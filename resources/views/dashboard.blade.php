<x-app-layout>

    @php
        /*
        |--------------------------------------------------------------------------
        | DATA DASHBOARD
        |--------------------------------------------------------------------------
        | Hanya membaca data yang sudah ada.
        | Tidak mengubah controller, route, model, transaksi, saldo,
        | Auto Renew, Scheduler, atau logic VPN.
        |--------------------------------------------------------------------------
        */
        $customer = auth()->user();

        $vpnAccounts = $customer
            ->vpnAccounts()
            ->with('server')
            ->latest()
            ->get();

        $activeVpns = $vpnAccounts->where('status', 'active');
        $expiredVpns = $vpnAccounts->where('status', 'expired');

        $now = now();

        $expiringSoonVpns = $activeVpns
            ->filter(function ($vpn) use ($now) {
                return $vpn->expires_at
                    && $vpn->expires_at->greaterThanOrEqualTo($now)
                    && $vpn->expires_at->lessThanOrEqualTo($now->copy()->addDays(7));
            })
            ->sortBy('expires_at')
            ->values();

        $autoRenewCount = $activeVpns
            ->where('auto_renew', true)
            ->count();

        $nearestVpn = $activeVpns
            ->filter(fn ($vpn) => $vpn->expires_at)
            ->sortBy('expires_at')
            ->first();
    @endphp

    <div
        x-data="{
            openTopup: false,
            selectedPayment: '',
            paymentData: {
                @foreach ($paymentSettings as $payment)
                    '{{ $payment->name }}': {
                        type: @js($payment->type),
                        name: @js($payment->name),
                        account_number: @js($payment->account_number),
                        account_name: @js($payment->account_name),
                        qris_image: @js(
                            $payment->qris_image
                                ? asset('storage/' . $payment->qris_image)
                                : null
                        )
                    },
                @endforeach
            },
            get selectedPaymentData() {
                if (!this.selectedPayment) {
                    return null;
                }

                return this.paymentData[this.selectedPayment] ?? null;
            }
        }"
        class="space-y-6"
    >

        {{-- PAGE HEADER --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">
                    Dashboard Customer
                </p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                    Selamat datang, {{ $customer->name }} 👋
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                    Kelola VPN, saldo, dan masa aktif layanan Anda dari satu tempat.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    @click="
                        selectedPayment = '';
                        openTopup = true;
                    "
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5
                           text-sm font-semibold text-white shadow-lg shadow-blue-600/20
                           transition hover:bg-blue-700 active:scale-[0.99]"
                >
                    <span class="text-base leading-none">+</span>
                    Top Up Saldo
                </button>

                <a
                    href="{{ route('customer.vpn.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200
                           bg-white px-4 py-2.5 text-sm font-semibold text-slate-700
                           shadow-sm transition hover:bg-slate-50 active:scale-[0.99]"
                >
                    Kelola VPN
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200
                        bg-emerald-50 px-5 py-4">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center
                            rounded-xl bg-emerald-100 text-emerald-600">
                    ✓
                </div>

                <div class="min-w-0">
                    <p class="text-sm font-semibold text-emerald-800">
                        Berhasil
                    </p>

                    <p class="mt-1 text-sm text-emerald-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-3 rounded-2xl border border-red-200
                        bg-red-50 px-5 py-4">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center
                            rounded-xl bg-red-100 text-red-600">
                    !
                </div>

                <div class="min-w-0">
                    <p class="text-sm font-semibold text-red-800">
                        Terjadi kesalahan
                    </p>

                    <div class="mt-1 space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- HERO --}}
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 shadow-xl">
            <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-blue-500/20 blur-3xl"></div>
            <div class="absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl"></div>

            <div class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-[1.4fr_0.6fr] lg:items-center">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/10
                                bg-white/5 px-3 py-1.5 text-xs font-medium text-slate-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Akun customer aktif
                    </div>

                    <h2 class="mt-5 max-w-xl text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Semua layanan VPN Anda,
                        <span class="text-blue-300">lebih mudah dipantau.</span>
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                        Pantau masa aktif, Auto Renew, dan saldo tanpa perlu membuka banyak halaman.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a
                            href="{{ route('customer.vpn.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5
                                   text-sm font-semibold text-slate-900 transition hover:bg-slate-100"
                        >
                            Lihat VPN Saya
                            <span aria-hidden="true">→</span>
                        </a>

                        <a
                            href="{{ route('customer.transactions.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/15
                                   bg-white/5 px-4 py-2.5 text-sm font-semibold text-white
                                   transition hover:bg-white/10"
                        >
                            Riwayat Transaksi
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">
                        Saldo tersedia
                    </p>

                    <p class="mt-3 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Rp {{ number_format($customer->balance, 0, ',', '.') }}
                    </p>

                    <p class="mt-2 text-sm text-slate-400">
                        Siap digunakan untuk pembuatan dan perpanjangan VPN.
                    </p>

                    <button
                        type="button"
                        @click="
                            selectedPayment = '';
                            openTopup = true;
                        "
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl
                               bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-blue-500"
                    >
                        + Tambah Saldo
                    </button>
                </div>
            </div>
        </div>

        {{-- SUMMARY --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- SALDO --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition
                        hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Saldo</p>

                        <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                            Rp {{ number_format($customer->balance, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5v9a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 16.5v-9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9h18"/>
                            <path stroke-linecap="round" d="M16 14h2"/>
                        </svg>
                    </div>
                </div>

                <button
                    type="button"
                    @click="
                        selectedPayment = '';
                        openTopup = true;
                    "
                    class="mt-4 text-xs font-semibold text-blue-600 transition hover:text-blue-700"
                >
                    Tambah saldo →
                </button>
            </div>

            {{-- VPN AKTIF --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition
                        hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">VPN Aktif</p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ $activeVpns->count() }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="8"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 12l2.2 2.2 4.8-4.8"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-xs text-slate-400">
                    {{ $vpnAccounts->count() }} total akun VPN
                </p>
            </div>

            {{-- AKAN BERAKHIR --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition
                        hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Akan Berakhir</p>

                        <p class="mt-2 text-3xl font-bold tracking-tight
                                  {{ $expiringSoonVpns->count() > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                            {{ $expiringSoonVpns->count() }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl
                                {{ $expiringSoonVpns->count() > 0 ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" d="M12 7v5l3 2"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-xs {{ $expiringSoonVpns->count() > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                    Dalam 7 hari ke depan
                </p>
            </div>

            {{-- AUTO RENEW --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition
                        hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Auto Renew</p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ $autoRenewCount }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl
                                {{ $autoRenewCount > 0 ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 11a8 8 0 00-14.9-4M4 5v4h4"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 13a8 8 0 0014.9 4M20 19v-4h-4"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-xs text-slate-400">
                    VPN dengan perpanjangan otomatis aktif
                </p>
            </div>
        </div>

        {{-- PERLU PERHATIAN --}}
        @if ($expiringSoonVpns->count() > 0 || $expiredVpns->count() > 0)
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">
                            Perlu Perhatian
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Beberapa akun VPN membutuhkan perhatian Anda.
                        </p>
                    </div>

                    <a
                        href="{{ route('customer.vpn.index') }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                    >
                        Kelola VPN →
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($expiringSoonVpns->take(3) as $vpn)
                        @php
                            $days = max(0, (int) now()->diffInDays($vpn->expires_at, false));
                        @endphp

                        <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" d="M12 7v5l3 2"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-900">
                                        {{ $vpn->username }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ $vpn->server->name ?? 'Server tidak diketahui' }}
                                        · Berakhir {{ $vpn->expires_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="inline-flex w-fit items-center rounded-full bg-amber-50 px-3 py-1.5
                                        text-xs font-semibold text-amber-700">
                                {{ $days }} hari lagi
                            </div>
                        </div>
                    @endforeach

                    @if ($expiredVpns->count() > 0)
                        <div class="flex items-center justify-between gap-4 bg-red-50/60 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                                    !
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-red-800">
                                        {{ $expiredVpns->count() }} VPN expired
                                    </p>

                                    <p class="mt-0.5 text-xs text-red-700">
                                        Buka menu VPN Saya untuk melihat opsi melanjutkan layanan.
                                    </p>
                                </div>
                            </div>

                            <a
                                href="{{ route('customer.vpn.index') }}"
                                class="shrink-0 text-sm font-semibold text-red-700 hover:text-red-800"
                            >
                                Lihat
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- VPN SAYA + MASA AKTIF --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1.5fr_0.8fr]">

            {{-- DAFTAR VPN --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">
                            VPN Saya
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Ringkasan akun VPN terbaru Anda.
                        </p>
                    </div>

                    <a
                        href="{{ route('customer.vpn.index') }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                    >
                        Lihat semua →
                    </a>
                </div>

                @if ($vpnAccounts->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach ($vpnAccounts->take(4) as $vpn)
                            @php
                                $isActive = $vpn->status === 'active';
                            @endphp

                            <div class="px-5 py-4 transition hover:bg-slate-50/70">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                                    {{ $isActive ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <rect x="3" y="4" width="18" height="6" rx="1.5"/>
                                                <rect x="3" y="14" width="18" height="6" rx="1.5"/>
                                                <path stroke-linecap="round" d="M7 7h.01M7 17h.01"/>
                                            </svg>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="truncate text-sm font-semibold text-slate-900">
                                                    {{ $vpn->username }}
                                                </p>

                                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide
                                                    {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                                    {{ $isActive ? 'ACTIVE' : 'EXPIRED' }}
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ strtoupper($vpn->vpn_type) }}
                                                ·
                                                {{ $vpn->server->name ?? 'Server tidak diketahui' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-left sm:text-right">
                                        @if ($vpn->expires_at)
                                            <p class="text-xs text-slate-400">
                                                Berlaku sampai
                                            </p>

                                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                                {{ $vpn->expires_at->format('d M Y') }}
                                            </p>

                                            @if ($isActive)
                                                @php
                                                    $remainingDays = max(0, (int) now()->diffInDays($vpn->expires_at, false));
                                                @endphp

                                                <p class="mt-0.5 text-xs
                                                    {{ $remainingDays <= 7 ? 'font-semibold text-amber-600' : 'text-slate-400' }}">
                                                    {{ $remainingDays }} hari tersisa
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-sm text-slate-400">
                                                Belum tersedia
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-5 py-12 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <path stroke-linecap="round" d="M7 8h10M7 12h6"/>
                            </svg>
                        </div>

                        <h4 class="mt-4 text-sm font-semibold text-slate-900">
                            Belum ada akun VPN
                        </h4>

                        <p class="mx-auto mt-1.5 max-w-sm text-sm leading-6 text-slate-500">
                            Buat akun VPN pertama Anda untuk mulai menggunakan layanan.
                        </p>

                        <a
                            href="{{ route('customer.vpn.index') }}"
                            class="mt-4 inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5
                                   text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            Buat VPN
                        </a>
                    </div>
                @endif
            </div>

            {{-- MASA AKTIF TERDEKAT --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-5">
                    <h3 class="text-base font-semibold text-slate-900">
                        Masa Aktif Terdekat
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        VPN yang paling dekat dengan tanggal berakhir.
                    </p>
                </div>

                @if ($nearestVpn)
                    @php
                        $nearestDays = max(0, (int) now()->diffInDays($nearestVpn->expires_at, false));

                        $totalSeconds = $nearestVpn->started_at
                            ? max(1, $nearestVpn->started_at->diffInSeconds($nearestVpn->expires_at))
                            : 1;

                        $elapsedSeconds = $nearestVpn->started_at
                            ? max(0, $nearestVpn->started_at->diffInSeconds(now()))
                            : 0;

                        $nearestPercent = min(100, max(0, (int) round(
                            ($elapsedSeconds / $totalSeconds) * 100
                        )));
                    @endphp

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $nearestVpn->username }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $nearestVpn->server->name ?? 'Server tidak diketahui' }}
                                </p>
                            </div>

                            <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase
                                {{ $nearestDays <= 7 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ $nearestDays <= 7 ? 'SEGERA' : 'AKTIF' }}
                            </span>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-3xl font-bold tracking-tight text-slate-900">
                                        {{ $nearestDays }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        hari tersisa
                                    </p>
                                </div>

                                <p class="text-right text-xs text-slate-500">
                                    Sampai<br>
                                    <span class="font-semibold text-slate-700">
                                        {{ $nearestVpn->expires_at->format('d M Y H:i') }}
                                    </span>
                                </p>
                            </div>

                            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full
                                           {{ $nearestDays <= 7 ? 'bg-amber-400' : 'bg-blue-500' }}"
                                    style="width: {{ $nearestPercent }}%;"
                                ></div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3 text-xs">
                            <div class="rounded-xl bg-slate-50 px-3 py-3">
                                <p class="text-slate-400">Tipe</p>
                                <p class="mt-1 font-semibold text-slate-700">
                                    {{ strtoupper($nearestVpn->vpn_type) }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-slate-50 px-3 py-3">
                                <p class="text-slate-400">Auto Renew</p>
                                <p class="mt-1 font-semibold
                                    {{ $nearestVpn->auto_renew ? 'text-emerald-600' : 'text-slate-600' }}">
                                    {{ $nearestVpn->auto_renew ? 'Aktif' : 'Nonaktif' }}
                                </p>
                            </div>
                        </div>

                        <a
                            href="{{ route('customer.vpn.index') }}"
                            class="mt-5 inline-flex w-full items-center justify-center rounded-xl
                                   border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold
                                   text-slate-700 transition hover:bg-slate-50"
                        >
                            Kelola VPN
                        </a>
                    </div>
                @else
                    <div class="px-5 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                            —
                        </div>

                        <p class="mt-4 text-sm font-semibold text-slate-800">
                            Belum ada VPN aktif
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Buat akun VPN untuk melihat masa aktif di sini.
                        </p>

                        <a
                            href="{{ route('customer.vpn.index') }}"
                            class="mt-4 inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5
                                   text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            Buat VPN
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- AKSES CEPAT --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <h3 class="text-base font-semibold text-slate-900">
                    Akses Cepat
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Akses fitur customer yang paling sering digunakan.
                </p>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <a
                    href="{{ route('customer.vpn.index') }}"
                    class="group flex items-center justify-between rounded-xl border border-slate-200
                           bg-slate-50 px-4 py-4 transition hover:border-blue-200 hover:bg-blue-50/50"
                >
                    <div>
                        <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-700">
                            VPN Saya
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Kelola akun VPN
                        </p>
                    </div>

                    <span class="text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-blue-600">
                        →
                    </span>
                </a>

                <a
                    href="{{ route('customer.transactions.index') }}"
                    class="group flex items-center justify-between rounded-xl border border-slate-200
                           bg-slate-50 px-4 py-4 transition hover:border-blue-200 hover:bg-blue-50/50"
                >
                    <div>
                        <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-700">
                            Transaksi
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Lihat riwayat keuangan
                        </p>
                    </div>

                    <span class="text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-blue-600">
                        →
                    </span>
                </a>

                <a
                    href="{{ route('profile.edit') }}"
                    class="group flex items-center justify-between rounded-xl border border-slate-200
                           bg-slate-50 px-4 py-4 transition hover:border-blue-200 hover:bg-blue-50/50"
                >
                    <div>
                        <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-700">
                            Pengaturan Akun
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Kelola profil Anda
                        </p>
                    </div>

                    <span class="text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-blue-600">
                        →
                    </span>
                </a>
            </div>
        </div>

{{-- =========================================================

            TOP UP MODAL

        ========================================================== --}}

        <div

            x-show="openTopup"

            x-cloak

            class="fixed inset-0 z-50

                   flex items-center justify-center

                   overflow-y-auto

                   bg-slate-900/50

                   px-4 py-6"

        >

            <div

                @click.outside="openTopup = false"

                class="w-full max-w-xl

                       overflow-hidden

                       rounded-2xl

                       bg-white

                       shadow-2xl"

            >



                {{-- MODAL HEADER --}}

                <div

                    class="flex items-center justify-between

                           border-b border-slate-100

                           px-6 py-5"

                >

                    <div>

                        <h3 class="text-lg font-semibold text-slate-900">

                            Tambah Saldo

                        </h3>

                        <p class="mt-1 text-sm text-slate-500">

                            Lengkapi pembayaran untuk menambah saldo.

                        </p>

                    </div>



                    <button

                        type="button"

                        @click="openTopup = false"

                        class="rounded-lg

                               px-3 py-2

                               text-slate-400

                               transition

                               hover:bg-slate-100

                               hover:text-slate-600"

                    >

                        ✕

                    </button>

                </div>



                {{-- FORM --}}

                <form

                    method="POST"

                    action="{{ route('customer.topup.store') }}"

                    enctype="multipart/form-data"

                >

                    @csrf



                    <div class="space-y-5 px-6 py-6">



                        {{-- =================================================

                            NOMINAL

                        ================================================== --}}

                        <div>

                            <label

                                for="dashboard_amount"

                                class="block text-sm font-medium text-slate-700"

                            >

                                Nominal Top Up

                            </label>

                            <div class="relative mt-2">

                                <div

                                    class="pointer-events-none absolute inset-y-0

                                           left-0 flex items-center pl-4"

                                >

                                    <span class="text-sm font-semibold text-slate-500">

                                        Rp

                                    </span>

                                </div>



                                <input

                                    id="dashboard_amount"

                                    name="amount"

                                    type="number"

                                    min="1000"

                                    step="1000"

                                    required

                                    placeholder="10000"

                                    value="{{ old('amount') }}"

                                    class="w-full rounded-xl

                                           border border-slate-300

                                           bg-slate-50

                                           py-3 pl-11 pr-4

                                           text-sm text-slate-900

                                           outline-none

                                           transition

                                           focus:border-blue-500

                                           focus:bg-white

                                           focus:ring-4

                                           focus:ring-blue-500/10"

                                />

                            </div>

                            <p class="mt-1 text-xs text-slate-400">

                                Minimal top up Rp1.000

                            </p>

                        </div>



                        {{-- =================================================

                            METODE PEMBAYARAN

                        ================================================== --}}

                        <div>

                            <label

                                for="dashboard_payment_method"

                                class="block text-sm font-medium text-slate-700"

                            >

                                Metode Pembayaran

                            </label>

                            <select

                                id="dashboard_payment_method"

                                name="payment_method"

                                x-model="selectedPayment"

                                required

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

                            >

                                <option value="">

                                    Pilih metode pembayaran

                                </option>

                                @foreach ($paymentSettings as $payment)

                                    <option value="{{ $payment->name }}">

                                        {{ $payment->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        {{-- =================================================

                            PAYMENT DETAIL

                        ================================================== --}}

                        <div

                            x-show="selectedPaymentData"

                            x-transition

                            x-cloak

                        >

                            {{-- BANK / EWALLET --}}

                            <template

                                x-if="

                                    selectedPaymentData &&

                                    selectedPaymentData.type !== 'qris'

                                "

                            >

                                <div

                                    class="rounded-xl

                                           border border-slate-200

                                           bg-slate-50

                                           p-5"

                                >

                                    <div class="flex items-center gap-3">

                                        <div

                                            class="flex h-10 w-10

                                                   items-center justify-center

                                                   rounded-xl

                                                   bg-blue-50

                                                   text-blue-600"

                                        >

                                            <svg

                                                xmlns="http://www.w3.org/2000/svg"

                                                class="h-5 w-5"

                                                fill="none"

                                                viewBox="0 0 24 24"

                                                stroke="currentColor"

                                                stroke-width="1.8"

                                            >

                                                <path

                                                    stroke-linecap="round"

                                                    stroke-linejoin="round"

                                                    d="M3 10h18M5 10v8m4-8v8m6-8v8m4-8v8M3 18h18M4 10l8-5 8 5"

                                                />

                                            </svg>

                                        </div>



                                        <div>

                                            <p

                                                class="text-sm

                                                       font-semibold

                                                       text-slate-900"

                                                x-text="selectedPaymentData.name"

                                            ></p>

                                            <p class="mt-1 text-xs text-slate-400">

                                                Informasi pembayaran

                                            </p>

                                        </div>

                                    </div>



                                    <div class="mt-5">

                                        <p class="text-xs text-slate-400">

                                            Nomor Rekening / Tujuan

                                        </p>

                                        <div

                                            class="mt-1 flex items-center

                                                   justify-between gap-3"

                                        >

                                            <p

                                                class="break-all

                                                       font-mono

                                                       text-lg

                                                       font-bold

                                                       text-slate-900"

                                                x-text="selectedPaymentData.account_number || '-'"

                                            ></p>

                                        </div>

                                    </div>



                                    <div class="mt-4">

                                        <p class="text-xs text-slate-400">

                                            Atas Nama

                                        </p>

                                        <p

                                            class="mt-1 text-sm

                                                   font-semibold

                                                   text-slate-800"

                                            x-text="selectedPaymentData.account_name || '-'"

                                        ></p>

                                    </div>

                                </div>

                            </template>



                            {{-- QRIS --}}

                            <template

                                x-if="

                                    selectedPaymentData &&

                                    selectedPaymentData.type === 'qris'

                                "

                            >

                                <div

                                    class="rounded-xl

                                           border border-purple-200

                                           bg-purple-50/40

                                           p-5"

                                >

                                    <div class="flex items-center gap-3">

                                        <div

                                            class="flex h-10 w-10

                                                   items-center justify-center

                                                   rounded-xl

                                                   bg-purple-100

                                                   text-purple-600"

                                        >

                                            <span class="text-xs font-bold">

                                                QR

                                            </span>

                                        </div>



                                        <div>

                                            <p

                                                class="text-sm

                                                       font-semibold

                                                       text-slate-900"

                                                x-text="selectedPaymentData.name"

                                            ></p>

                                            <p class="mt-1 text-xs text-slate-400">

                                                Pembayaran QRIS

                                            </p>

                                        </div>

                                    </div>



                                    <div

                                        class="mt-5 flex min-h-[220px]

                                               items-center justify-center

                                               rounded-xl

                                               border border-purple-100

                                               bg-white

                                               p-4"

                                    >

                                        <template

                                            x-if="selectedPaymentData.qris_image"

                                        >

                                            <img

                                                :src="selectedPaymentData.qris_image"

                                                :alt="'QRIS ' + selectedPaymentData.name"

                                                class="max-h-56 w-auto

                                                       rounded-lg

                                                       object-contain"

                                            >

                                        </template>



                                        <template

                                            x-if="!selectedPaymentData.qris_image"

                                        >

                                            <p class="text-sm text-slate-400">

                                                Gambar QRIS belum tersedia.

                                            </p>

                                        </template>

                                    </div>



                                    <template

                                        x-if="selectedPaymentData.account_name"

                                    >

                                        <p class="mt-3 text-xs text-slate-500">

                                            Atas Nama:

                                            <span

                                                class="font-semibold text-slate-700"

                                                x-text="selectedPaymentData.account_name"

                                            ></span>

                                        </p>

                                    </template>

                                </div>

                            </template>

                        </div>



                        {{-- =================================================

                            BUKTI PEMBAYARAN

                        ================================================== --}}

                        <div>

                            <label

                                for="dashboard_proof"

                                class="block text-sm font-medium text-slate-700"

                            >

                                Bukti Pembayaran

                            </label>

                            <input

                                id="dashboard_proof"

                                name="proof"

                                type="file"

                                accept=".jpg,.jpeg,.png,.pdf"

                                required

                                class="mt-2 w-full rounded-xl

                                       border border-slate-300

                                       bg-slate-50

                                       p-2

                                       text-sm text-slate-500"

                            >

                            <p class="mt-1 text-xs text-slate-400">

                                JPG, JPEG, PNG atau PDF — maksimal 5 MB.

                            </p>

                        </div>

                    </div>



                    {{-- MODAL FOOTER --}}

                    <div

                        class="flex justify-end gap-2

                               border-t border-slate-100

                               bg-slate-50

                               px-6 py-4"

                    >

                        <button

                            type="button"

                            @click="openTopup = false"

                            class="rounded-xl

                                   border border-slate-200

                                   bg-white

                                   px-4 py-2.5

                                   text-sm font-medium

                                   text-slate-700

                                   transition hover:bg-slate-50"

                        >

                            Batal

                        </button>



                        <button

                            type="submit"

                            @disabled($paymentSettings->count() === 0)

                            class="rounded-xl

                                   bg-blue-600

                                   px-5 py-2.5

                                   text-sm font-semibold

                                   text-white

                                   shadow-lg

                                   shadow-blue-600/20

                                   transition hover:bg-blue-700

                                   disabled:cursor-not-allowed

                                   disabled:opacity-50"

                        >

                            Kirim Pengajuan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
