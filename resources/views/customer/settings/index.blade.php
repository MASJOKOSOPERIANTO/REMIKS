<x-app-layout>

    <div class="space-y-6">

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Pengaturan Akun
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Kelola informasi profil dan keamanan akun Anda.
            </p>
        </div>


        {{-- =========================================================
            SUCCESS
        ========================================================== --}}
        @if (session('success'))

            <div
                class="rounded-2xl border border-emerald-200
                       bg-emerald-50 px-5 py-4"
            >

                <div class="flex items-start gap-3">

                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center
                               rounded-xl bg-emerald-100
                               text-emerald-600 font-bold"
                    >
                        ✓
                    </div>

                    <div>

                        <p class="text-sm font-semibold text-emerald-800">
                            Berhasil
                        </p>

                        <p class="mt-1 text-sm text-emerald-700">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================
            ERROR
        ========================================================== --}}
        @if ($errors->any())

            <div
                class="rounded-2xl border border-red-200
                       bg-red-50 px-5 py-4"
            >

                <div class="flex items-start gap-3">

                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center
                               rounded-xl bg-red-100
                               text-red-600 font-bold"
                    >
                        !
                    </div>

                    <div>

                        <p class="text-sm font-semibold text-red-800">
                            Terjadi kesalahan
                        </p>

                        <div class="mt-2 space-y-1 text-sm text-red-700">

                            @foreach ($errors->all() as $error)

                                <p>
                                    {{ $error }}
                                </p>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================
            INFORMASI PROFIL
        ========================================================== --}}
        <div
            class="overflow-hidden rounded-2xl
                   border border-slate-200
                   bg-white shadow-sm"
        >

            {{-- HEADER --}}
            <div
                class="border-b border-slate-100
                       px-6 py-5"
            >

                <h2 class="text-lg font-semibold text-slate-900">
                    Informasi Profil
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi dasar akun customer Anda.
                </p>

            </div>


            {{-- FORM --}}
            <form
                method="POST"
                action="{{ route('customer.settings.update') }}"
            >

                @csrf
                @method('PATCH')


                <div class="p-6">

                    <div
                        class="flex flex-col gap-6
                               sm:flex-row sm:items-center"
                    >

                        {{-- =================================================
                            FOTO / AVATAR
                        ================================================== --}}
                        <div class="shrink-0">

                            <div
                                class="flex h-24 w-24 items-center justify-center
                                       overflow-hidden rounded-full
                                       bg-blue-50
                                       text-3xl
                                       font-bold
                                       text-blue-600"
                            >

                                {{ strtoupper(substr($user->name, 0, 1)) }}

                            </div>

                            <p class="mt-2 text-center text-xs text-slate-400">
                                Foto profil
                            </p>

                        </div>


                        {{-- =================================================
                            DATA USER
                        ================================================== --}}
                        <div class="grid flex-1 grid-cols-1 gap-5 md:grid-cols-2">

                            {{-- NAMA --}}
                            <div>

                                <label
                                    for="name"
                                    class="block text-sm font-medium text-slate-700"
                                >
                                    Nama
                                </label>

                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    autocomplete="name"
                                    class="mt-2 w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           px-4 py-3
                                           text-sm text-slate-900
                                           outline-none
                                           transition
                                           focus:border-blue-500
                                           focus:bg-white
                                           focus:ring-4
                                           focus:ring-blue-500/10"
                                    placeholder="Masukkan nama"
                                >

                            </div>


                            {{-- EMAIL --}}
                            <div>

                                <label
                                    for="email"
                                    class="block text-sm font-medium text-slate-700"
                                >
                                    Email
                                </label>

                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    autocomplete="email"
                                    class="mt-2 w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           px-4 py-3
                                           text-sm text-slate-900
                                           outline-none
                                           transition
                                           focus:border-blue-500
                                           focus:bg-white
                                           focus:ring-4
                                           focus:ring-blue-500/10"
                                    placeholder="Masukkan email"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- ERROR NAMA --}}
                    @error('name')
                        <p class="mt-3 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror


                    {{-- ERROR EMAIL --}}
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror


                    {{-- BUTTON --}}
                    <div class="mt-6 flex justify-end">

                        <button
                            type="submit"
                            class="inline-flex items-center
                                   rounded-xl
                                   bg-blue-600
                                   px-5 py-2.5
                                   text-sm font-semibold
                                   text-white
                                   shadow-lg
                                   shadow-blue-600/20
                                   transition
                                   hover:bg-blue-700"
                        >
                            Simpan Informasi
                        </button>

                    </div>

                </div>

            </form>

        </div>


        {{-- =========================================================
            KEAMANAN AKUN
        ========================================================== --}}
        <div
            class="overflow-hidden rounded-2xl
                   border border-slate-200
                   bg-white shadow-sm"
        >

            {{-- HEADER --}}
            <div
                class="border-b border-slate-100
                       px-6 py-5"
            >

                <h2 class="text-lg font-semibold text-slate-900">
                    Keamanan Akun
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Ubah password akun Anda secara berkala untuk menjaga keamanan.
                </p>

            </div>


            {{-- FORM PASSWORD --}}
            <form
                method="POST"
                action="{{ route('customer.settings.password') }}"
            >

                @csrf
                @method('PATCH')


                <div class="p-6">

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- =================================================
                            PASSWORD SAAT INI
                        ================================================== --}}
                        <div class="md:col-span-2">

                            <label
                                for="current_password"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Password Saat Ini
                            </label>

                            <input
                                id="current_password"
                                name="current_password"
                                type="password"
                                autocomplete="current-password"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3
                                       text-sm text-slate-900
                                       outline-none
                                       transition
                                       focus:border-blue-500
                                       focus:bg-white
                                       focus:ring-4
                                       focus:ring-blue-500/10"
                                placeholder="Masukkan password saat ini"
                            >

                            @error('current_password')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- =================================================
                            PASSWORD BARU
                        ================================================== --}}
                        <div>

                            <label
                                for="password"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Password Baru
                            </label>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3
                                       text-sm text-slate-900
                                       outline-none
                                       transition
                                       focus:border-blue-500
                                       focus:bg-white
                                       focus:ring-4
                                       focus:ring-blue-500/10"
                                placeholder="Masukkan password baru"
                            >

                            @error('password')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            <p class="mt-1 text-xs text-slate-400">
                                Minimal 8 karakter.
                            </p>

                        </div>


                        {{-- =================================================
                            KONFIRMASI PASSWORD
                        ================================================== --}}
                        <div>

                            <label
                                for="password_confirmation"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Konfirmasi Password Baru
                            </label>

                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3
                                       text-sm text-slate-900
                                       outline-none
                                       transition
                                       focus:border-blue-500
                                       focus:bg-white
                                       focus:ring-4
                                       focus:ring-blue-500/10"
                                placeholder="Ulangi password baru"
                            >

                            @error('password_confirmation')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- BUTTON --}}
                    <div class="mt-6 flex justify-end">

                        <button
                            type="submit"
                            class="inline-flex items-center
                                   rounded-xl
                                   bg-blue-600
                                   px-5 py-2.5
                                   text-sm font-semibold
                                   text-white
                                   shadow-lg
                                   shadow-blue-600/20
                                   transition
                                   hover:bg-blue-700"
                        >
                            Simpan Password
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
