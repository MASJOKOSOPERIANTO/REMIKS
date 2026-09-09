<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\VpnAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RouterOS\Client;
use RouterOS\Query;

class VpnController extends Controller
{
    /**
     * Menampilkan daftar VPN milik customer.
     */
    public function index()
    {
        $vpnAccounts = Auth::user()
            ->vpnAccounts()
            ->with('server')
            ->latest()
            ->get();

        $servers = Server::where('status', true)
            ->orderBy('name')
            ->get();

        return view('customer.vpn.index', compact(
            'vpnAccounts',
            'servers'
        ));
    }

    /**
     * Menampilkan form pembuatan VPN.
     */
    public function create()
    {
        $servers = Server::where('status', true)
            ->orderBy('name')
            ->get();

        return view('customer.vpn.create', compact('servers'));
    }

    /**
     * Membuat akun VPN di MikroTik.
     */
    public function store(Request $request)
    {
        /*
         |--------------------------------------------------------------------------
         | Biaya Pembuatan VPN
         |--------------------------------------------------------------------------
         */
        $vpnCost = 2000;

        /*
         |--------------------------------------------------------------------------
         | Validasi
         |--------------------------------------------------------------------------
         */
        $validated = $request->validate([
            'server_id' => [
                'required',
                'integer',
                'exists:servers,id',
            ],

            'vpn_type' => [
                'required',
                'in:l2tp,sstp',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
                'max:255',
            ],

            'to_port' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],
        ]);

        /*
         |--------------------------------------------------------------------------
         | Cek Saldo Customer
         |--------------------------------------------------------------------------
         */
        $customer = Auth::user();

        if ((float) $customer->balance < $vpnCost) {
            return back()
                ->withInput()
                ->withErrors([
                    'vpn' => 'Saldo tidak mencukupi. Biaya pembuatan VPN adalah Rp2.000.',
                ]);
        }

        /*
         |--------------------------------------------------------------------------
         | Ambil server aktif
         |--------------------------------------------------------------------------
         */
        $server = Server::where('status', true)
            ->findOrFail($validated['server_id']);

        /*
         |--------------------------------------------------------------------------
         | Tentukan service dan profile MikroTik
         |--------------------------------------------------------------------------
         */
        if ($validated['vpn_type'] === 'sstp') {
            $service = 'sstp';
            $profile = 'sstp-profile';
        } else {
            $service = 'l2tp';
            $profile = 'default-encryption';
        }

