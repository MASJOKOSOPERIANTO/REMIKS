<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopupController extends Controller
{
    /**
     * Menampilkan halaman top up customer.
     */
    public function index()
    {
        // Riwayat top up milik customer yang sedang login
        $transactions = Auth::user()
            ->transactions()
            ->where('type', 'topup')
            ->latest()
            ->get();

        // Ambil hanya metode pembayaran yang aktif
        $paymentSettings = PaymentSetting::where('status', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view(
            'customer.topup.index',
            [
                'transactions' => $transactions,
                'paymentSettings' => $paymentSettings,
            ]
        );
    }

    /**
     * Menyimpan pengajuan top up customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1000',
                'max:100000000',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:100',
            ],

            'proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ], [
            'amount.required' => 'Nominal top up wajib diisi.',
            'amount.numeric' => 'Nominal top up harus berupa angka.',
            'amount.min' => 'Minimal top up adalah Rp1.000.',
            'amount.max' => 'Maksimal top up adalah Rp100.000.000.',

            'payment_method.required' => 'Metode pembayaran wajib dipilih.',

            'proof.required' => 'Bukti pembayaran wajib diupload.',
            'proof.file' => 'File bukti pembayaran tidak valid.',
            'proof.mimes' => 'Bukti pembayaran harus JPG, JPEG, PNG, atau PDF.',
            'proof.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        try {

            /*
             * Simpan bukti pembayaran
             */
            $proofPath = $request
                ->file('proof')
                ->store('topups', 'public');

            /*
             * Buat transaksi top up.
             *
             * STATUS MASIH PENDING.
             *
             * Saldo customer BELUM bertambah.
             */
            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'topup',
                'amount' => $validated['amount'],
                'status' => 'pending',
                'description' => 'Pengajuan top up saldo',
                'payment_method' => $validated['payment_method'],
                'proof' => $proofPath,
                'confirmed_by' => null,
                'confirmed_at' => null,
            ]);

            /*
             * Setelah pengajuan berhasil dibuat,
             * customer langsung diarahkan ke halaman transaksi.
             */
            return redirect()
                ->route('customer.transactions.index')
                ->with(
                    'success',
                    'Pengajuan top up berhasil dikirim dan menunggu konfirmasi admin.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'topup' =>
                        'Gagal mengirim pengajuan top up: ' .
                        $e->getMessage(),
                ]);
        }
    }
}
