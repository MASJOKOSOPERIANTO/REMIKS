<x-app-layout>
    @php
        $customer = auth()->user();
        $vpnAccounts = $customer->vpnAccounts()
            ->with('server')
            ->latest()
            ->get();

        $activeVpns = $vpnAccounts->where('status', 'active');
        $expiredVpns = $vpnAccounts->where('status', 'expired');
        $autoRenewVpns = $activeVpns->where('auto_renew', true);

        $now = now();
        $expiringSoon = $activeVpns->filter(function ($vpn) use ($now) {
            if (!$vpn->expires_at) {
                return false;
            }

            return $vpn->expires_at->greaterThanOrEqualTo($now)
                && $vpn->expires_at->lessThanOrEqualTo($now->copy()->addDays(7));
        });

        $nearestVpn = $activeVpns
            ->filter(fn ($vpn) => $vpn->expires_at)
            ->sortBy('expires_at')
            ->first();

        $latestVpns = $vpnAccounts->take(5);
    @endphp

    <div
        x-data="{
            openTopup: false,
            selectedPayment: '',
            paymentData: {
                @foreach ($paymentSettings as $payment)
                    @js($payment->name): {
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
                return this.selectedPayment
                    ? (this.paymentData[this.selectedPayment] ?? null)
                    : null;
            }
        }"
        class="space-y-6"
    >
        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">Customer Area</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                    Dashboard
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Selamat datang, {{ $customer->name }}. Kelola VPN dan saldo Anda dari sini.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route('customer.transactions.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 17.25V6.75z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9h9M7.5 12h6M7.5 15h4" />
                    </svg>
                    Transaksi
                </a>

                <a
                    href="{{ route('customer.vpn.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h9.5A2.25 2.25 0 0120 9v6a2.25 2.25 0 01-2.25 2.25h-9.5A2.25 2.25 0 016 15V9a2.25 2.25 0 012.25-2.25z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75v4.5A2.25 2.25 0 006 16.5h1.5M20.25 9.75v4.5A2.25 2.25 0 0118 16.5h-1.5" />
                    </svg>
                    Kelola VPN
                </a>
            </div>
        </div>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-emerald-800">Berhasil</p>
                        <p class="mt-1 text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
                <p class="text-sm font-semibold text-red-800">Terjadi kesalahan</p>
                <div class="mt-2 space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- TOP SUMMARY --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {{-- SALDO --}}
            <div class="overflow-hidden rounded-2xl bg-blue-600 p-5 shadow-lg shadow-blue-600/20">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Saldo Saat Ini</p>
                        <p class="mt-3 text-2xl font-bold tracking-tight text-white">
                            Rp {{ number_format($customer->balance, 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-xs text-blue-100/90">Digunakan untuk pembuatan dan renewal VPN.</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="8" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m2.5-5.5c0-.83-1.12-1.5-2.5-1.5s-2.5.67-2.5 1.5 1.12 1.5 2.5 1.5 2.5.67 2.5 1.5-1.12 1.5-2.5 1.5-2.5-.67-2.5-1.5" />
                        </svg>
                    </div>
                </div>
                <button
                    type="button"
                    @click="selectedPayment = ''; openTopup = true"
                    class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-sm transition hover:bg-blue-50"
                >
                    <span class="text-lg leading-none">+</span>
                    Tambah Saldo
                </button>
            </div>

            {{-- VPN AKTIF --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">VPN Aktif</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $activeVpns->count() }}</p>
                        <p class="mt-2 text-xs text-slate-400">Akun yang saat ini dapat digunakan.</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="4" width="18" height="6" rx="1.5" />
                            <rect x="3" y="14" width="18" height="6" rx="1.5" />
                            <path stroke-linecap="round" d="M7 7h.01M7 17h.01M11 7h6M11 17h6" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- SEGERA EXPIRED --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Segera Berakhir</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight {{ $expiringSoon->count() > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $expiringSoon->count() }}</p>
                        <p class="mt-2 text-xs text-slate-400">Masa aktif berakhir dalam 7 hari.</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $expiringSoon->count() > 0 ? 'bg-amber-50 text-amber-600' : 'bg-slate-50 text-slate-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5" />
                            <circle cx="12" cy="12" r="8" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- AUTO RENEW --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Auto Renew</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $autoRenewVpns->count() }}</p>
                        <p class="mt-2 text-xs text-slate-400">VPN aktif dengan perpanjangan otomatis.</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 01-13.7 5.6L4 15.5M4 12a8 8 0 0113.7-5.6L20 8.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 4v4.5h-4.5M4 20v-4.5h4.5" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFO GRID --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
            {{-- VPN TERDEKAT EXPIRED --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Perlu Perhatian</p>
                        <h2 class="mt-1 text-lg font-semibold text-slate-900">Masa Aktif Terdekat</h2>
                        <p class="mt-1 text-sm text-slate-500">VPN aktif yang paling dekat dengan tanggal berakhir.</p>
                    </div>
                    <a href="{{ route('customer.vpn.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Lihat semua →
                    </a>
                </div>

                @if ($nearestVpn)
                    @php
                        $daysRemaining = max(0, now()->startOfDay()->diffInDays($nearestVpn->expires_at->copy()->startOfDay(), false));
                        $hoursRemaining = max(0, now()->diffInHours($nearestVpn->expires_at, false));
                    @endphp

                    <div class="mt-5 rounded-2xl border {{ $nearestVpn->expires_at->lte(now()->copy()->addDays(7)) ? 'border-amber-200 bg-amber-50' : 'border-slate-200 bg-slate-50' }} p-5">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center rounded-lg bg-white px-2.5 py-1 font-mono text-sm font-bold text-slate-900 shadow-sm">
                                        {{ $nearestVpn->username }}
                                    </span>
                                    <span class="inline-flex items-center rounded-full {{ $nearestVpn->auto_renew ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }} px-2.5 py-1 text-[11px] font-semibold">
                                        {{ $nearestVpn->auto_renew ? 'Auto Renew ON' : 'Auto Renew OFF' }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">
                                    {{ $nearestVpn->server?->name ?? 'Server tidak tersedia' }} · {{ strtoupper($nearestVpn->vpn_type) }}
                                </p>
                            </div>

                            <div class="md:text-right">
                                <p class="text-xs text-slate-500">Berakhir</p>
                                <p class="mt-1 text-base font-bold text-slate-900">
                                    {{ $nearestVpn->expires_at->format('d M Y, H:i') }}
                                </p>
                                <p class="mt-1 text-xs font-medium {{ $nearestVpn->expires_at->lte(now()->copy()->addDays(7)) ? 'text-amber-700' : 'text-slate-500' }}">
                                    @if ($daysRemaining === 0)
                                        {{ $hoursRemaining }} jam lagi
                                    @else
                                        {{ $daysRemaining }} hari lagi
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-5 rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5" />
                                <circle cx="12" cy="12" r="8" />
                            </svg>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada VPN aktif</p>
                        <p class="mt-1 text-xs text-slate-400">Buat akun VPN untuk mulai menggunakan layanan.</p>
                    </div>
                @endif
            </div>

            {{-- QUICK ACTION --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Akses Cepat</p>
                <h2 class="mt-1 text-lg font-semibold text-slate-900">Menu Customer</h2>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('customer.vpn.index') }}" class="group flex items-center gap-3 rounded-xl border border-slate-200 p-3.5 transition hover:border-blue-200 hover:bg-blue-50/60">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="4" width="18" height="6" rx="1.5" />
                                <rect x="3" y="14" width="18" height="6" rx="1.5" />
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold text-slate-800">VPN Saya</span>
                            <span class="mt-0.5 block text-xs text-slate-500">Kelola akun, server, dan masa aktif</span>
                        </span>
                        <span class="text-slate-400 group-hover:text-blue-600">→</span>
                    </a>

                    <a href="{{ route('customer.transactions.index') }}" class="group flex items-center gap-3 rounded-xl border border-slate-200 p-3.5 transition hover:border-blue-200 hover:bg-blue-50/60">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 group-hover:bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5h12A1.5 1.5 0 0119.5 6v12a1.5 1.5 0 01-1.5 1.5H6A1.5 1.5 0 014.5 18V6A1.5 1.5 0 016 4.5z" />
                                <path stroke-linecap="round" d="M8 9h8M8 12h8M8 15h5" />
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold text-slate-800">Transaksi</span>
                            <span class="mt-0.5 block text-xs text-slate-500">Top up dan riwayat pemakaian saldo</span>
                        </span>
                        <span class="text-slate-400 group-hover:text-blue-600">→</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 rounded-xl border border-slate-200 p-3.5 transition hover:border-blue-200 hover:bg-blue-50/60">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 group-hover:bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 20.25a6.75 6.75 0 0113.5 0" />
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold text-slate-800">Pengaturan Akun</span>
                            <span class="mt-0.5 block text-xs text-slate-500">Perbarui profil dan keamanan akun</span>
                        </span>
                        <span class="text-slate-400 group-hover:text-blue-600">→</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- VPN TERBARU --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Monitoring</p>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">VPN Terbaru</h2>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan akun VPN terakhir yang Anda buat.</p>
                </div>
                <a href="{{ route('customer.vpn.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Kelola VPN →</a>
            </div>

            @if ($latestVpns->count())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px]">
                        <thead class="border-b border-slate-200 bg-slate-50/70">
                            <tr>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">VPN</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Server</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Type</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Expired</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($latestVpns as $vpn)
                                <tr class="transition hover:bg-slate-50/70">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm font-semibold text-slate-900">{{ $vpn->username }}</span>
                                        @if ($vpn->auto_renew)
                                            <span class="ml-2 inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">AUTO</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $vpn->server?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-medium uppercase text-slate-600">{{ $vpn->vpn_type }}</td>
                                    <td class="px-6 py-4">
                                        @if ($vpn->status === 'active')
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                        @elseif ($vpn->status === 'expired')
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">Expired</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ ucfirst($vpn->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $vpn->expires_at ? $vpn->expires_at->format('d M Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="4" width="18" height="16" rx="2" />
                            <path stroke-linecap="round" d="M7 8h10M7 12h10M7 16h6" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-800">Belum ada akun VPN</h3>
                    <p class="mt-1 text-sm text-slate-500">Buat VPN pertama Anda untuk mulai menggunakan layanan.</p>
                    <a href="{{ route('customer.vpn.index') }}" class="mt-4 inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Buat VPN
                    </a>
                </div>
            @endif
        </div>

        {{-- TOP UP MODAL --}}
        <div
            x-show="openTopup"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/50 px-4 py-6"
        >
            <div
                x-show="openTopup"
                x-transition
                @click.outside="openTopup = false"
                class="w-full max-w-xl overflow-hidden rounded-2xl bg-white shadow-2xl"
            >
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Saldo</h3>
                        <p class="mt-1 text-sm text-slate-500">Lengkapi pembayaran untuk menambah saldo.</p>
                    </div>
                    <button
                        type="button"
                        @click="openTopup = false"
                        class="rounded-lg px-3 py-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                    >
                        ✕
                    </button>
                </div>

                <form method="POST" action="{{ route('customer.topup.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-5 px-6 py-6">
                        {{-- NOMINAL --}}
                        <div>
                            <label for="dashboard_amount" class="block text-sm font-medium text-slate-700">Nominal Top Up</label>
                            <div class="relative mt-2">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="text-sm font-semibold text-slate-500">Rp</span>
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
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                                >
                            </div>
                            <p class="mt-1 text-xs text-slate-400">Minimal top up Rp1.000</p>
                        </div>

                        {{-- METODE PEMBAYARAN --}}
                        <div>
                            <label for="dashboard_payment_method" class="block text-sm font-medium text-slate-700">Metode Pembayaran</label>
                            <select
                                id="dashboard_payment_method"
                                name="payment_method"
                                x-model="selectedPayment"
                                required
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >
                                <option value="">Pilih metode pembayaran</option>
                                @foreach ($paymentSettings as $payment)
                                    <option value="{{ $payment->name }}">{{ $payment->name }} - {{ strtoupper($payment->type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DETAIL PEMBAYARAN --}}
                        <div x-show="selectedPaymentData" x-transition x-cloak>
                            {{-- BANK / EWALLET --}}
                            <template x-if="selectedPaymentData && selectedPaymentData.type !== 'qris'">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 10v8m4-8v8m6-8v8m4-8v8M3 18h18M4 10l8-5 8 5" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900" x-text="selectedPaymentData.name"></p>
                                            <p class="mt-1 text-xs text-slate-400">Informasi pembayaran</p>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <p class="text-xs text-slate-400">Nomor Rekening / Tujuan</p>
                                        <p class="mt-1 break-all font-mono text-lg font-bold text-slate-900" x-text="selectedPaymentData.account_number || '-' "></p>
                                    </div>

                                    <div class="mt-4">
                                        <p class="text-xs text-slate-400">Atas Nama</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800" x-text="selectedPaymentData.account_name || '-' "></p>
                                    </div>
                                </div>
                            </template>

                            {{-- QRIS --}}
                            <template x-if="selectedPaymentData && selectedPaymentData.type === 'qris'">
                                <div class="rounded-xl border border-purple-200 bg-purple-50/40 p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                                            <span class="text-xs font-bold">QR</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900" x-text="selectedPaymentData.name"></p>
                                            <p class="mt-1 text-xs text-slate-400">Pembayaran QRIS</p>
                                        </div>
                                    </div>

                                    <div class="mt-5 flex min-h-[220px] items-center justify-center rounded-xl border border-purple-100 bg-white p-4">
                                        <template x-if="selectedPaymentData.qris_image">
                                            <img
                                                :src="selectedPaymentData.qris_image"
                                                :alt="'QRIS ' + selectedPaymentData.name"
                                                class="max-h-56 w-auto rounded-lg object-contain"
                                            >
                                        </template>
                                        <template x-if="!selectedPaymentData.qris_image">
                                            <p class="text-sm text-slate-400">Gambar QRIS belum tersedia.</p>
                                        </template>
                                    </div>

                                    <template x-if="selectedPaymentData.account_name">
                                        <p class="mt-3 text-xs text-slate-500">
                                            Atas Nama:
                                            <span class="font-semibold text-slate-700" x-text="selectedPaymentData.account_name"></span>
                                        </p>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- BUKTI PEMBAYARAN --}}
                        <div>
                            <label for="dashboard_proof" class="block text-sm font-medium text-slate-700">Bukti Pembayaran</label>
                            <input
                                id="dashboard_proof"
                                name="proof"
                                type="file"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 p-2 text-sm text-slate-500"
                            >
                            <p class="mt-1 text-xs text-slate-400">JPG, JPEG, PNG atau PDF — maksimal 5 MB.</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-slate-100 bg-slate-50 px-6 py-4">
                        <button
                            type="button"
                            @click="openTopup = false"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            @disabled($paymentSettings->count() === 0)
                            class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
