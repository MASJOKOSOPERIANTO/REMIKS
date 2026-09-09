<x-app-layout>

    <div
        x-data="{
            openCreate: false,
            openEdit: false,
            openScript: false,

            editVpn: {
                id: null,
                server: '',
                vpn_type: '',
                username: '',
                password: '',
                to_port: '',
                dst_port: ''
            },

            mikrotikScript: ''
        }"
        class="space-y-6"
    >

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 7h10M5 11h14M8 15h8M10 19h4"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 3v18"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                            VPN Saya
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Kelola akun VPN, masa aktif, koneksi remote, dan auto perpanjangan Anda.
                        </p>
                    </div>
                </div>
            </div>

            <button
                type="button"
                @click="openCreate = true"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5
                       text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition
                       hover:bg-blue-700 active:scale-[0.99]"
            >
                <span class="text-lg leading-none">+</span>
                Buat VPN
            </button>
        </div>

        {{-- =========================================================
            STATISTIK
        ========================================================== --}}
        @php
            $vpnAktif = $vpnAccounts->where('status', 'active')->count();
            $vpnExpired = $vpnAccounts->where('status', 'expired')->count();
            $vpnAutoRenew = $vpnAccounts->where('auto_renew', true)->count();

            $vpnSegeraExpired = $vpnAccounts
                ->filter(function ($vpn) {
                    return $vpn->status === 'active'
                        && $vpn->expires_at
                        && $vpn->expires_at->isFuture()
                        && $vpn->expires_at->lte(now()->copy()->addDays(7));
                })
                ->count();

            $vpnTerdekat = $vpnAccounts
                ->filter(function ($vpn) {
                    return $vpn->status === 'active'
                        && $vpn->expires_at
                        && $vpn->expires_at->isFuture();
                })
                ->sortBy('expires_at')
                ->first();
        @endphp

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- AKTIF --}}
            <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">VPN Aktif</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $vpnAktif }}</p>
                        <p class="mt-1 text-xs text-slate-400">Akun sedang dapat digunakan</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="8"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 12l2.2 2.2 4.8-4.8"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- SEGERA EXPIRED --}}
            <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Segera Expired</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $vpnSegeraExpired }}</p>
                        <p class="mt-1 text-xs text-slate-400">Jatuh tempo dalam 7 hari</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4l2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- AUTO RENEW --}}
            <div class="rounded-2xl border border-blue-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Auto Renew</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $vpnAutoRenew }}</p>
                        <p class="mt-1 text-xs text-slate-400">Akun dengan perpanjangan otomatis</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 12a8 8 0 00-13.4-5.9L5 8.7M4 4v4.7h4.7"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 12a8 8 0 0013.4 5.9L19 15.3M20 20v-4.7h-4.7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- TOTAL / EXPIRED --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total VPN</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $vpnAccounts->count() }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ $vpnExpired }} akun expired</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <path stroke-linecap="round" d="M7 8h10M7 12h10M7 16h6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================
            NOTICE VPN TERDEKAT EXPIRED
        ========================================================== --}}
        @if($vpnTerdekat)
            @php
                $daysLeft = now()->startOfDay()->diffInDays($vpnTerdekat->expires_at->copy()->startOfDay());
            @endphp

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                                Masa Aktif Terdekat
                            </p>
                            <p class="mt-1 text-base font-semibold text-slate-900">
                                {{ $vpnTerdekat->username }}
                            </p>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $vpnTerdekat->server->name ?? '-' }}
                                · Expired {{ $vpnTerdekat->expires_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-slate-50 px-4 py-2.5 text-center">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Sisa</p>
                            <p class="mt-0.5 text-lg font-bold text-slate-900">
                                {{ $daysLeft }} <span class="text-sm font-medium text-slate-400">hari</span>
                            </p>
                        </div>

                        <a
                            href="{{ route('customer.vpn.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white
                                   px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Kelola VPN
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- =========================================================
            DAFTAR VPN
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Daftar Akun VPN</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Kelola akun VPN dan akses remote Anda dari satu tempat.
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 px-3 py-2 text-xs font-medium text-slate-500">
                    {{ $vpnAccounts->count() }} akun terdaftar
                </div>
            </div>

            @if($vpnAccounts->count() > 0)

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1240px] text-left text-xs">
                        <thead class="border-b border-slate-200 bg-slate-50">
                            <tr>
                                <th class="w-12 px-4 py-3 text-center font-semibold text-slate-500">No</th>
                                <th class="px-4 py-3 font-semibold text-slate-500">Server</th>
                                <th class="px-4 py-3 font-semibold text-slate-500">Tipe</th>
                                <th class="px-4 py-3 font-semibold text-slate-500">Username</th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-500">Port</th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-500">Status</th>
                                <th class="px-4 py-3 font-semibold text-slate-500">Dibuat</th>
                                <th class="px-4 py-3 font-semibold text-slate-500">Expired</th>
                                <th class="px-4 py-3 font-semibold text-slate-500">IP Remote</th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-500">Auto Renew</th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach($vpnAccounts as $index => $vpn)

                                @php
                                    $ipBadgeColors = [
                                        'bg-blue-50 text-blue-700 border-blue-200',
                                        'bg-purple-50 text-purple-700 border-purple-200',
                                        'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'bg-orange-50 text-orange-700 border-orange-200',
                                        'bg-pink-50 text-pink-700 border-pink-200',
                                        'bg-cyan-50 text-cyan-700 border-cyan-200',
                                    ];

                                    $ipBadgeColor = $ipBadgeColors[$index % count($ipBadgeColors)];

                                    if ($vpn->vpn_type === 'sstp') {
                                        $interfaceCommand = '/interface sstp-client add';
                                    } else {
                                        $interfaceCommand = '/interface l2tp-client add';
                                    }

                                    $mikrotikScript =
                                        $interfaceCommand . " \\\n" .
                                        "name=" . $vpn->username . " \\\n" .
                                        "connect-to=" . ($vpn->server->host ?? '') . " \\\n" .
                                        "user=" . $vpn->username . " \\\n" .
                                        "password=" . $vpn->password . " \\\n" .
                                        "profile=default-encryption \\\n" .
                                        "disabled=no";
                                @endphp

                                <tr class="transition hover:bg-slate-50/80">

                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100
                                                     text-xs font-semibold text-slate-600">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-slate-800">
                                            {{ $vpn->server->name ?? '-' }}
                                        </div>
                                        <div class="mt-1 text-[11px] text-slate-400">
                                            {{ $vpn->server->host ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($vpn->vpn_type === 'sstp')
                                            <span class="inline-flex rounded-full bg-purple-50 px-2.5 py-1 text-[11px] font-semibold text-purple-700">
                                                SSTP
                                            </span>
                                        @elseif($vpn->vpn_type === 'l2tp')
                                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                                L2TP
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                                {{ strtoupper($vpn->vpn_type) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="font-mono text-sm font-semibold text-slate-800">
                                            {{ $vpn->username }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 font-mono text-[11px] font-semibold text-slate-700">
                                            {{ $vpn->to_port ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @if($vpn->status === 'active')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1
                                                         text-[11px] font-semibold text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @elseif($vpn->status === 'expired')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1
                                                         text-[11px] font-semibold text-red-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                Expired
                                            </span>
                                        @elseif($vpn->status === 'suspended')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1
                                                         text-[11px] font-semibold text-amber-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Suspended
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                                {{ ucfirst($vpn->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        @if($vpn->started_at)
                                            <div class="font-medium text-slate-700">
                                                {{ $vpn->started_at->format('d/m/Y') }}
                                            </div>
                                            <div class="mt-0.5 text-[11px] text-slate-400">
                                                {{ $vpn->started_at->format('H:i') }}
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        @if($vpn->expires_at)
                                            <div class="font-medium {{ $vpn->expires_at->isPast() ? 'text-red-600' : 'text-slate-700' }}">
                                                {{ $vpn->expires_at->format('d/m/Y') }}
                                            </div>
                                            <div class="mt-0.5 text-[11px] {{ $vpn->expires_at->isPast() ? 'text-red-400' : 'text-slate-400' }}">
                                                {{ $vpn->expires_at->format('H:i') }}
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($vpn->server && $vpn->dst_port)
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center rounded-lg border px-2.5 py-1.5 font-mono text-[11px]
                                                             font-semibold shadow-sm {{ $ipBadgeColor }}">
                                                    {{ $vpn->server->host }}:{{ $vpn->dst_port }}
                                                </span>

                                                <button
                                                    type="button"
                                                    onclick="copyToClipboard('{{ $vpn->server->host }}:{{ $vpn->dst_port }}')"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200
                                                           bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-700"
                                                    title="Salin IP Remote"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <rect x="9" y="9" width="10" height="10" rx="2"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <form
                                            method="POST"
                                            action="{{ route('customer.vpn.auto-renew', $vpn->id) }}"
                                            class="auto-renew-form inline-block"
                                            data-enabled="{{ $vpn->auto_renew ? '1' : '0' }}"
                                        >
                                            @csrf

                                            <button
                                                type="button"
                                                onclick="confirmAutoRenew(this)"
                                                class="{{ $vpn->auto_renew
                                                    ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'
                                                    : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}
                                                       rounded-lg px-2.5 py-1.5 text-[11px] font-semibold transition"
                                                title="{{ $vpn->auto_renew ? 'Auto Perpanjangan Aktif' : 'Auto Perpanjangan Nonaktif' }}"
                                            >
                                                {{ $vpn->auto_renew ? '🟢 ON' : '⚪ OFF' }}
                                            </button>
                                        </form>
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap items-center justify-center gap-1.5">

                                            @if($vpn->status === 'expired')
                                                <form
                                                    method="POST"
                                                    action="{{ route('customer.vpn.resume', $vpn->id) }}"
                                                    class="resume-vpn-form"
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="rounded-lg bg-emerald-50 px-2.5 py-1.5 text-[11px] font-semibold
                                                               text-emerald-700 transition hover:bg-emerald-100"
                                                        title="Lanjutkan Berlangganan"
                                                    >
                                                        Lanjutkan
                                                    </button>
                                                </form>
                                            @endif

                                            <button
                                                type="button"
                                                @click="editVpn = {
                                                    id: {{ $vpn->id }},
                                                    server: @js($vpn->server->name ?? '-'),
                                                    vpn_type: @js($vpn->vpn_type),
                                                    username: @js($vpn->username),
                                                    password: @js($vpn->password),
                                                    to_port: @js($vpn->to_port),
                                                    dst_port: @js($vpn->dst_port)
                                                }; openEdit = true;"
                                                class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-[11px] font-semibold text-blue-600
                                                       transition hover:bg-blue-100"
                                            >
                                                Edit
                                            </button>

                                            <button
                                                type="button"
                                                @click="mikrotikScript = @js($mikrotikScript); openScript = true;"
                                                class="rounded-lg bg-slate-50 px-2.5 py-1.5 text-[11px] font-semibold text-slate-700
                                                       transition hover:bg-slate-100"
                                            >
                                                Script
                                            </button>

                                            <form
                                                method="POST"
                                                action="{{ route('customer.vpn.destroy', $vpn->id) }}"
                                                class="delete-vpn-form"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-50 px-2.5 py-1.5 text-[11px] font-semibold text-red-600
                                                           transition hover:bg-red-100"
                                                >
                                                    Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else

                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path stroke-linecap="round" d="M8 9h8M8 13h5"/>
                        </svg>
                    </div>

                    <h2 class="mt-5 text-lg font-semibold text-slate-900">
                        Belum Ada Akun VPN
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        Anda belum memiliki akun VPN. Buat akun VPN baru untuk mulai menggunakan layanan.
                    </p>

                    <button
                        type="button"
                        @click="openCreate = true"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5
                               text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
                    >
                        <span class="text-lg leading-none">+</span>
                        Buat VPN
                    </button>
                </div>

            @endif
        </div>

        {{-- =========================================================
            MODAL
        ========================================================== --}}
        @include('customer.vpn.modals.create')
        @include('customer.vpn.modals.edit')
        @include('customer.vpn.modals.script')

    </div>
</x-app-layout>

{{-- =============================================================
    SWEETALERT AUTO RENEW
============================================================= --}}
<script>
    function confirmAutoRenew(button) {
        const form = button.closest('.auto-renew-form');

        if (!form) {
            return;
        }

        const enabled = form.dataset.enabled === '1';

        Swal.fire({
            title: enabled
                ? 'Nonaktifkan Auto Perpanjangan?'
                : 'Aktifkan Auto Perpanjangan?',
            text: enabled
                ? 'VPN ini tidak akan diperpanjang otomatis lagi.'
                : 'Saat jatuh tempo, sistem akan mencoba memperpanjang VPN dengan biaya Rp2.000 jika saldo mencukupi.',
            icon: enabled ? 'warning' : 'info',
            showCancelButton: true,
            confirmButtonText: enabled ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>

{{-- =============================================================
    SWEETALERT RESUME
============================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.resume-vpn-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Lanjutkan Berlangganan?',
                    html: `
                        <div style="text-align:left; line-height:1.6;">
                            <p style="margin-bottom:10px;">
                                VPN Anda akan diaktifkan kembali selama
                                <strong>1 bulan</strong>.
                            </p>

                            <div style="
                                background:#f8fafc;
                                border:1px solid #e2e8f0;
                                border-radius:10px;
                                padding:12px 14px;
                                margin-bottom:10px;
                            ">
                                <div style="display:flex;justify-content:space-between;gap:12px;">
                                    <span style="color:#64748b;">Biaya</span>
                                    <strong style="color:#0f172a;">Rp2.000</strong>
                                </div>

                                <div style="display:flex;justify-content:space-between;gap:12px;margin-top:5px;">
                                    <span style="color:#64748b;">Masa aktif</span>
                                    <strong style="color:#0f172a;">1 bulan</strong>
                                </div>
                            </div>

                            <p style="font-size:13px;color:#64748b;margin:0;">
                                Saldo akan dipotong otomatis setelah Anda mengonfirmasi.
                            </p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

{{-- =============================================================
    SWEETALERT DELETE
============================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-vpn-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Hapus Akun VPN?',
                    text: 'PPP Secret, NAT, dan data VPN di database akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

{{-- =============================================================
    SWEETALERT SALDO KURANG
============================================================= --}}
@if($errors->has('vpn'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Saldo Tidak Mencukupi',
                text: @js($errors->first('vpn')),
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#2563eb',
            });
        });
    </script>
@endif

{{-- =============================================================
    COPY IP REMOTE
============================================================= --}}
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.createElement('div');

            toast.innerText = '✅ Tersalin: ' + text;

            toast.className =
                'fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 2000);
        });
    }
</script>
