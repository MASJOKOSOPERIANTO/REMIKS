<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\VpnAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RouterOS\Client;
use RouterOS\Query;

class ProcessVpnRenewals extends Command
{
    /**
     * Nama dan signature command.
     *
     * Contoh:
     * php artisan vpn:process-renewals
     * php artisan vpn:process-renewals --vpn-id=1
     */
    protected $signature = 'vpn:process-renewals
                            {--vpn-id= : Hanya memproses VPN dengan ID tertentu}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Memproses masa berlaku dan auto renewal akun VPN customer.';

    /**
     * Biaya perpanjangan VPN per bulan.
     */
    private int $renewalCost = 2000;

    /**
     * Jalankan command.
     */
    public function handle(): int
    {
        $this->info('Memulai proses VPN renewal...');
        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Query VPN
        |--------------------------------------------------------------------------
        |
        | Hanya VPN yang:
        | - status masih active
        | - expires_at sudah jatuh tempo
        |
        | Dengan demikian command aman dijalankan berkali-kali.
        |
        |--------------------------------------------------------------------------
        */
        $query = VpnAccount::query()
            ->with(['server', 'user'])
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());

        /*
        |--------------------------------------------------------------------------
        | Jika --vpn-id diberikan
        |--------------------------------------------------------------------------
        */
        if ($this->option('vpn-id')) {
            $query->where('id', $this->option('vpn-id'));
        }

        $vpnAccounts = $query
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Tidak ada VPN jatuh tempo
        |--------------------------------------------------------------------------
        */
        if ($vpnAccounts->isEmpty()) {
            $this->info('Tidak ada VPN yang perlu diproses.');
            return self::SUCCESS;
        }

        $this->info(
            'Ditemukan ' . $vpnAccounts->count() . ' VPN yang perlu diproses.'
        );

        $this->newLine();

        $successCount = 0;
        $expiredCount = 0;
        $failedCount = 0;

