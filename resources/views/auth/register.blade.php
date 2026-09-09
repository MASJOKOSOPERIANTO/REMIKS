<x-guest-layout>

    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-10">

        <div class="w-full max-w-md">

            {{-- LOGO --}}
            <div class="mb-8 text-center">

                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center
                           rounded-2xl bg-blue-600
                           shadow-lg shadow-blue-600/20"
                >
                    {{-- VPN / NETWORK ICON --}}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8.5 12a3.5 3.5 0 117 0v1.5a3.5 3.5 0 11-7 0V12z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 12a6 6 0 0112 0"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 12a8 8 0 0116 0"
                        />

                        <circle
                            cx="12"
                            cy="12"
                            r="1"
                            fill="currentColor"
                            stroke="none"
                        />
                    </svg>
                </div>

                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">
                    VPN PANEL
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Management System
                </p>

            </div>


            {{-- REGISTER CARD --}}
            <div
                class="overflow-hidden rounded-2xl
                       border border-slate-200
                       bg-white
                       shadow-xl shadow-slate-200/60"
            >

                <div class="px-6 py-7 sm:px-8">


                    {{-- HEADER --}}
                    <div class="mb-7">

                        <h2 class="text-xl font-semibold text-slate-900">
                            Buat akun baru
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Daftar untuk mulai menggunakan VPN Panel.
                        </p>

                    </div>


                    {{-- ERROR UMUM --}}
                    @if ($errors->any())

                        <div
                            class="mb-5 rounded-xl border border-red-200
                                   bg-red-50 px-4 py-3"
                        >

                            <div class="text-sm font-semibold text-red-700">
                                Pendaftaran gagal
                            </div>

                            <ul class="mt-1 list-disc pl-5 text-xs text-red-600">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- REGISTER FORM --}}
                    <form method="POST" action="{{ route('register') }}">

                        @csrf


                        {{-- NAME --}}
                        <div>

                            <label
                                for="name"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Nama
                            </label>

                            <div class="relative mt-2">

                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0
                                           flex items-center pl-3.5 text-slate-400"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 19a6 6 0 00-12 0"
                                        />

                                        <circle
                                            cx="9"
                                            cy="7"
                                            r="4"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M17 11a4 4 0 014 4v4"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Nama lengkap"
                                    class="block w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           py-3 pl-11 pr-4
                                           text-sm text-slate-900
                                           placeholder:text-slate-400
                                           outline-none
                                           transition
                                           focus:border-blue-500
                                           focus:bg-white
                                           focus:ring-4
                                           focus:ring-blue-500/10"
                                />

                            </div>

                            @error('name')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- EMAIL --}}
                        <div class="mt-5">

                            <label
                                for="email"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Email
                            </label>

                            <div class="relative mt-2">

                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0
                                           flex items-center pl-3.5 text-slate-400"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 8l9 6 9-6"
                                        />

                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="14"
                                            rx="2"
                                            ry="2"
                                        />
                                    </svg>

                                </div>


                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="username"
                                    placeholder="nama@email.com"
                                    class="block w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           py-3 pl-11 pr-4
                                           text-sm text-slate-900
                                           placeholder:text-slate-400
                                           outline-none
                                           transition
                                           focus:border-blue-500
                                           focus:bg-white
                                           focus:ring-4
                                           focus:ring-blue-500/10"
                                />

                            </div>

                            @error('email')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- PASSWORD --}}
                        <div class="mt-5">

                            <label
                                for="password"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Password
                            </label>

                            <div class="relative mt-2">

                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0
                                           flex items-center pl-3.5 text-slate-400"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <rect
                                            x="5"
                                            y="10"
                                            width="14"
                                            height="10"
                                            rx="2"
                                            ry="2"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M8 10V7a4 4 0 018 0v3"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Minimal 8 karakter"
                                    class="block w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           py-3 pl-11 pr-4
                                           text-sm text-slate-900
                                           placeholder:text-slate-400
                                           outline-none
                                           transition
                                           focus:border-blue-500
                                           focus:bg-white
                                           focus:ring-4
                                           focus:ring-blue-500/10"
                                />

                            </div>

                            @error('password')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- CONFIRM PASSWORD --}}
                        <div class="mt-5">

                            <label
                                for="password_confirmation"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Konfirmasi Password
                            </label>

                            <div class="relative mt-2">

                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0
                                           flex items-center pl-3.5 text-slate-400"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <rect
                                            x="5"
                                            y="10"
                                            width="14"
                                            height="10"
                                            rx="2"
                                            ry="2"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M8 10V7a4 4 0 018 0v3"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 15l2 2 4-4"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Ulangi password"
                                    class="block w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           py-3 pl-11 pr-4
                                           text-sm text-slate-900
                                           placeholder:text-slate-400
                                           outline-none
                                           transition
                                           focus:border-blue-500
                                           focus:bg-white
                                           focus:ring-4
                                           focus:ring-blue-500/10"
                                />

                            </div>

                            @error('password_confirmation')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- REGISTER BUTTON --}}
                        <button
                            type="submit"
                            class="mt-6 flex w-full items-center justify-center
                                   rounded-xl
                                   bg-blue-600
                                   px-4 py-3
                                   text-sm font-semibold text-white
                                   shadow-lg shadow-blue-600/20
                                   transition
                                   hover:bg-blue-700
                                   focus:outline-none
                                   focus:ring-4
                                   focus:ring-blue-500/20
                                   active:scale-[0.99]"
                        >
                            Buat Akun
                        </button>

                    </form>

                </div>


                {{-- LOGIN LINK --}}
                <div
                    class="border-t border-slate-100
                           bg-slate-50
                           px-6 py-5
                           text-center sm:px-8"
                >

                    <p class="text-sm text-slate-500">

                        Sudah memiliki akun?

                        <a
                            href="{{ route('login') }}"
                            class="font-semibold text-blue-600
                                   transition hover:text-blue-700"
                        >
                            Masuk sekarang
                        </a>

                    </p>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="mt-6 text-center">

                <p class="text-xs text-slate-400">
                    © {{ date('Y') }} VPN Panel
                </p>

            </div>

        </div>

    </div>

</x-guest-layout>
