<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">
                VPN Accounts
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Menampilkan seluruh akun VPN yang dibuat oleh customer.
            </p>
        </div>
    </x-slot>


    {{-- ROOT ALPINE --}}
    <div
        x-data="{
            searchVpn: ''
        }"
        class="space-y-6"
    >

        {{-- =========================================================
            SUMMARY
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- TOTAL VPN --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Total VPN
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ $vpnAccounts->count() }}
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-blue-50 text-blue-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.5 12.5l1.5 1.5 3.5-3.5"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            {{-- VPN AKTIF --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            VPN Aktif
                        </p>

                        <p class="mt-3 text-3xl font-bold text-emerald-600">
                            {{ $vpnAccounts->where('status', 'active')->count() }}
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-emerald-50 text-emerald-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 12l2.5 2.5L16 9"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            {{-- VPN EXPIRED --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            VPN Expired
                        </p>

                        <p class="mt-3 text-3xl font-bold text-red-600">

                            {{
                                $vpnAccounts
                                    ->filter(function ($vpn) {
                                        return $vpn->expires_at &&
                                            $vpn->expires_at->isPast();
                                    })
                                    ->count()
                            }}

                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-red-50 text-red-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 7v5l3 2"
                            />
                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            VPN TABLE
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- HEADER --}}
            <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5
                        lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h3 class="text-lg font-semibold text-slate-800">
                        Seluruh VPN Accounts
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Data akun VPN customer yang tersimpan dalam sistem.
                    </p>

                </div>


                {{-- SEARCH --}}
                <div class="relative w-full lg:w-80">

                    {{-- SEARCH ICON --}}
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0
                               flex items-center pl-3.5"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-slate-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"
                            />

                        </svg>

                    </div>


                    {{-- SEARCH INPUT --}}
                    <input
                        type="text"
                        x-model="searchVpn"
                        placeholder="Cari customer, username, server..."
                        class="w-full rounded-xl border border-slate-300
                               bg-slate-50 py-2.5 pl-11 pr-10
                               text-sm text-slate-900
                               outline-none transition
                               placeholder:text-slate-400
                               focus:border-blue-500
                               focus:bg-white
                               focus:ring-4
                               focus:ring-blue-500/10"
                    >


                    {{-- CLEAR --}}
                    <button
                        type="button"
                        x-show="searchVpn.length > 0"
                        x-cloak
                        @click="searchVpn = ''"
                        class="absolute inset-y-0 right-0
                               flex items-center pr-3
                               text-slate-400 transition
                               hover:text-slate-600"
                    >
                        ✕
                    </button>

                </div>

            </div>


            @if($vpnAccounts->count() > 0)

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="border-b border-slate-200 bg-slate-50">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Customer
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Server
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Type
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Username
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    VPN IP
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    IP Remote
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Port
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Dibuat
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Expired
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($vpnAccounts as $vpn)

                                @php
                                    $customerName = $vpn->user?->name ?? '';
                                    $customerEmail = $vpn->user?->email ?? '';
                                    $serverName = $vpn->server?->name ?? '';
                                    $serverHost = $vpn->server?->host ?? '';
                                    $vpnType = $vpn->vpn_type ?? '';
                                    $username = $vpn->username ?? '';
                                    $vpnIp = $vpn->vpn_ip ?? '';
                                    $dstPort = $vpn->dst_port ?? '';
                                    $toPort = $vpn->to_port ?? '';
                                @endphp

                                <tr
                                    x-show="
                                        searchVpn === '' ||
                                        @js(strtolower($customerName)).includes(searchVpn.toLowerCase()) ||
                                        @js(strtolower($customerEmail)).includes(searchVpn.toLowerCase()) ||
                                        @js(strtolower($serverName)).includes(searchVpn.toLowerCase()) ||
                                        @js(strtolower($serverHost)).includes(searchVpn.toLowerCase()) ||
                                        @js(strtolower($vpnType)).includes(searchVpn.toLowerCase()) ||
                                        @js(strtolower($username)).includes(searchVpn.toLowerCase()) ||
                                        @js(strtolower($vpnIp)).includes(searchVpn.toLowerCase()) ||
                                        @js(strtolower((string) $dstPort)).includes(searchVpn.toLowerCase()) ||
                                        @js(strtolower((string) $toPort)).includes(searchVpn.toLowerCase())
                                    "
                                    class="transition hover:bg-slate-50"
                                >

                                    {{-- NO --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- CUSTOMER --}}
                                    <td class="px-6 py-4">

                                        @if($vpn->user)

                                            <div class="flex items-center gap-3">

                                                <div
                                                    class="flex h-9 w-9 shrink-0 items-center justify-center
                                                           rounded-full bg-blue-100
                                                           text-sm font-bold text-blue-700"
                                                >
                                                    {{ strtoupper(substr($vpn->user->name, 0, 1)) }}
                                                </div>

                                                <div class="min-w-0">

                                                    <p class="truncate text-sm font-semibold text-slate-800">
                                                        {{ $vpn->user->name }}
                                                    </p>

                                                    <p class="text-xs text-slate-400">
                                                        {{ $vpn->user->email }}
                                                    </p>

                                                </div>

                                            </div>

                                        @else

                                            <span class="text-sm text-slate-400">
                                                Customer tidak ditemukan
                                            </span>

                                        @endif

                                    </td>


                                    {{-- SERVER --}}
                                    <td class="px-6 py-4">

                                        @if($vpn->server)

                                            <div>

                                                <p class="text-sm font-semibold text-slate-800">
                                                    {{ $vpn->server->name }}
                                                </p>

                                                <p class="mt-1 text-xs text-slate-400">
                                                    {{ $vpn->server->host }}
                                                </p>

                                            </div>

                                        @else

                                            <span class="text-sm text-red-500">
                                                Server tidak ditemukan
                                            </span>

                                        @endif

                                    </td>


                                    {{-- TYPE --}}
                                    <td class="px-6 py-4">

                                        @if($vpn->vpn_type === 'sstp')

                                            <span
                                                class="inline-flex items-center rounded-full
                                                       bg-blue-50 px-3 py-1.5
                                                       text-xs font-semibold text-blue-700"
                                            >
                                                SSTP
                                            </span>

                                        @elseif($vpn->vpn_type === 'l2tp')

                                            <span
                                                class="inline-flex items-center rounded-full
                                                       bg-violet-50 px-3 py-1.5
                                                       text-xs font-semibold text-violet-700"
                                            >
                                                L2TP
                                            </span>

                                        @else

                                            <span
                                                class="inline-flex items-center rounded-full
                                                       bg-slate-100 px-3 py-1.5
                                                       text-xs font-semibold text-slate-700"
                                            >
                                                {{ strtoupper($vpn->vpn_type) }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- USERNAME --}}
                                    <td class="px-6 py-4">

                                        <span
                                            class="font-mono text-sm font-semibold text-slate-800"
                                        >
                                            {{ $vpn->username }}
                                        </span>

                                    </td>


                                    {{-- VPN IP --}}
                                    <td class="px-6 py-4">

                                        <span
                                            class="font-mono text-sm text-slate-600"
                                        >
                                            {{ $vpn->vpn_ip ?: '-' }}
                                        </span>

                                    </td>


                                    {{-- IP REMOTE --}}
                                    <td class="px-6 py-4">

                                        @if($vpn->server && $vpn->dst_port)

                                            <span
                                                class="inline-flex items-center rounded-lg
                                                       bg-slate-100 px-3 py-2
                                                       font-mono text-xs font-semibold
                                                       text-slate-700"
                                            >
                                                {{ $vpn->server->host }}:{{ $vpn->dst_port }}
                                            </span>

                                        @else

                                            <span class="text-sm text-slate-400">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- PORT --}}
                                    <td class="px-6 py-4">

                                        <span
                                            class="inline-flex items-center rounded-lg
                                                   bg-blue-50 px-3 py-2
                                                   font-mono text-xs font-semibold
                                                   text-blue-700"
                                        >
                                            {{ $vpn->to_port }}
                                        </span>

                                    </td>


                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">

                                        @if($vpn->expires_at && $vpn->expires_at->isPast())

                                            <span
                                                class="inline-flex items-center gap-2
                                                       rounded-full bg-red-50 px-3 py-1.5
                                                       text-xs font-semibold text-red-700"
                                            >

                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                                Expired

                                            </span>

                                        @elseif($vpn->status === 'active')

                                            <span
                                                class="inline-flex items-center gap-2
                                                       rounded-full bg-emerald-50 px-3 py-1.5
                                                       text-xs font-semibold text-emerald-700"
                                            >

                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                                Aktif

                                            </span>

                                        @else

                                            <span
                                                class="inline-flex items-center gap-2
                                                       rounded-full bg-slate-100 px-3 py-1.5
                                                       text-xs font-semibold text-slate-700"
                                            >

                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>

                                                {{ ucfirst($vpn->status) }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- DIBUAT --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                        @if($vpn->started_at)

                                            {{ $vpn->started_at->format('d/m/Y') }}

                                            <div class="mt-1 text-xs text-slate-400">
                                                {{ $vpn->started_at->format('H:i') }}
                                            </div>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- EXPIRED --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        @if($vpn->expires_at)

                                            @if($vpn->expires_at->isPast())

                                                <span class="text-sm font-semibold text-red-600">
                                                    {{ $vpn->expires_at->format('d/m/Y') }}
                                                </span>

                                                <div class="mt-1 text-xs text-red-400">
                                                    Sudah expired
                                                </div>

                                            @else

                                                <span class="text-sm font-semibold text-slate-700">
                                                    {{ $vpn->expires_at->format('d/m/Y') }}
                                                </span>

                                                <div class="mt-1 text-xs text-slate-400">
                                                    {{ $vpn->expires_at->format('H:i') }}
                                                </div>

                                            @endif

                                        @else

                                            <span class="text-sm text-slate-400">
                                                -
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach


                            {{-- SEARCH EMPTY --}}
                            <tr
                                x-show="
                                    searchVpn !== '' &&
                                    Array.from($el.parentElement.querySelectorAll('tr')).every(row => {
                                        if (row === $el) return true;
                                        return row.style.display === 'none';
                                    })
                                "
                                x-cloak
                            >

                                <td
                                    colspan="11"
                                    class="px-6 py-12 text-center"
                                >

                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center
                                               rounded-2xl bg-slate-100"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-7 w-7 text-slate-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"
                                            />

                                        </svg>

                                    </div>

                                    <p class="mt-4 text-sm font-semibold text-slate-700">
                                        Data tidak ditemukan
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Coba gunakan kata pencarian yang lain.
                                    </p>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="px-6 py-16 text-center">

                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center
                               rounded-2xl bg-slate-100"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8 text-slate-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.5 12.5l1.5 1.5 3.5-3.5"
                            />

                        </svg>

                    </div>


                    <h3 class="mt-5 text-lg font-semibold text-slate-800">
                        Belum Ada VPN Account
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Belum ada akun VPN yang dibuat oleh customer.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