        /*
        |--------------------------------------------------------------------------
        | Proses satu per satu
        |--------------------------------------------------------------------------
        */
        foreach ($vpnAccounts as $vpnAccount) {

            $this->line(
                '------------------------------------------------------------'
            );

            $this->info(
                'VPN #' . $vpnAccount->id .
                ' | Username: ' . $vpnAccount->username
            );

            try {

                /*
                |--------------------------------------------------------------------------
                | Lock database
                |--------------------------------------------------------------------------
                |
                | Lock digunakan agar proses yang sama tidak berjalan
                | bersamaan dan melakukan potongan saldo dua kali.
                |
                |--------------------------------------------------------------------------
                */
                $result = DB::transaction(function () use ($vpnAccount) {

                    $vpn = VpnAccount::query()
                        ->with(['server', 'user'])
                        ->lockForUpdate()
                        ->find($vpnAccount->id);

                    /*
                    |--------------------------------------------------------------------------
                    | VPN sudah tidak perlu diproses
                    |--------------------------------------------------------------------------
                    */
                    if (!$vpn) {
                        return [
                            'result' => 'skip',
                            'message' => 'VPN tidak ditemukan.',
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Cek kembali status dan tanggal expired
                    |--------------------------------------------------------------------------
                    |
                    | Penting untuk mencegah double processing.
                    |
                    |--------------------------------------------------------------------------
                    */
                    if (
                        $vpn->status !== 'active' ||
                        !$vpn->expires_at ||
                        $vpn->expires_at->isFuture()
                    ) {
                        return [
                            'result' => 'skip',
                            'message' => 'VPN sudah diproses atau belum jatuh tempo.',
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Server MikroTik
                    |--------------------------------------------------------------------------
                    */
                    $server = $vpn->server;

                    if (!$server) {
                        throw new \Exception(
                            'Server MikroTik tidak ditemukan.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Customer
                    |--------------------------------------------------------------------------
                    */
                    $customer = $vpn->user;

                    if (!$customer) {
                        throw new \Exception(
                            'Customer pemilik VPN tidak ditemukan.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Jika Auto Renew OFF
                    |--------------------------------------------------------------------------
                    |
                    | Tidak ada renewal.
                    | VPN langsung expired dan PPP Secret dinonaktifkan.
                    |
                    |--------------------------------------------------------------------------
                    */
                    if (!$vpn->auto_renew) {

                        $this->disableVpnOnMikrotik($vpn);

                        $vpn->update([
                            'status' => 'expired',
                        ]);

                        return [
                            'result' => 'expired',
                            'message' => 'Auto Renew OFF. VPN dinonaktifkan dan status menjadi expired.',
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Lock saldo customer
                    |--------------------------------------------------------------------------
                    */
                    $customer = $customer->newQuery()
                        ->lockForUpdate()
                        ->find($customer->id);

                    if (!$customer) {
                        throw new \Exception(
                            'Data customer tidak ditemukan.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Cek saldo
                    |--------------------------------------------------------------------------
                    */
                    if ((float) $customer->balance < $this->renewalCost) {

                        /*
                        |--------------------------------------------------------------------------
                        | Saldo tidak cukup
                        |--------------------------------------------------------------------------
                        |
                        | Tidak melakukan pemotongan saldo.
                        | VPN dinonaktifkan tetapi data tetap disimpan.
                        |
                        |--------------------------------------------------------------------------
                        */
                        $this->disableVpnOnMikrotik($vpn);

                        $vpn->update([
                            'status' => 'expired',
                        ]);

                        return [
                            'result' => 'expired',
                            'message' => 'Saldo tidak mencukupi. VPN dinonaktifkan dan status menjadi expired.',
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Saldo cukup
                    |--------------------------------------------------------------------------
                    |
                    | Aktifkan kembali PPP Secret terlebih dahulu.
                    |
                    |--------------------------------------------------------------------------
                    */
                    $this->enableVpnOnMikrotik($vpn);

                    /*
                    |--------------------------------------------------------------------------
                    | Potong saldo
                    |--------------------------------------------------------------------------
                    */
                    $customer->decrement(
                        'balance',
                        $this->renewalCost
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Masa aktif diperpanjang 1 bulan
                    |--------------------------------------------------------------------------
                    |
                    | Kita memperpanjang dari expires_at lama,
                    | bukan dari waktu sekarang.
                    |
                    | Contoh:
                    |
                    | expired 10 Januari
                    | renewal 11 Januari
                    |
                    | hasil:
                    | expires_at = 10 Februari
                    |
                    | Hal ini mencegah customer kehilangan sisa
                    | periode akibat keterlambatan scheduler.
                    |
                    |--------------------------------------------------------------------------
                    */
                    $newExpiresAt = $vpn->expires_at
                        ->copy()
                        ->addMonth();

                    $vpn->update([
                        'status' => 'active',
                        'expires_at' => $newExpiresAt,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Catat transaksi
                    |--------------------------------------------------------------------------
                    */
                    Transaction::create([
                        'user_id' => $customer->id,
                        'type' => 'vpn',
                        'amount' => -$this->renewalCost,
                        'status' => 'approved',
                        'description' => 'Auto renewal VPN - ' . $vpn->username,
                        'payment_method' => null,
                        'proof' => null,
                        'confirmed_by' => null,
                        'confirmed_at' => now(),
                    ]);

                    return [
                        'result' => 'renewed',
                        'message' => 'VPN berhasil diperpanjang 1 bulan. Saldo terpotong Rp2.000.',
                        'new_expires_at' => $newExpiresAt->format('d/m/Y H:i'),
                    ];
                });

                /*
                |--------------------------------------------------------------------------
                | Tampilkan hasil
                |--------------------------------------------------------------------------
                */
                if ($result['result'] === 'renewed') {

                    $successCount++;

                    $this->info(
                        '✓ BERHASIL: ' . $result['message']
                    );

                    if (!empty($result['new_expires_at'])) {
                        $this->line(
                            '  Expired baru: ' . $result['new_expires_at']
                        );
                    }

                } elseif ($result['result'] === 'expired') {

                    $expiredCount++;

                    $this->warn(
                        '⚠ EXPIRED: ' . $result['message']
                    );

                } else {

                    $this->line(
                        '→ SKIP: ' . $result['message']
                    );
                }

            } catch (\Throwable $e) {

                $failedCount++;

                $this->error(
                    '✗ GAGAL: ' . $e->getMessage()
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */
        $this->newLine();

        $this->line(
            '============================================================'
        );

        $this->info('PROSES SELESAI');

        $this->line(
            'Berhasil Renewal : ' . $successCount
        );

        $this->line(
            'Menjadi Expired  : ' . $expiredCount
        );

        $this->line(
            'Gagal            : ' . $failedCount
        );

        $this->line(
            '============================================================'
        );

        return $failedCount > 0
            ? self::FAILURE
            : self::SUCCESS;
    }

    /**
     * Membuat koneksi ke MikroTik.
     */
    private function createRouterClient(VpnAccount $vpn): Client
    {
        $server = $vpn->server;

        if (!$server) {
            throw new \Exception(
                'Server MikroTik tidak ditemukan.'
            );
        }

        return new Client([
            'host' => $server->host,
            'user' => $server->api_user,
            'pass' => $server->api_password,
            'port' => (int) $server->api_port,
            'timeout' => 10,
        ]);
    }

    /**
     * Disable PPP Secret di MikroTik.
     */
    private function disableVpnOnMikrotik(VpnAccount $vpn): void
    {
        $client = $this->createRouterClient($vpn);

        $query = new Query('/ppp/secret/print');

        $query->where(
            'name',
            $vpn->username
        );

        $pppSecrets = $client
            ->query($query)
            ->read();

        if (empty($pppSecrets)) {
            throw new \Exception(
                'PPP Secret tidak ditemukan di MikroTik.'
            );
        }

        foreach ($pppSecrets as $pppSecret) {

            if (empty($pppSecret['.id'])) {
                continue;
            }

            $updateQuery = new Query('/ppp/secret/set');

            $updateQuery
                ->equal('.id', $pppSecret['.id'])
                ->equal('disabled', 'yes');

            $client
                ->query($updateQuery)
                ->read();
        }
    }

    /**
     * Enable PPP Secret di MikroTik.
     */
    private function enableVpnOnMikrotik(VpnAccount $vpn): void
    {
        $client = $this->createRouterClient($vpn);

        $query = new Query('/ppp/secret/print');

        $query->where(
            'name',
            $vpn->username
        );

        $pppSecrets = $client
            ->query($query)
            ->read();

        if (empty($pppSecrets)) {
            throw new \Exception(
                'PPP Secret tidak ditemukan di MikroTik.'
            );
        }

        foreach ($pppSecrets as $pppSecret) {

            if (empty($pppSecret['.id'])) {
                continue;
            }

            $updateQuery = new Query('/ppp/secret/set');

            $updateQuery
                ->equal('.id', $pppSecret['.id'])
                ->equal('disabled', 'no');

            $client
                ->query($updateQuery)
                ->read();
        }
    }
}
