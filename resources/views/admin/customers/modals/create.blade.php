{{-- MODAL TAMBAH CUSTOMER --}}

<div
    x-show="openCreateCustomer"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto
           bg-slate-900/50 px-4 py-6"
>
    <div
        @click.outside="openCreateCustomer = false"
        class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl"
    >

        {{-- HEADER --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

            <div>
                <h3 class="text-lg font-semibold text-slate-900">
                    Tambah Customer
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Tambahkan akun customer baru ke dalam sistem.
                </p>
            </div>

            <button
                type="button"
                @click="openCreateCustomer = false"
                class="rounded-lg px-3 py-2 text-slate-400 transition
                       hover:bg-slate-100 hover:text-slate-600"
            >
                ✕
            </button>

        </div>


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('admin.customers.store') }}"
        >

            @csrf

            <div class="space-y-5 px-6 py-6">

                {{-- NAMA --}}
                <div>

                    <label
                        for="customer_name"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Nama Customer
                    </label>

                    <input
                        id="customer_name"
                        name="name"
                        type="text"
                        required
                        autocomplete="name"
                        placeholder="Masukkan nama customer"
                        value="{{ old('name') }}"
                        class="mt-2 w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-sm text-slate-900
                               outline-none transition
                               focus:border-blue-500 focus:bg-white
                               focus:ring-4 focus:ring-blue-500/10"
                    >

                    @error('name')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- EMAIL --}}
                <div>

                    <label
                        for="customer_email"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Email
                    </label>

                    <input
                        id="customer_email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="customer@email.com"
                        value="{{ old('email') }}"
                        class="mt-2 w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-sm text-slate-900
                               outline-none transition
                               focus:border-blue-500 focus:bg-white
                               focus:ring-4 focus:ring-blue-500/10"
                    >

                    @error('email')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- PASSWORD --}}
                <div>

                    <label
                        for="customer_password"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Password
                    </label>

                    <input
                        id="customer_password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="mt-2 w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-sm text-slate-900
                               outline-none transition
                               focus:border-blue-500 focus:bg-white
                               focus:ring-4 focus:ring-blue-500/10"
                    >

                    @error('password')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- CONFIRM PASSWORD --}}
                <div>

                    <label
                        for="customer_password_confirmation"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Konfirmasi Password
                    </label>

                    <input
                        id="customer_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                        class="mt-2 w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-sm text-slate-900
                               outline-none transition
                               focus:border-blue-500 focus:bg-white
                               focus:ring-4 focus:ring-blue-500/10"
                    >

                </div>


                {{-- INFO --}}
                <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">

                    <div class="flex items-start gap-3">

                        <div class="mt-0.5 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">

                                <circle cx="12"
                                        cy="12"
                                        r="9"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 10v6"/>

                                <path stroke-linecap="round"
                                      stroke-linecap="round"
                                      d="M12 7h.01"/>

                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-blue-800">
                                Informasi Akun
                            </p>

                            <p class="mt-1 text-xs leading-5 text-blue-700">
                                Akun baru akan dibuat sebagai customer dengan
                                status aktif secara otomatis.
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="flex justify-end gap-2 border-t border-slate-100
                        bg-slate-50 px-6 py-4">

                <button
                    type="button"
                    @click="openCreateCustomer = false"
                    class="rounded-xl border border-slate-200 bg-white
                           px-4 py-2.5 text-sm font-medium text-slate-700
                           transition hover:bg-slate-50"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2.5
                           text-sm font-semibold text-white
                           shadow-lg shadow-blue-600/20
                           transition hover:bg-blue-700"
                >
                    Simpan Customer
                </button>

            </div>

        </form>

    </div>
</div>
