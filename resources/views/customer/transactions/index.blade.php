<x-app-layout>

    <div

        x-data="{

            openTopup: {{ $errors->any() ? 'true' : 'false' }},

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

        {{-- HEADER --}}

        <div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">

                Transaksi

            </h1>

            <p class="mt-1 text-sm text-slate-500">

                Riwayat transaksi dan status pembayaran Anda.

            </p>

        </div>



        {{-- SUCCESS --}}

        @if (session('success'))

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4">

                <div class="flex items-start gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center

                                rounded-xl bg-emerald-100 text-emerald-600">

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



        {{-- ERROR --}}

        @if ($errors->any())

            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4">

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

        @endif



        {{-- SUMMARY --}}

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- TOTAL --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">

                            Total Transaksi

                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900">

                            {{ $transactions->count() }}

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

                            <path stroke-linecap="round"

                                  stroke-linejoin="round"

                                  d="M7 7h10M7 11h10M7 15h6"/>

                            <path stroke-linecap="round"

                                  stroke-linejoin="round"

                                  d="M5 4h14v16H5z"/>

                        </svg>

                    </div>

                </div>

            </div>



            {{-- PENDING --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">

                            Menunggu

                        </p>

                        <p class="mt-3 text-3xl font-bold text-amber-600">

                            {{ $transactions->where('status', 'pending')->count() }}

                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center

                                rounded-xl bg-amber-50 text-amber-600">

                        <svg xmlns="http://www.w3.org/2000/svg"

                             class="h-6 w-6"

                             fill="none"

                             viewBox="0 0 24 24"

                             stroke="currentColor"

                             stroke-width="1.8">

                            <circle cx="12"

                                    cy="12"

                                    r="9"/>

                            <path stroke-linecap="round"

                                  stroke-linejoin="round"

                                  d="M12 7v5l3 2"/>

                        </svg>

                    </div>

                </div>

            </div>



            {{-- APPROVED --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">

                            Berhasil

                        </p>

                        <p class="mt-3 text-3xl font-bold text-emerald-600">

                            {{ $transactions->where('status', 'approved')->count() }}

                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center

                                rounded-xl bg-emerald-50 text-emerald-600">

                        <svg xmlns="http://www.w3.org/2000/svg"

                             class="h-6 w-6"

                             fill="none"

                             viewBox="0 0 24 24"

                             stroke="currentColor"

                             stroke-width="1.8">

                            <path stroke-linecap="round"

                                  stroke-linejoin="round"

                                  d="M5 13l4 4L19 7"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>



        {{-- TRANSACTION TABLE --}}

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- HEADER --}}

            <div class="flex items-center justify-between gap-4

                        border-b border-slate-200 px-6 py-5">

                <div>

                    <h3 class="text-lg font-semibold text-slate-800">

                        Riwayat Transaksi

                    </h3>

                    <p class="mt-1 text-sm text-slate-500">

                        Semua transaksi yang dilakukan dari akun Anda.

                    </p>

                </div>



                {{-- TOP UP BUTTON --}}

                <button

                    type="button"

                    @click="

                        selectedPayment = '';

                        openTopup = true;

                    "

                    class="inline-flex items-center gap-2 rounded-xl

                           bg-blue-600 px-4 py-2.5

                           text-sm font-semibold text-white

                           shadow-lg shadow-blue-600/20

                           transition hover:bg-blue-700

                           active:scale-[0.99]"

                >

                    <span class="text-lg leading-none">

                        +

                    </span>

                    Top Up

                </button>

            </div>



            @if($transactions->count() > 0)

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

                                    Jenis

                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold

                                           uppercase tracking-wider text-slate-500">

                                    Nominal

                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold

                                           uppercase tracking-wider text-slate-500">

                                    Metode

                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold

                                           uppercase tracking-wider text-slate-500">

                                    Tanggal

                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold

                                           uppercase tracking-wider text-slate-500">

                                    Status

                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold

                                           uppercase tracking-wider text-slate-500">

                                    Keterangan

                                </th>

                                <th class="px-6 py-4 text-center text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Aksi
                                </th>

                            </tr>

                        </thead>



                        <tbody class="divide-y divide-slate-100">

                            @foreach($transactions as $transaction)

                                <tr class="transition hover:bg-slate-50">

                                    <td class="px-6 py-4 text-sm text-slate-600">

                                        {{ $loop->iteration }}

                                    </td>



                                    <td class="px-6 py-4">

                                        @if($transaction->type === 'topup')

                                            <span class="inline-flex items-center gap-2

                                                         text-sm font-medium text-blue-700">

                                                <span class="h-2 w-2 rounded-full bg-blue-600"></span>

                                                Top Up

                                            </span>

                                        @else

                                            <span class="inline-flex items-center gap-2

                                                         text-sm font-medium text-slate-700">

                                                <span class="h-2 w-2 rounded-full bg-slate-500"></span>

                                                {{ ucfirst($transaction->type) }}

                                            </span>

                                        @endif

                                    </td>



                                    <td class="px-6 py-4">

                                        <span class="font-semibold text-slate-800">

                                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}

                                        </span>

                                    </td>



                                    <td class="px-6 py-4 text-sm text-slate-600">

                                        {{ $transaction->payment_method ?: '-' }}

                                    </td>



                                    <td class="px-6 py-4 text-sm text-slate-600">

                                        {{ $transaction->created_at->format('d/m/Y') }}

                                        <div class="mt-1 text-xs text-slate-400">

                                            {{ $transaction->created_at->format('H:i') }}

                                        </div>

                                    </td>



                                    <td class="px-6 py-4">

                                        @if($transaction->status === 'pending')

                                            <span class="inline-flex items-center gap-2

                                                         rounded-full bg-amber-50 px-3 py-1.5

                                                         text-xs font-semibold text-amber-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                                                Pending

                                            </span>

                                        @elseif($transaction->status === 'approved')

                                            <span class="inline-flex items-center gap-2

                                                         rounded-full bg-emerald-50 px-3 py-1.5

                                                         text-xs font-semibold text-emerald-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                                Approved

                                            </span>

                                        @elseif($transaction->status === 'rejected')

                                            <span class="inline-flex items-center gap-2

                                                         rounded-full bg-red-50 px-3 py-1.5

                                                         text-xs font-semibold text-red-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                                Rejected

                                            </span>

                                        @else

                                            <span class="inline-flex items-center gap-2

                                                         rounded-full bg-slate-100 px-3 py-1.5

                                                         text-xs font-semibold text-slate-700">

                                                {{ ucfirst($transaction->status) }}

                                            </span>

                                        @endif

                                    </td>



                                    <td class="px-6 py-4">

                                        <div class="max-w-xs text-sm text-slate-600">

                                            {{ $transaction->description ?: '-' }}

                                        </div>

                                    </td>

                                    {{-- AKSI --}}
                                    <td class="px-6 py-4">

                                        @if(
                                            $transaction->type === 'topup' &&
                                            $transaction->status === 'pending'
                                        )

                                            @php
                                                $whatsappNumber = env('WHATSAPP_ADMIN_NUMBER');

                                                $whatsappMessage =
                                                    "Halo Admin,\n\n" .
                                                    "Saya sudah melakukan top up saldo VPN Panel.\n\n" .
                                                    "Nama      : " . auth()->user()->name . "\n" .
                                                    "Nominal   : Rp" . number_format($transaction->amount, 0, ',', '.') . "\n" .
                                                    "Metode    : " . ($transaction->payment_method ?: '-') . "\n" .
                                                    "Transaksi : #" . $transaction->id . "\n\n" .
                                                    "Mohon dibantu konfirmasi pembayaran saya.\n\n" .
                                                    "Terima kasih.";
                                            @endphp

                                            <a
                                                href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode($whatsappMessage) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5
                                                       rounded-xl
                                                       bg-emerald-50
                                                       px-3 py-2
                                                       text-xs font-semibold
                                                       text-emerald-700
                                                       transition
                                                       hover:bg-emerald-100"
                                            >

                                                {{-- WHATSAPP ICON --}}
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="1.8"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M21 11.5a8.5 8.5 0 0 1-12.8 7.3L3 20l1.3-5A8.5 8.5 0 1 1 21 11.5Z"
                                                    />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M8.5 8.8c.2-.4.4-.4.7-.4h.6c.2 0 .4.1.5.4l.8 1.8c.1.2.1.4-.1.6l-.6.7c.7 1.2 1.6 2.1 2.8 2.8l.7-.6c.2-.2.4-.2.6-.1l1.8.8c.3.1.4.3.4.5v.6c0 .3 0 .5-.4.7-.5.3-1.1.4-1.7.2-2.1-.6-4-1.8-5.4-3.3-1.5-1.5-2.7-3.3-3.3-5.4-.2-.6-.1-1.2.2-1.8Z"
                                                    />
                                                </svg>

                                                Konfirmasi WhatsApp

                                            </a>

                                        @else

                                            <span class="text-xs text-slate-300">
                                                -
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center

                                rounded-2xl bg-slate-100">

                        <svg xmlns="http://www.w3.org/2000/svg"

                             class="h-8 w-8 text-slate-400"

                             fill="none"

                             viewBox="0 0 24 24"

                             stroke="currentColor"

                             stroke-width="1.8">

                            <path stroke-linecap="round"

                                  stroke-linejoin="round"

                                  d="M7 7h10M7 11h10M7 15h6"/>

                            <path stroke-linecap="round"

                                  stroke-linejoin="round"

                                  d="M5 4h14v16H5z"/>

                        </svg>

                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-800">

                        Belum Ada Transaksi

                    </h3>

                    <p class="mt-2 text-sm text-slate-500">

                        Belum ada transaksi yang tercatat pada akun Anda.

                    </p>

                    <button

                        type="button"

                        @click="

                            selectedPayment = '';

                            openTopup = true;

                        "

                        class="mt-6 inline-flex items-center gap-2

                               rounded-xl bg-blue-600 px-5 py-2.5

                               text-sm font-medium text-white

                               transition hover:bg-blue-700"

                    >

                        <span class="text-lg leading-none">

                            +

                        </span>

                        Mulai Top Up

                    </button>

                </div>

            @endif

        </div>



        {{-- ========================================================= --}}

        {{-- TOP UP MODAL --}}

        {{-- ========================================================= --}}

        <div

            x-show="openTopup"

            x-cloak

            class="fixed inset-0 z-50 flex items-center justify-center

                   overflow-y-auto bg-slate-900/50 px-4 py-6"

        >

            <div

                @click.outside="openTopup = false"

                class="w-full max-w-xl overflow-hidden

                       rounded-2xl bg-white shadow-2xl"

            >

                {{-- MODAL HEADER --}}

                <div class="flex items-center justify-between

                            border-b border-slate-100 px-6 py-5">

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

                        class="rounded-lg px-3 py-2 text-slate-400

                               transition hover:bg-slate-100

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

                        {{-- NOMINAL --}}

                        <div>

                            <label

                                for="transaction_amount"

                                class="block text-sm font-medium text-slate-700"

                            >

                                Nominal Top Up

                            </label>

                            <div class="relative mt-2">

                                <div class="pointer-events-none absolute inset-y-0

                                            left-0 flex items-center pl-4">

                                    <span class="text-sm font-semibold text-slate-500">

                                        Rp

                                    </span>

                                </div>

                                <input

                                    id="transaction_amount"

                                    name="amount"

                                    type="number"

                                    min="1000"

                                    step="1000"

                                    required

                                    placeholder="10000"

                                    value="{{ old('amount') }}"

                                    class="w-full rounded-xl border border-slate-300

                                           bg-slate-50 py-3 pl-11 pr-4

                                           text-sm text-slate-900 outline-none

                                           transition focus:border-blue-500

                                           focus:bg-white focus:ring-4

                                           focus:ring-blue-500/10"

                                >

                            </div>

                            <p class="mt-1 text-xs text-slate-400">

                                Minimal top up Rp1.000

                            </p>

                        </div>



                        {{-- PAYMENT METHOD --}}

                        <div>

                            <label

                                for="transaction_payment_method"

                                class="block text-sm font-medium text-slate-700"

                            >

                                Metode Pembayaran

                            </label>

                            <select

                                id="transaction_payment_method"

                                name="payment_method"

                                x-model="selectedPayment"

                                required

                                class="mt-2 w-full rounded-xl border border-slate-300

                                       bg-slate-50 px-4 py-3

                                       text-sm text-slate-900 outline-none

                                       transition focus:border-blue-500

                                       focus:bg-white focus:ring-4

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



                        {{-- PAYMENT DETAIL --}}

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

                                <div class="rounded-xl border border-slate-200

                                            bg-slate-50 p-5">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center

                                                    justify-center rounded-xl

                                                    bg-blue-50 text-blue-600">

                                            <svg xmlns="http://www.w3.org/2000/svg"

                                                 class="h-5 w-5"

                                                 fill="none"

                                                 viewBox="0 0 24 24"

                                                 stroke="currentColor"

                                                 stroke-width="1.8">

                                                <path stroke-linecap="round"

                                                      stroke-linejoin="round"

                                                      d="M3 10h18M5 10v8m4-8v8m6-8v8m4-8v8M3 18h18M4 10l8-5 8 5"/>

                                            </svg>

                                        </div>

                                        <div>

                                            <p

                                                class="text-sm font-semibold text-slate-900"

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

                                        <p

                                            class="mt-1 break-all font-mono

                                                   text-lg font-bold text-slate-900"

                                            x-text="selectedPaymentData.account_number || '-'"

                                        ></p>

                                    </div>



                                    <div class="mt-4">

                                        <p class="text-xs text-slate-400">

                                            Atas Nama

                                        </p>

                                        <p

                                            class="mt-1 text-sm font-semibold text-slate-800"

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

                                <div class="rounded-xl border border-purple-200

                                            bg-purple-50/40 p-5">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center

                                                    justify-center rounded-xl

                                                    bg-purple-100 text-purple-600">

                                            <span class="text-xs font-bold">

                                                QR

                                            </span>

                                        </div>

                                        <div>

                                            <p

                                                class="text-sm font-semibold text-slate-900"

                                                x-text="selectedPaymentData.name"

                                            ></p>

                                            <p class="mt-1 text-xs text-slate-400">

                                                Pembayaran QRIS

                                            </p>

                                        </div>

                                    </div>



                                    <div class="mt-5 flex min-h-[220px]

                                                items-center justify-center

                                                rounded-xl border border-purple-100

                                                bg-white p-4">

                                        <template x-if="selectedPaymentData.qris_image">

                                            <img

                                                :src="selectedPaymentData.qris_image"

                                                :alt="'QRIS ' + selectedPaymentData.name"

                                                class="max-h-56 w-auto rounded-lg object-contain"

                                            >

                                        </template>



                                        <template x-if="!selectedPaymentData.qris_image">

                                            <p class="text-sm text-slate-400">

                                                Gambar QRIS belum tersedia.

                                            </p>

                                        </template>

                                    </div>



                                    <template x-if="selectedPaymentData.account_name">

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



                        {{-- PROOF --}}

                        <div>

                            <label

                                for="transaction_proof"

                                class="block text-sm font-medium text-slate-700"

                            >

                                Bukti Pembayaran

                            </label>

                            <input

                                id="transaction_proof"

                                name="proof"

                                type="file"

                                accept=".jpg,.jpeg,.png,.pdf"

                                required

                                class="mt-2 w-full rounded-xl

                                       border border-slate-300

                                       bg-slate-50 p-2

                                       text-sm text-slate-500"

                            >

                            <p class="mt-1 text-xs text-slate-400">

                                JPG, JPEG, PNG atau PDF — maksimal 5 MB.

                            </p>

                        </div>

                    </div>



                    {{-- FOOTER --}}

                    <div class="flex justify-end gap-2

                                border-t border-slate-100

                                bg-slate-50 px-6 py-4">

                        <button

                            type="button"

                            @click="openTopup = false"

                            class="rounded-xl border border-slate-200

                                   bg-white px-4 py-2.5

                                   text-sm font-medium text-slate-700

                                   transition hover:bg-slate-50"

                        >

                            Batal

                        </button>



                        <button

                            type="submit"

                            @disabled($paymentSettings->count() === 0)

                            class="rounded-xl bg-blue-600

                                   px-5 py-2.5

                                   text-sm font-semibold text-white

                                   shadow-lg shadow-blue-600/20

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
