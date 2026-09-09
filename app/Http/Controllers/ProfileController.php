<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's default Breeze profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's default Breeze profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Display customer account settings.
     */
    public function customerSettings(Request $request): View
    {
        return view('customer.settings.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update customer account information.
     *
     * Digunakan hanya untuk:
     * - Nama
     * - Email
     */
    public function customerSettingsUpdate(Request $request): RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Validasi Nama dan Email
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'name.required' =>
                'Nama wajib diisi.',

            'name.string' =>
                'Nama harus berupa teks.',

            'name.max' =>
                'Nama maksimal 255 karakter.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.max' =>
                'Email maksimal 255 karakter.',

            'email.unique' =>
                'Email tersebut sudah digunakan oleh pengguna lain.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Nama
        |--------------------------------------------------------------------------
        */
        $user->name = $validated['name'];

        /*
        |--------------------------------------------------------------------------
        | Update Email
        |--------------------------------------------------------------------------
        */
        $user->email = $validated['email'];

        /*
        |--------------------------------------------------------------------------
        | Jika email berubah
        |--------------------------------------------------------------------------
        */
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan
        |--------------------------------------------------------------------------
        */
        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Kembali ke Pengaturan Akun
        |--------------------------------------------------------------------------
        */
        return Redirect::route('customer.settings')
            ->with(
                'success',
                'Informasi akun berhasil diperbarui.'
            );
    }

    /**
     * Update customer password.
     *
     * Method ini KHUSUS untuk password.
     * Tidak meminta nama dan email.
     */
    public function customerPasswordUpdate(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Validasi Password
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'current_password.required' =>
                'Password saat ini wajib diisi.',

            'current_password.current_password' =>
                'Password saat ini tidak benar.',

            'password.required' =>
                'Password baru wajib diisi.',

            'password.string' =>
                'Password baru harus berupa teks.',

            'password.min' =>
                'Password baru minimal 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password baru tidak cocok.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Simpan Password Baru
        |--------------------------------------------------------------------------
        */
        $request->user()->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Kembali ke Pengaturan Akun
        |--------------------------------------------------------------------------
        */
        return Redirect::route('customer.settings')
            ->with(
                'success',
                'Password berhasil diperbarui.'
            );
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => [
                'required',
                'current_password',
            ],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