        try {

            /*
             |--------------------------------------------------------------------------
             | Koneksi ke MikroTik
             |--------------------------------------------------------------------------
             */
            $client = new Client([
                'host' => $server->host,
                'user' => $server->api_user,
                'pass' => $server->api_password,
                'port' => (int) $server->api_port,
                'timeout' => 10,
            ]);

            /*
             |--------------------------------------------------------------------------
             | Cek username sudah ada di MikroTik
             |--------------------------------------------------------------------------
             */
            $existingUser = (new Query('/ppp/secret/print'))
                ->where('name', $validated['username']);

            $existing = $client->query($existingUser)->read();

            if (!empty($existing)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'username' => 'Username VPN tersebut sudah digunakan di MikroTik.',
                    ]);
            }

            /*
             |--------------------------------------------------------------------------
             | Cari IP VPN yang belum digunakan
             |--------------------------------------------------------------------------
             */
            $usedIps = VpnAccount::whereNotNull('vpn_ip')
                ->pluck('vpn_ip')
                ->toArray();

            $vpnIp = null;

            for ($i = 254; $i >= 2; $i--) {

                $candidate = "10.20.20.$i";

                if (!in_array($candidate, $usedIps)) {
                    $vpnIp = $candidate;
                    break;
                }
            }

            if (!$vpnIp) {
                throw new \Exception('IP VPN sudah habis.');
            }

            /*
             |--------------------------------------------------------------------------
             | Buat PPP Secret
             |--------------------------------------------------------------------------
             */
            $query = new Query('/ppp/secret/add');

            $query
                ->equal('name', $validated['username'])
                ->equal('password', $validated['password'])
                ->equal('service', $service)
                ->equal('profile', $profile)
                ->equal('remote-address', $vpnIp)
                ->equal('comment', $validated['username']);

            $client->query($query)->read();

            /*
             |--------------------------------------------------------------------------
             | Generate Port Forward Acak
             |--------------------------------------------------------------------------
             */
            do {

                $randomDstPort = random_int(2000, 5000);

                $existsPort = VpnAccount::where(
                    'dst_port',
                    $randomDstPort
                )->exists();

            } while ($existsPort);

            /*
             |--------------------------------------------------------------------------
             | Buat NAT
             |--------------------------------------------------------------------------
             */
            $natQuery = new Query('/ip/firewall/nat/add');

            $natQuery
                ->equal('chain', 'dstnat')
                ->equal('protocol', 'tcp')
                ->equal('dst-port', $randomDstPort)
                ->equal('action', 'dst-nat')
                ->equal('to-addresses', $vpnIp)
                ->equal('to-ports', $validated['to_port'])
                ->equal('comment', $validated['username']);

            $client->query($natQuery)->read();

            /*
             |--------------------------------------------------------------------------
             | Masa Aktif 1 Bulan Kalender
             |--------------------------------------------------------------------------
             */
            $startedAt = now();
            $expiresAt = $startedAt->copy()->addMonth();

            /*
             |--------------------------------------------------------------------------
             | Simpan ke Database
             |--------------------------------------------------------------------------
             */
            VpnAccount::create([
                'user_id' => Auth::id(),
                'server_id' => $server->id,
                'vpn_type' => $validated['vpn_type'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'vpn_ip' => $vpnIp,
                'to_port' => $validated['to_port'],
                'dst_port' => $randomDstPort,
                'status' => 'active',
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
            ]);

            /*
             |--------------------------------------------------------------------------
             | Potong Saldo Customer
             |--------------------------------------------------------------------------
             */
            $customer->decrement('balance', $vpnCost);

            /*
             |--------------------------------------------------------------------------
             | Catat Transaksi Pemakaian VPN
             |--------------------------------------------------------------------------
             */
            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'vpn',
                'amount' => -$vpnCost,
                'status' => 'approved',
                'description' => 'Biaya pembuatan VPN - ' . $validated['username'],
                'payment_method' => null,
                'proof' => null,
                'confirmed_by' => null,
                'confirmed_at' => now(),
            ]);

            /*
             |--------------------------------------------------------------------------
             | Berhasil
             |--------------------------------------------------------------------------
             */
            return redirect()
                ->route('customer.vpn.index')
                ->with(
                    'success',
                    'Akun VPN berhasil dibuat di MikroTik. Saldo terpotong Rp2.000.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'vpn' => 'Gagal membuat akun VPN: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Mengubah akun VPN di MikroTik dan database.
     */
    public function update(
        Request $request,
        VpnAccount $vpnAccount
    ) {
        /*
         |--------------------------------------------------------------------------
         | Pastikan VPN milik customer
         |--------------------------------------------------------------------------
         */
        if ($vpnAccount->user_id !== Auth::id()) {
            abort(
                403,
                'Anda tidak memiliki akses ke akun VPN ini.'
            );
        }

        /*
         |--------------------------------------------------------------------------
         | Validasi
         |--------------------------------------------------------------------------
         */
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
                'max:255',
            ],

            'to_port' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],
        ]);

        /*
         |--------------------------------------------------------------------------
         | Ambil server
         |--------------------------------------------------------------------------
         */
        $server = $vpnAccount->server;

        if (!$server) {
            return back()->withErrors([
                'vpn' => 'Server MikroTik untuk akun VPN ini tidak ditemukan.',
            ]);
        }

        /*
         |--------------------------------------------------------------------------
         | Simpan username lama
         |--------------------------------------------------------------------------
         */
        $oldUsername = $vpnAccount->username;

        try {

            /*
             |--------------------------------------------------------------------------
             | Koneksi ke MikroTik
             |--------------------------------------------------------------------------
             */
            $client = new Client([
                'host' => $server->host,
                'user' => $server->api_user,
                'pass' => $server->api_password,
                'port' => (int) $server->api_port,
                'timeout' => 10,
            ]);

            /*
             |--------------------------------------------------------------------------
             | 1. Cari PPP Secret berdasarkan username lama
             |--------------------------------------------------------------------------
             */
            $pppQuery = new Query('/ppp/secret/print');

            $pppQuery
                ->where('name', $oldUsername);

            $pppSecrets = $client->query($pppQuery)->read();

            /*
             |--------------------------------------------------------------------------
             | 2. Update PPP Secret
             |--------------------------------------------------------------------------
             */
            foreach ($pppSecrets as $pppSecret) {

                if (!empty($pppSecret['.id'])) {

                    $updatePpp = new Query('/ppp/secret/set');

                    $updatePpp
                        ->equal('.id', $pppSecret['.id'])
                        ->equal(
                            'name',
                            $validated['username']
                        )
                        ->equal(
                            'password',
                            $validated['password']
                        );

                    $client->query($updatePpp)->read();
                }
            }

            /*
             |--------------------------------------------------------------------------
             | 3. Ambil semua NAT
             |--------------------------------------------------------------------------
             */
            $natQuery = new Query('/ip/firewall/nat/print');

            $natRules = $client->query($natQuery)->read();

            /*
             |--------------------------------------------------------------------------
             | 4. Cari NAT yang benar berdasarkan comment
             |--------------------------------------------------------------------------
             */
            $matchingNatRules = [];

            foreach ($natRules as $natRule) {

                if (
                    isset($natRule['comment']) &&
                    $natRule['comment'] === $oldUsername
                ) {
                    $matchingNatRules[] = $natRule;
                }
            }

            /*
             |--------------------------------------------------------------------------
             | 5. Update NAT yang cocok saja
             |--------------------------------------------------------------------------
             */
            foreach ($matchingNatRules as $natRule) {

                if (!empty($natRule['.id'])) {

                    $updateNat = new Query('/ip/firewall/nat/set');

                    $updateNat
                        ->equal('.id', $natRule['.id'])
                        ->equal(
                            'to-ports',
                            $validated['to_port']
                        )
                        ->equal(
                            'comment',
                            $validated['username']
                        );

                    $client->query($updateNat)->read();
                }
            }

            /*
             |--------------------------------------------------------------------------
             | 6. Update database
             |--------------------------------------------------------------------------
             */
            $vpnAccount->update([
                'username' => $validated['username'],
                'password' => $validated['password'],
                'to_port' => $validated['to_port'],
            ]);

            return redirect()
                ->route('customer.vpn.index')
                ->with(
                    'success',
                    'Akun VPN berhasil diperbarui.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'vpn' => 'Gagal memperbarui akun VPN: ' . $e->getMessage(),
                ]);
        }
    }


    /**
     * Mengaktifkan / menonaktifkan auto renewal VPN.
     */
    public function toggleAutoRenew(VpnAccount $vpnAccount)
    {
        if ($vpnAccount->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke akun VPN ini.');
        }

        $vpnAccount->update([
            'auto_renew' => ! $vpnAccount->auto_renew,
        ]);

        $message = $vpnAccount->auto_renew
            ? 'Auto Perpanjangan berhasil diaktifkan.'
            : 'Auto Perpanjangan berhasil dinonaktifkan.';

        return redirect()
            ->route('customer.vpn.index')
            ->with('success', $message);
    }

    /**
     * Melanjutkan kembali langganan VPN yang sudah expired.
     *
     * Biaya: Rp2.000
     * Masa aktif baru dimulai dari sekarang dan berlaku 1 bulan.
     */
    public function resumeSubscription(VpnAccount $vpnAccount)
    {
        if ($vpnAccount->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke akun VPN ini.');
        }

        if ($vpnAccount->status !== 'expired') {
            return back()->withErrors([
                'vpn' => 'VPN ini belum berstatus expired sehingga tidak perlu dilanjutkan.',
            ]);
        }

        $renewalCost = 2000;
        $customer = $vpnAccount->user;
        $server = $vpnAccount->server;

        if (!$customer) {
            return back()->withErrors([
                'vpn' => 'Customer pemilik VPN tidak ditemukan.',
            ]);
        }

        if (!$server) {
            return back()->withErrors([
                'vpn' => 'Server MikroTik untuk akun VPN ini tidak ditemukan.',
            ]);
        }

        if ((float) $customer->balance < $renewalCost) {
            return back()->withErrors([
                'vpn' => 'Saldo tidak mencukupi. Biaya melanjutkan berlangganan adalah Rp2.000.',
            ]);
        }

        try {
            $client = new Client([
                'host' => $server->host,
                'user' => $server->api_user,
                'pass' => $server->api_password,
                'port' => (int) $server->api_port,
                'timeout' => 10,
            ]);

            $pppQuery = new Query('/ppp/secret/print');
            $pppQuery->where('name', $vpnAccount->username);

            $pppSecrets = $client->query($pppQuery)->read();

            if (empty($pppSecrets)) {
                throw new \Exception('PPP Secret tidak ditemukan di MikroTik.');
            }

            foreach ($pppSecrets as $pppSecret) {
                if (empty($pppSecret['.id'])) {
                    continue;
                }

                $updatePpp = new Query('/ppp/secret/set');
                $updatePpp
                    ->equal('.id', $pppSecret['.id'])
                    ->equal('disabled', 'no');

                $client->query($updatePpp)->read();
            }

            $customer->decrement('balance', $renewalCost);

            $startedAt = now();
            $expiresAt = $startedAt->copy()->addMonth();

            $vpnAccount->update([
                'status' => 'active',
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
            ]);

            Transaction::create([
                'user_id' => $customer->id,
                'type' => 'vpn',
                'amount' => -$renewalCost,
                'status' => 'approved',
                'description' => 'Lanjutkan berlangganan VPN - ' . $vpnAccount->username,
                'payment_method' => null,
                'proof' => null,
                'confirmed_by' => null,
                'confirmed_at' => now(),
            ]);

            return redirect()
                ->route('customer.vpn.index')
                ->with('success', 'Langganan VPN berhasil dilanjutkan. Saldo terpotong Rp2.000.');

        } catch (\Throwable $e) {
            return back()->withErrors([
                'vpn' => 'Gagal melanjutkan berlangganan VPN: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Menghapus akun VPN dari MikroTik dan database.
     */
    public function destroy(VpnAccount $vpnAccount)
    {
        /*
         |--------------------------------------------------------------------------
         | Pastikan VPN milik customer
         |--------------------------------------------------------------------------
         */
        if ($vpnAccount->user_id !== Auth::id()) {
            abort(
                403,
                'Anda tidak memiliki akses ke akun VPN ini.'
            );
        }

        /*
         |--------------------------------------------------------------------------
         | Ambil server
         |--------------------------------------------------------------------------
         */
        $server = $vpnAccount->server;

        if (!$server) {
            return back()->withErrors([
                'vpn' => 'Server MikroTik untuk akun VPN ini tidak ditemukan.',
            ]);
        }

        try {

            /*
             |--------------------------------------------------------------------------
             | Koneksi ke MikroTik
             |--------------------------------------------------------------------------
             */
            $client = new Client([
                'host' => $server->host,
                'user' => $server->api_user,
                'pass' => $server->api_password,
                'port' => (int) $server->api_port,
                'timeout' => 10,
            ]);

            /*
             |--------------------------------------------------------------------------
             | 1. Cari PPP Secret
             |--------------------------------------------------------------------------
             */
            $pppQuery = new Query('/ppp/secret/print');

            $pppQuery
                ->where('name', $vpnAccount->username);

            $pppSecrets = $client->query($pppQuery)->read();

            /*
             |--------------------------------------------------------------------------
             | 2. Hapus PPP Secret
             |--------------------------------------------------------------------------
             */
            foreach ($pppSecrets as $pppSecret) {

                if (!empty($pppSecret['.id'])) {

                    $removePpp = new Query('/ppp/secret/remove');

                    $removePpp
                        ->equal('.id', $pppSecret['.id']);

                    $client->query($removePpp)->read();
                }
            }

            /*
             |--------------------------------------------------------------------------
             | 3. Cari NAT berdasarkan username
             |--------------------------------------------------------------------------
             */
            $natQuery = new Query('/ip/firewall/nat/print');

            $natQuery
                ->where('comment', $vpnAccount->username);

            $natRules = $client->query($natQuery)->read();

            /*
             |--------------------------------------------------------------------------
             | 4. Hapus NAT
             |--------------------------------------------------------------------------
             */
            foreach ($natRules as $natRule) {

                if (!empty($natRule['.id'])) {

                    $removeNat = new Query(
                        '/ip/firewall/nat/remove'
                    );

                    $removeNat
                        ->equal('.id', $natRule['.id']);

                    $client->query($removeNat)->read();
                }
            }

            /*
             |--------------------------------------------------------------------------
             | 5. Hapus dari database
             |--------------------------------------------------------------------------
             */
            $vpnAccount->delete();

            return redirect()
                ->route('customer.vpn.index')
                ->with(
                    'success',
                    'Akun VPN berhasil dihapus dari MikroTik dan database.'
                );

        } catch (\Throwable $e) {

            return back()->withErrors([
                'vpn' => 'Gagal menghapus akun VPN: ' . $e->getMessage(),
            ]);
        }
    }
}
