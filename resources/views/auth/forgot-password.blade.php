<x-guest-layout>
    <div class="w-full max-w-md mx-auto">

        <!-- Card -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">

            <!-- Header -->
            <div class="px-8 pt-8 pb-6 text-center">

                <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-full bg-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-7 h-7 text-blue-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 8l9 6 9-6m-18 8h18V8l-9 6-9-6v8z" />
                    </svg>
                </div>

                <h1 class="mt-4 text-2xl font-bold text-gray-900">
                    Lupa Password?
                </h1>

                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Masukkan alamat email akun Anda.
                    Kami akan mengirimkan tautan untuk mengatur ulang password.
                </p>
            </div>

            <!-- Body -->
            <div class="px-8 pb-8">

                <!-- Status -->
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')"
                />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label
                            for="email"
                            value="Alamat Email"
                        />

                        <x-text-input
                            id="email"
                            class="block mt-2 w-full rounded-xl"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            placeholder="contoh@email.com"
                        />

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2"
                        />
                    </div>

                    <!-- Submit -->
                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center items-center
                                   px-4 py-3
                                   bg-blue-600
                                   border border-transparent
                                   rounded-xl
                                   font-semibold
                                   text-sm text-white
                                   hover:bg-blue-700
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-blue-500
                                   transition"
                        >
                            Kirim Link Reset Password
                        </button>
                    </div>

                    <!-- Back Login -->
                    <div class="mt-5 text-center">
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-500 hover:text-blue-600 transition">
                            ← Kembali ke Login
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-guest-layout>
