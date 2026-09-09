<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    /**
     * Menampilkan semua metode pembayaran.
     */
    public function index()
    {
        $paymentSettings = PaymentSetting::latest()->get();

        return view(
            'admin.payment-settings.index',
            compact('paymentSettings')
        );
    }

    /**
     * Menyimpan metode pembayaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => [
                'required',
                'in:bank,qris,ewallet',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'account_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'qris_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ], [
            'type.required' => 'Jenis pembayaran wajib dipilih.',
            'type.in' => 'Jenis pembayaran tidak valid.',

            'name.required' => 'Nama pembayaran wajib diisi.',
            'name.max' => 'Nama pembayaran maksimal 100 karakter.',

            'qris_image.image' => 'File QRIS harus berupa gambar.',
            'qris_image.mimes' => 'QRIS harus berformat JPG, JPEG, PNG, atau WEBP.',
            'qris_image.max' => 'Ukuran gambar QRIS maksimal 5 MB.',
        ]);

        try {

            $qrisImagePath = null;

            if ($request->hasFile('qris_image')) {
                $qrisImagePath = $request
                    ->file('qris_image')
                    ->store('payment-settings', 'public');
            }

            PaymentSetting::create([
                'type' => $validated['type'],
                'name' => $validated['name'],
                'account_number' => $validated['account_number'] ?? null,
                'account_name' => $validated['account_name'] ?? null,
                'qris_image' => $qrisImagePath,
                'status' => $request->boolean('status', true),
            ]);

            return redirect()
                ->route('admin.payment-settings.index')
                ->with(
                    'success',
                    'Metode pembayaran berhasil ditambahkan.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'payment' =>
                        'Gagal menambahkan metode pembayaran: ' .
                        $e->getMessage(),
                ]);
        }
    }

    /**
     * Memperbarui metode pembayaran.
     */
    public function update(
        Request $request,
        PaymentSetting $paymentSetting
    ) {
        $validated = $request->validate([
            'type' => [
                'required',
                'in:bank,qris,ewallet',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'account_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'qris_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        try {

            $qrisImagePath = $paymentSetting->qris_image;

            if ($request->hasFile('qris_image')) {

                if (
                    $paymentSetting->qris_image &&
                    Storage::disk('public')->exists(
                        $paymentSetting->qris_image
                    )
                ) {
                    Storage::disk('public')->delete(
                        $paymentSetting->qris_image
                    );
                }

                $qrisImagePath = $request
                    ->file('qris_image')
                    ->store('payment-settings', 'public');
            }

            $paymentSetting->update([
                'type' => $validated['type'],
                'name' => $validated['name'],
                'account_number' => $validated['account_number'] ?? null,
                'account_name' => $validated['account_name'] ?? null,
                'qris_image' => $qrisImagePath,
                'status' => $request->boolean('status', false),
            ]);

            return redirect()
                ->route('admin.payment-settings.index')
                ->with(
                    'success',
                    'Metode pembayaran berhasil diperbarui.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'payment' =>
                        'Gagal memperbarui metode pembayaran: ' .
                        $e->getMessage(),
                ]);
        }
    }

    /**
     * Mengaktifkan / menonaktifkan pembayaran.
     */
    public function toggle(PaymentSetting $paymentSetting)
    {
        try {

            $paymentSetting->update([
                'status' => !$paymentSetting->status,
            ]);

            return redirect()
                ->route('admin.payment-settings.index')
                ->with(
                    'success',
                    $paymentSetting->status
                        ? 'Metode pembayaran diaktifkan.'
                        : 'Metode pembayaran dinonaktifkan.'
                );

        } catch (\Throwable $e) {

            return back()->withErrors([
                'payment' =>
                    'Gagal mengubah status pembayaran: ' .
                    $e->getMessage(),
            ]);
        }
    }

    /**
     * Menghapus metode pembayaran.
     */
    public function destroy(PaymentSetting $paymentSetting)
    {
        try {

            if (
                $paymentSetting->qris_image &&
                Storage::disk('public')->exists(
                    $paymentSetting->qris_image
                )
            ) {
                Storage::disk('public')->delete(
                    $paymentSetting->qris_image
                );
            }

            $paymentSetting->delete();

            return redirect()
                ->route('admin.payment-settings.index')
                ->with(
                    'success',
                    'Metode pembayaran berhasil dihapus.'
                );

        } catch (\Throwable $e) {

            return back()->withErrors([
                'payment' =>
                    'Gagal menghapus metode pembayaran: ' .
                    $e->getMessage(),
            ]);
        }
    }
}
