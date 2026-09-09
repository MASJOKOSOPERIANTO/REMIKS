<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">
                Detail Customer
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Informasi akun customer.
            </p>
        </div>
    </x-slot>


    <div class="space-y-6">

        {{-- BACK --}}
        <div>
            <a href="{{ route('admin.customers.index') }}"
               class="inline-flex items-center gap-2
                      rounded-xl border border-slate-200
                      bg-white px-4 py-2.5
                      text-sm font-medium text-slate-700
                      transition hover:bg-slate-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>

                </svg>

                Kembali ke Customers

            </a>
        </div>


        {{-- CUSTOMER HEADER --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="p-6">

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                    {{-- AVATAR --}}
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center
                                rounded-2xl bg-blue-100
                                text-2xl font-bold text-blue-700">

                        {{ strtoupper(substr($customer->name, 0, 1)) }}

                    </div>


                    {{-- NAME --}}
                    <div class="min-w-0">

                        <h1 class="text-2xl font-bold text-slate-900">
                            {{ $customer->name }}
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $customer->email }}
                        </p>

                        <div class="mt-3">

                            @if($customer->is_active)

                                <span class="inline-flex items-center gap-2
                                             rounded-full bg-emerald-50
                                             px-3 py-1.5
                                             text-xs font-semibold
                                             text-emerald-700">

                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                    Akun Aktif

                                </span>

                            @else

                                <span class="inline-flex items-center gap-2
                                             rounded-full bg-red-50
                                             px-3 py-1.5
                                             text-xs font-semibold
                                             text-red-700">

                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                    Akun Nonaktif

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- INFORMATION CARDS --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- USERNAME / NAMA --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Username / Nama
                        </p>

                        <p class="mt-3 text-xl font-bold text-slate-900 break-words">
                            {{ $customer->name }}
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-blue-50 text-blue-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <circle cx="12"
                                    cy="8"
                                    r="4"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 21a8 8 0 0116 0"/>

                        </svg>

                    </div>

                </div>

            </div>


            {{-- SALDO --}}
            <div class="rounded-2xl border border-blue-100
                        bg-blue-600 p-6 shadow-lg shadow-blue-600/20">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-blue-100">
                            Saldo
                        </p>

                        <p class="mt-3 text-3xl font-bold tracking-tight text-white">
                            Rp {{ number_format($customer->balance ?? 0, 0, ',', '.') }}
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-white/15 text-white">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <circle cx="12"
                                    cy="12"
                                    r="8"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 8v8"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M14.5 10.5c0-.83-1.12-1.5-2.5-1.5s-2.5.67-2.5 1.5 1.12 1.5 2.5 1.5 2.5.67 2.5 1.5-1.12 1.5-2.5 1.5-2.5-.67-2.5-1.5"/>

                        </svg>

                    </div>

                </div>

            </div>


            {{-- JUMLAH VPN --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Jumlah VPN
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ $customer->vpnAccounts()->count() }}
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Total akun VPN
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-violet-50 text-violet-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M9.5 12.5l1.5 1.5 3.5-3.5"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- ACCOUNT INFORMATION --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <h3 class="text-lg font-semibold text-slate-800">
                    Informasi Akun
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi dasar customer.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                {{-- EMAIL --}}
                <div>

                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Email
                    </p>

                    <p class="mt-2 break-all text-sm font-semibold text-slate-800">
                        {{ $customer->email }}
                    </p>

                </div>


                {{-- STATUS --}}
                <div>

                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Status
                    </p>

                    <div class="mt-2">

                        @if($customer->is_active)

                            <span class="inline-flex items-center gap-2
                                         rounded-full bg-emerald-50
                                         px-3 py-1.5
                                         text-xs font-semibold text-emerald-700">

                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                Aktif

                            </span>

                        @else

                            <span class="inline-flex items-center gap-2
                                         rounded-full bg-red-50
                                         px-3 py-1.5
                                         text-xs font-semibold text-red-700">

                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                Nonaktif

                            </span>

                        @endif

                    </div>

                </div>


                {{-- USER ID --}}
                <div>

                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Customer ID
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        #{{ $customer->id }}
                    </p>

                </div>


                {{-- TANGGAL DAFTAR --}}
                <div>

                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Terdaftar
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        {{ $customer->created_at->format('d/m/Y H:i') }}
                    </p>

                </div>

            </div>

        </div>


       {{-- ACTION --}}
<div class="flex flex-wrap gap-3">

    <a
        href="{{ route('admin.customers.index') }}"
        class="inline-flex items-center gap-2 rounded-xl
               border border-slate-200 bg-white
               px-5 py-2.5 text-sm font-medium text-slate-700
               transition hover:bg-slate-50"
    >

        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-4 w-4"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 19l-7-7 7-7"/>

        </svg>

        Kembali

    </a>

</div>

    </div>

</x-app-layout>
