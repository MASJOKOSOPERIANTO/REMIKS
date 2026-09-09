<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VPN Panel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900">

    <div class="min-h-screen flex">

        {{-- =========================================================
            SIDEBAR
        ========================================================== --}}
        <aside
            class="w-64 h-screen sticky top-0 shrink-0
                   bg-white border-r border-slate-200
                   shadow-sm flex flex-col"
        >

            {{-- =====================================================
                BRAND
            ====================================================== --}}
            <div class="px-6 py-6 border-b border-slate-200">

                <div class="flex items-center gap-3">

                    {{-- BRAND ICON --}}
                    <div
                        class="w-10 h-10 rounded-xl
                               bg-blue-600
                               flex items-center justify-center
                               shadow-sm"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8.25 8.25l-1.5 1.5m0 0l1.5 1.5m-1.5-1.5h9m-9 0l-1.5-1.5m1.5 1.5l-1.5 1.5M15.75 15.75l1.5-1.5m0 0l-1.5-1.5m1.5 1.5h-9m9 0l1.5 1.5m-1.5-1.5l-1.5-1.5M12 3v3m0 12v3M3 12h3m12 0h3"
                            />
                        </svg>

                    </div>


                    {{-- BRAND TEXT --}}
                    <div>

                        <div class="text-lg font-bold tracking-tight text-slate-800">
                            VPN PANEL
                        </div>

                        <div class="text-xs text-slate-400 mt-0.5">
                            Management System
                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                NAVIGATION
            ====================================================== --}}
            <nav class="flex-1 px-4 py-6">

                {{-- =================================================
                    DASHBOARD
                ================================================== --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition
                    {{ request()->routeIs('dashboard')
                        ? 'bg-blue-50 text-blue-700 font-semibold'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"
                        />
                    </svg>

                    <span>Dashboard</span>

                </a>


                {{-- =================================================
                    ADMIN MENU
                ================================================== --}}
                @if(auth()->user()->role === 'admin')

                    {{-- SECTION TITLE --}}
                    <div class="pt-6 pb-3 px-4">

                        <div
                            class="text-[11px] font-bold text-slate-400
                                   uppercase tracking-wider"
                        >
                            Management
                        </div>

                    </div>


                    {{-- =================================================
                        CUSTOMERS
                    ================================================== --}}
                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition
                        {{ request()->routeIs('admin.customers.*')
                            ? 'bg-blue-50 text-blue-700 font-semibold'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 11-6 0 3 3 0 016 0z"
                            />

                        </svg>

                        <span>Customers</span>

                    </a>


                    {{-- =================================================
                        SERVERS
                    ================================================== --}}
                    <a
                        href="{{ route('admin.servers.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition
                        {{ request()->routeIs('admin.servers.*')
                            ? 'bg-blue-50 text-blue-700 font-semibold'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <rect
                                x="4"
                                y="4"
                                width="16"
                                height="6"
                                rx="1"
                            />

                            <rect
                                x="4"
                                y="14"
                                width="16"
                                height="6"
                                rx="1"
                            />

                            <path
                                stroke-linecap="round"
                                d="M8 7h.01M8 17h.01"
                            />

                            <path
                                stroke-linecap="round"
                                d="M12 7h5M12 17h5"
                            />

                        </svg>

                        <span>Servers</span>

                    </a>


                   {{-- VPN ACCOUNTS --}}
                                <a
                                    href="{{ route('admin.vpn-accounts.index') }}"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition
                                    {{ request()->routeIs('admin.vpn-accounts.*')
                                        ? 'bg-blue-50 text-blue-700 font-semibold'
                                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
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

                                    <span>VPN Accounts</span>

                                </a>


                    {{-- =================================================
                        TRANSACTIONS DROPDOWN
                    ================================================== --}}
                    <div
                        x-data="{
                            openTransactionMenu: {{ request()->routeIs('admin.transactions.*') || request()->routeIs('admin.payment-settings.*') ? 'true' : 'false' }}
                        }"
                        class="mb-2"
                    >

                        {{-- TRANSACTIONS PARENT --}}
                        <button
                            type="button"
                            @click="openTransactionMenu = !openTransactionMenu"
                            class="w-full flex items-center justify-between gap-3
                                   px-4 py-3 rounded-xl transition
                                   {{ request()->routeIs('admin.transactions.*') || request()->routeIs('admin.payment-settings.*')
                                        ? 'bg-blue-50 text-blue-700 font-semibold'
                                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                        >

                            <span class="flex items-center gap-3">

                                {{-- ICON --}}
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <rect
                                        x="4"
                                        y="4"
                                        width="16"
                                        height="16"
                                        rx="2"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8 8h8M8 12h8M8 16h5"
                                    />

                                </svg>

                                <span>Transactions</span>

                            </span>


                            {{-- CHEVRON --}}
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 transition-transform duration-200"
                                :class="{ 'rotate-180': openTransactionMenu }"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19 9l-7 7-7-7"
                                />

                            </svg>

                        </button>


                        {{-- SUB MENU --}}
                        <div
                            x-show="openTransactionMenu"
                            x-transition
                            x-cloak
                            class="mt-1 ml-4 space-y-1
                                   border-l border-slate-200
                                   pl-4"
                        >

                            {{-- DETAIL TRANSAKSI --}}
                            <a
                                href="{{ route('admin.transactions.index') }}"
                                class="flex items-center gap-3
                                       px-4 py-2.5 rounded-lg
                                       text-sm transition
                                       {{ request()->routeIs('admin.transactions.*')
                                            ? 'bg-blue-50 text-blue-700 font-semibold'
                                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 5h6M9 9h6M9 13h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"
                                    />

                                </svg>

                                <span>Detail Transaksi</span>

                            </a>


                            {{-- PAYMENT SETTINGS --}}
                            <a
                                href="{{ route('admin.payment-settings.index') }}"
                                class="flex items-center gap-3
                                       px-4 py-2.5 rounded-lg
                                       text-sm transition
                                       {{ request()->routeIs('admin.payment-settings.*')
                                            ? 'bg-blue-50 text-blue-700 font-semibold'
                                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="3"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.4 15a1.7 1.7 0 00.34 1.88l.06.06-1.72 1.72-.06-.06A1.7 1.7 0 0016.14 18a1.7 1.7 0 00-1.07 1.58V20h-2.42v-.42A1.7 1.7 0 0011.58 18a1.7 1.7 0 00-1.88.34l-.06.06-1.72-1.72.06-.06A1.7 1.7 0 008.02 15a1.7 1.7 0 00-1.58-1.07H6v-2.42h.42A1.7 1.7 0 008 10.44a1.7 1.7 0 00-.34-1.88L7.6 8.5l1.72-1.72.06.06a1.7 1.7 0 001.88.34A1.7 1.7 0 0012.33 5.6V5h2.42v.42A1.7 1.7 0 0016.33 7.1a1.7 1.7 0 001.88-.34l-.06-.06L20 8.42l-.06.06a1.7 1.7 0 00-.34 1.88A1.7 1.7 0 0019.58 12H20v2.42h-.42A1.7 1.7 0 0019.4 15z"
                                    />

                                </svg>

                                <span>Payment Settings</span>

                            </a>

                        </div>

                    </div>

                @endif


                {{-- =================================================
                    CUSTOMER MENU
                ================================================== --}}
                @if(auth()->user()->role === 'customer')

                    {{-- SECTION VPN --}}
                    <div class="pt-6 pb-3 px-4">

                        <div
                            class="text-[11px] font-bold text-slate-400
                                   uppercase tracking-wider"
                        >
                            VPN
                        </div>

                    </div>


                    {{-- =================================================
                        VPN SAYA
                    ================================================== --}}
                    <a
                        href="{{ route('customer.vpn.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition
                        {{ request()->routeIs('customer.vpn.*')
                            ? 'bg-blue-50 text-blue-700 font-semibold'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
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

                        <span>VPN Saya</span>

                    </a>


                    {{-- SECTION KEUANGAN --}}
                    <div class="pt-6 pb-3 px-4">

                        <div
                            class="text-[11px] font-bold text-slate-400
                                   uppercase tracking-wider"
                        >
                            Keuangan
                        </div>

                    </div>


                    {{-- =================================================
                        CUSTOMER TRANSACTIONS
                    ================================================== --}}
                    <a
                        href="{{ route('customer.transactions.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition
                        {{ request()->routeIs('customer.transactions.*')
                            ? 'bg-blue-50 text-blue-700 font-semibold'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <rect
                                x="5"
                                y="4"
                                width="14"
                                height="16"
                                rx="2"
                            />

                            <path
                                stroke-linecap="round"
                                d="M8 8h8M8 12h8M8 16h5"
                            />

                        </svg>

                        <span>Transaksi</span>

                    </a>


                    {{-- SECTION PENGATURAN --}}
                    <div class="pt-6 pb-3 px-4">

                        <div
                            class="text-[11px] font-bold text-slate-400
                                   uppercase tracking-wider"
                        >
                            Pengaturan
                        </div>

                    </div>


                    {{-- =================================================
                        PENGATURAN AKUN
                    ================================================== --}}
                    <a
                        href="{{ route('customer.settings') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2
                               text-slate-600
                               hover:bg-slate-50 hover:text-slate-900
                               transition"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <circle
                                cx="12"
                                cy="8"
                                r="4"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 21a8 8 0 0116 0"
                            />

                        </svg>

                        <span>Setting Account</span>

                    </a>

                @endif

            </nav>


            {{-- =====================================================
                USER AREA
            ====================================================== --}}
            <div class="border-t border-slate-200 p-4 bg-slate-50">

                <div class="flex items-center gap-3 mb-4">

                    {{-- AVATAR --}}
                    <div
                        class="w-10 h-10 rounded-full
                               bg-blue-100
                               flex items-center justify-center
                               text-blue-700
                               font-bold
                               border border-blue-200
                               shrink-0"
                    >

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </div>


                    {{-- USER INFO --}}
                    <div class="min-w-0">

                        <div class="font-semibold text-slate-800 truncate">
                            {{ auth()->user()->name }}
                        </div>

                        <div class="text-xs text-slate-500">
                            {{ ucfirst(auth()->user()->role) }}
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    LOGOUT
                ================================================== --}}
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2
                               px-4 py-3
                               rounded-xl
                               border border-slate-200
                               bg-white
                               text-slate-600
                               font-medium
                               hover:bg-red-50
                               hover:text-red-600
                               hover:border-red-200
                               transition"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12H3m0 0l4-4m-4 4l4 4M15 5h4a2 2 0 012 2v10a2 2 0 01-2 2h-4"
                            />

                        </svg>

                        <span>Logout</span>

                    </button>

                </form>

            </div>

        </aside>


        {{-- =========================================================
            MAIN CONTENT
        ========================================================== --}}
        <div class="flex-1 min-w-0 min-h-screen">

            {{-- HEADER --}}
            <header class="bg-white border-b border-slate-200 shadow-sm">

                <div class="px-6 py-4">

                    @isset($header)

                        {{ $header }}

                    @else

                        <h2 class="text-xl font-semibold text-slate-800">
                            Dashboard
                        </h2>

                    @endisset

                </div>

            </header>


            {{-- PAGE CONTENT --}}
            <main class="p-6">

                {{ $slot }}

            </main>

        </div>

    </div>

</body>

</html>
