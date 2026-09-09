<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-xl font-semibold text-slate-900">
                Dashboard Admin
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Selamat datang kembali, {{ auth()->user()->name }}.
            </p>
        </div>

    </x-slot>


    <div class="space-y-6">

        {{-- HEADER --}}
        <div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Dashboard Admin
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Kelola sistem VPN Panel dari sini.
            </p>

        </div>


        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">

            {{-- CUSTOMERS --}}
            <div
                class="rounded-2xl border border-slate-200
                       bg-white p-6 shadow-sm"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Customers
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ \App\Models\User::where('role', 'customer')->count() }}
                        </p>

                    </div>

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-xl bg-blue-50
                               text-blue-600"
                    >
                        👥
                    </div>

                </div>

            </div>


            {{-- SERVERS --}}
            <div
                class="rounded-2xl border border-slate-200
                       bg-white p-6 shadow-sm"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Servers
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ \App\Models\Server::where('status', true)->count() }}
                        </p>

                    </div>

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-xl bg-purple-50
                               text-purple-600"
                    >
                        🖥
                    </div>

                </div>

            </div>


            {{-- VPN AKTIF --}}
            <div
                class="rounded-2xl border border-slate-200
                       bg-white p-6 shadow-sm"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            VPN Aktif
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ \App\Models\VpnAccount::where('status', 'active')->count() }}
                        </p>

                    </div>

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-xl bg-emerald-50
                               text-emerald-600"
                    >
                        🔐
                    </div>

                </div>

            </div>


            {{-- TOP UP PENDING --}}
            <div
                class="rounded-2xl border border-amber-200
                       bg-amber-50 p-6 shadow-sm"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-amber-700">
                            Top Up Pending
                        </p>

                        <p class="mt-3 text-3xl font-bold text-amber-800">
                            {{ \App\Models\Transaction::where('type', 'topup')->where('status', 'pending')->count() }}
                        </p>

                    </div>

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-xl bg-amber-100
                               text-amber-600"
                    >
                        ⏳
                    </div>

                </div>

            </div>

        </div>


        {{-- QUICK ACTION --}}
        <div
            class="rounded-2xl border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <h2 class="text-lg font-semibold text-slate-900">
                Aksi Cepat
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Akses pengelolaan utama VPN Panel.
            </p>


            <div class="mt-5 flex flex-wrap gap-3">

                <a
                    href="{{ route('admin.servers.index') }}"
                    class="rounded-xl
                           bg-blue-600
                           px-4 py-2.5
                           text-sm font-semibold
                           text-white
                           shadow-lg shadow-blue-600/20
                           transition hover:bg-blue-700"
                >
                    Kelola Server
                </a>


                <a
                    href="{{ route('admin.transactions.index') }}"
                    class="rounded-xl
                           border border-slate-200
                           bg-white
                           px-4 py-2.5
                           text-sm font-semibold
                           text-slate-700
                           transition hover:bg-slate-50"
                >
                    Transactions
                </a>


                <a
                    href="{{ route('admin.payment-settings.index') }}"
                    class="rounded-xl
                           border border-slate-200
                           bg-white
                           px-4 py-2.5
                           text-sm font-semibold
                           text-slate-700
                           transition hover:bg-slate-50"
                >
                    Payment Settings
                </a>

            </div>

        </div>


        {{-- SYSTEM INFO --}}
        <div
            class="rounded-2xl border border-blue-100
                   bg-blue-50 p-6"
        >

            <div class="flex items-start gap-3">

                <div
                    class="flex h-10 w-10 shrink-0
                           items-center justify-center
                           rounded-xl bg-blue-100
                           text-blue-600"
                >
                    ✓
                </div>

                <div>

                    <h2 class="text-base font-semibold text-blue-900">
                        Sistem VPN Panel
                    </h2>

                    <p class="mt-1 text-sm text-blue-700">
                        Dashboard admin aktif. Gunakan menu sebelah kiri
                        untuk mengelola customer, server, VPN, transaksi,
                        dan metode pembayaran.
                    </p>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
