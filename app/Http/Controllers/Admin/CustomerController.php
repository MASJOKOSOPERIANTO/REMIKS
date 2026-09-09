<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Menampilkan seluruh customer.
     */
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->latest()
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Menampilkan form tambah customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Menyimpan customer baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail customer.
     */
    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Menampilkan form edit customer.
     */
    public function edit(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Memperbarui data customer.
     */
    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($customer->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Password hanya diubah jika admin mengisi password baru.
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $customer->update($data);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Data customer berhasil diperbarui.');
    }

    /**
     * Menghapus customer.
     */
    public function destroy(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }

    /**
     * Mengaktifkan atau menonaktifkan akun customer.
     */
    public function toggleStatus(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->update([
            'is_active' => !$customer->is_active,
        ]);

        $status = $customer->is_active
            ? 'diaktifkan'
            : 'dinonaktifkan';

        return redirect()
            ->route('admin.customers.index')
            ->with('success', "Akun customer berhasil {$status}.");
    }

    /**
     * Mengganti password customer.
     */
    public function resetPassword(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Password customer berhasil diubah.');
    }
}
