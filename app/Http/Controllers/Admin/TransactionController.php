<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Menampilkan seluruh transaksi top up.
     */
    public function index()
    {
        $transactions = Transaction::with('user', 'confirmer')
            ->where('type', 'topup')
            ->latest()
            ->get();

        return view(
            'admin.transactions.index',
            compact('transactions')
        );
    }

    /**
     * Menyetujui transaksi top up.
     *
     * Saldo customer akan bertambah satu kali saja.
     */
    public function approve(Transaction $transaction)
    {
        if ($transaction->type !== 'topup') {
            return back()->withErrors([
                'transaction' => 'Transaksi ini bukan transaksi top up.',
            ]);
        }

        if ($transaction->status !== 'pending') {
            return back()->withErrors([
                'transaction' => 'Transaksi ini sudah diproses sebelumnya.',
            ]);
        }

        try {

            DB::transaction(function () use ($transaction) {

                /*
                 * Kunci transaksi agar tidak dapat diproses
                 * dua kali secara bersamaan.
                 */
                $lockedTransaction = Transaction::whereKey($transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * Cek ulang status setelah transaksi dikunci.
                 */
                if ($lockedTransaction->status !== 'pending') {
                    throw new \Exception(
                        'Transaksi sudah diproses sebelumnya.'
                    );
                }

                $user = $lockedTransaction->user()
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * Tambahkan saldo customer.
                 */
                $user->increment(
                    'balance',
                    $lockedTransaction->amount
                );

                /*
                 * Tandai transaksi sebagai approved.
                 */
                $lockedTransaction->update([
                    'status' => 'approved',
                    'confirmed_by' => Auth::id(),
                    'confirmed_at' => now(),
                ]);
            });

            return redirect()
                ->route('admin.transactions.index')
                ->with(
                    'success',
                    'Top up berhasil dikonfirmasi dan saldo customer telah ditambahkan.'
                );

        } catch (\Throwable $e) {

            return back()->withErrors([
                'transaction' =>
                    'Gagal mengonfirmasi top up: ' .
                    $e->getMessage(),
            ]);
        }
    }

    /**
     * Menolak transaksi top up.
     */
    public function reject(
        Request $request,
        Transaction $transaction
    ) {
        if ($transaction->type !== 'topup') {
            return back()->withErrors([
                'transaction' => 'Transaksi ini bukan transaksi top up.',
            ]);
        }

        if ($transaction->status !== 'pending') {
            return back()->withErrors([
                'transaction' => 'Transaksi ini sudah diproses sebelumnya.',
            ]);
        }

        $validated = $request->validate([
            'reason' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        try {

            $transaction->update([
                'status' => 'rejected',
                'description' => $validated['reason']
                    ? 'Top up ditolak: ' . $validated['reason']
                    : 'Top up ditolak oleh admin.',
                'confirmed_by' => Auth::id(),
                'confirmed_at' => now(),
            ]);

            return redirect()
                ->route('admin.transactions.index')
                ->with(
                    'success',
                    'Pengajuan top up telah ditolak.'
                );

        } catch (\Throwable $e) {

            return back()->withErrors([
                'transaction' =>
                    'Gagal menolak top up: ' .
                    $e->getMessage(),
            ]);
        }
    }

    /**
     * Menghapus transaksi.
     */
    public function destroy(Transaction $transaction)
    {
        try {

            /*
             * Hapus bukti pembayaran dari storage
             * jika transaksi memiliki file.
             */
            if (
                $transaction->proof &&
                Storage::disk('public')->exists(
                    $transaction->proof
                )
            ) {
                Storage::disk('public')->delete(
                    $transaction->proof
                );
            }

            $transaction->delete();

            return redirect()
                ->route('admin.transactions.index')
                ->with(
                    'success',
                    'Transaksi berhasil dihapus.'
                );

        } catch (\Throwable $e) {

            return back()->withErrors([
                'transaction' =>
                    'Gagal menghapus transaksi: ' .
                    $e->getMessage(),
            ]);
        }
    }
}
