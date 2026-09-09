<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-xl font-semibold text-slate-900">
                Top Up Saldo
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Tambahkan saldo untuk membeli layanan VPN.
            </p>
        </div>

    </x-slot>


    <div class="space-y-6">

        {{-- =========================================================
             SUCCESS
        ========================================================== --}}
        @if (session('success'))

            <div
                class="rounded-2xl border border-emerald-200
                       bg-emerald-50 px-5 py-4"
            >

                <div class="flex items-start gap-3">

                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center
                               rounded-xl bg-emerald-100 text-emerald-600"
                    >
                        ✓
                    </div>

                    <div>

                        <p class="text-sm font-semibold text-emerald-800">
                            Pengajuan Berhasil
                        </p>

                        <p class="mt-1 text-sm text-emerald-700">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================
             ERROR
        ========================================================== --}}
        @if ($errors->any())

            <div
                class="rounded-2xl border border-red-200
                       bg-red-50 px-5 py-4"
            >

                <p class="text-sm font-semibold text-red-800">
                    Terjadi Kesalahan
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


        {{-- =========================================================
             TOP SUMMARY
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- SALDO --}}
            <div
                class="rounded-2xl bg-blue-600
                       p-6 shadow-lg shadow-blue-600/20"
            >

                <p class="text-sm font-medium text-blue-100">
                    Saldo Anda
                </p>

                <p class="mt-2 text-3xl font-bold tracking-tight text-white">
                    Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm text-blue-100">
                    Saldo akan bertambah setelah top up dikonfirmasi admin.
                </p>

            </div>


            {{-- STATUS --}}
            <div
                class="rounded-2xl border border-slate-200
                       bg-white p-6 shadow-sm"
            >

                <p class="text-sm font-medium text-slate-500">
                    Pengajuan Top Up
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $transactions->count() }}
                </p>

                <p class="mt-2 text-sm text-slate-500">
                    Total pengajuan top up Anda.
                </p>

            </div>

        </div>


        {{-- =========================================================
             PAYMENT METHODS
        ========================================================== --}}
        <div
            class="rounded-2xl border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <div>

                <h3 class="text-lg font-semibold text-slate-900">
                    Metode Pembayaran
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Transfer ke salah satu metode pembayaran aktif di bawah ini.
                </p>

            </div>


            @if ($paymentSettings->count() > 0)

                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">

                    @foreach ($paymentSettings as $payment)

                        {{-- BANK / EWALLET --}}
                        @if ($payment->type !== 'qris')

                            <div
                                class="rounded-2xl border border-slate-200
                                       bg-slate-50 p-5"
                            >

                                <div class="flex items-center justify-between">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-11 w-11 items-center justify-center
                                                   rounded-xl
                                                   @if($payment->type === 'ewallet')
                                                       bg-emerald-50 text-emerald-600
                                                   @else
                                                       bg-blue-50 text-blue-600
                                                   @endif"
                                        >

                                            @if ($payment->type === 'ewallet')

                                                <span class="text-lg font-bold">
                                                    $
                                                </span>

                                            @else

                                                <span class="text-lg font-bold">
                                                    B
                                                </span>

                                            @endif

                                        </div>


                                        <div>

                                            <p class="text-base font-semibold text-slate-900">
                                                {{ $payment->name }}
                                            </p>

                                            <p class="mt-1 text-xs uppercase tracking-wide text-slate-400">
                                                {{ $payment->type }}
                                            </p>

                                        </div>

                                    </div>


                                    <span
                                        class="rounded-lg border border-emerald-200
                                               bg-emerald-50 px-2.5 py-1
                                               text-[11px] font-semibold text-emerald-700"
                                    >
                                        Aktif
                                    </span>

                                </div>


                                <div
                                    class="mt-5 rounded-xl border
                                           border-slate-200 bg-white p-4"
                                >

                                    <p class="text-xs text-slate-400">
                                        Nomor Rekening / Tujuan
                                    </p>

                                    <div class="mt-1 flex items-center justify-between gap-3">

                                        <p
                                            class="break-all
                                                   font-mono
                                                   text-lg font-bold
                                                   text-slate-900"
                                        >
                                            {{ $payment->account_number ?: '-' }}
                                        </p>

                                        @if ($payment->account_number)

                                            <button
                                                type="button"
                                                onclick="copyPayment('{{ $payment->account_number }}')"
                                                class="shrink-0 rounded-lg
                                                       border border-slate-200
                                                       bg-white px-3 py-2
                                                       text-xs font-medium
                                                       text-slate-600
                                                       transition hover:bg-slate-50"
                                            >
                                                Salin
                                            </button>

                                        @endif

                                    </div>


                                    <div class="mt-4">

                                        <p class="text-xs text-slate-400">
                                            Atas Nama
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $payment->account_name ?: '-' }}
                                        </p>

                                    </div>

                                </div>

                            </div>


                        {{-- QRIS --}}
                        @else

                            <div
                                class="rounded-2xl border border-purple-200
                                       bg-purple-50/40 p-5"
                            >

                                <div class="flex items-center justify-between">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-11 w-11 items-center justify-center
                                                   rounded-xl bg-purple-100
                                                   text-purple-600"
                                        >
                                            <span class="text-xs font-bold">
                                                QR
                                            </span>
                                        </div>

                                        <div>

                                            <p class="text-base font-semibold text-slate-900">
                                                {{ $payment->name }}
                                            </p>

                                            <p class="mt-1 text-xs uppercase tracking-wide text-slate-400">
                                                QRIS
                                            </p>

                                        </div>

                                    </div>


                                    <span
                                        class="rounded-lg border border-emerald-200
                                               bg-emerald-50 px-2.5 py-1
                                               text-[11px] font-semibold text-emerald-700"
                                    >
                                        Aktif
                                    </span>

                                </div>


                                <div
                                    class="mt-5 flex min-h-[260px]
                                           items-center justify-center
                                           rounded-xl border border-purple-100
                                           bg-white p-5"
                                >

                                    @if ($payment->qris_image)

                                        <img
                                            src="{{ asset('storage/' . $payment->qris_image) }}"
                                            alt="QRIS {{ $payment->name }}"
                                            class="max-h-60 w-auto
                                                   rounded-xl
                                                   border border-slate-200
                                                   bg-white p-2
                                                   shadow-sm"
                                        />

                                    @else

                                        <div class="text-center">

                                            <div
                                                class="mx-auto flex h-14 w-14
                                                       items-center justify-center
                                                       rounded-2xl bg-purple-100
                                                       text-purple-600"
                                            >
                                                QR
                                            </div>

                                            <p class="mt-4 text-sm font-semibold text-slate-700">
                                                QRIS belum tersedia
                                            </p>

                                        </div>

                                    @endif

                                </div>


                                @if ($payment->account_name)

                                    <div class="mt-4">

                                        <p class="text-xs text-slate-400">
                                            Atas Nama
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $payment->account_name }}
                                        </p>

                                    </div>

                                @endif

                            </div>

                        @endif

                    @endforeach

                </div>

            @else

                <div
                    class="mt-5 rounded-xl
                           border border-amber-200
                           bg-amber-50 px-5 py-4"
                >

                    <p class="text-sm font-semibold text-amber-800">
                        Belum ada metode pembayaran aktif.
                    </p>

                    <p class="mt-1 text-sm text-amber-700">
                        Silakan hubungi admin untuk mendapatkan informasi pembayaran.
                    </p>

                </div>

            @endif

        </div>


        {{-- =========================================================
             FORM TOP UP
        ========================================================== --}}
        <div
            class="rounded-2xl border border-slate-200
                   bg-white shadow-sm"
        >

            <div class="border-b border-slate-100 px-6 py-5">

                <h3 class="text-lg font-semibold text-slate-900">
                    Ajukan Top Up
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Setelah melakukan pembayaran, upload bukti transfer Anda.
                </p>

            </div>


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
                            for="amount"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Nominal Top Up
                        </label>

                        <div class="relative mt-2">

                            <div
                                class="pointer-events-none absolute inset-y-0 left-0
                                       flex items-center pl-4"
                            >

                                <span class="text-sm font-semibold text-slate-500">
                                    Rp
                                </span>

                            </div>

                            <input
                                id="amount"
                                name="amount"
                                type="number"
                                min="1000"
                                step="1000"
                                value="{{ old('amount') }}"
                                required
                                placeholder="10000"
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
                            >

                        </div>

                        <p class="mt-2 text-xs text-slate-400">
                            Minimal top up Rp1.000
                        </p>

                        @error('amount')

                            <p class="mt-2 text-xs text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- METODE PEMBAYARAN --}}
                    <div>

                        <label
                            for="payment_method"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Metode Pembayaran
                        </label>

                        <select
                            id="payment_method"
                            name="payment_method"
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

                                <option
                                    value="{{ $payment->name }}"
                                    @selected(old('payment_method') === $payment->name)
                                >
                                    {{ $payment->name }}
                                    -
                                    {{ strtoupper($payment->type) }}
                                </option>

                            @endforeach

                        </select>

                        @if ($paymentSettings->count() === 0)

                            <p class="mt-2 text-xs text-red-600">
                                Belum tersedia metode pembayaran aktif.
                            </p>

                        @endif

                        @error('payment_method')

                            <p class="mt-2 text-xs text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- BUKTI --}}
                    <div>

                        <label
                            for="proof"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Bukti Pembayaran
                        </label>

                        <div
                            class="mt-2 rounded-xl
                                   border-2 border-dashed
                                   border-slate-300
                                   bg-slate-50
                                   p-5
                                   transition
                                   hover:border-blue-400"
                        >

                            <input
                                id="proof"
                                name="proof"
                                type="file"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                                class="block w-full
                                       text-sm text-slate-500
                                       file:mr-4
                                       file:rounded-lg
                                       file:border-0
                                       file:bg-blue-50
                                       file:px-4
                                       file:py-2
                                       file:text-sm
                                       file:font-semibold
                                       file:text-blue-700
                                       hover:file:bg-blue-100"
                            />

                            <p class="mt-2 text-xs text-slate-400">
                                JPG, JPEG, PNG atau PDF — maksimal 5 MB.
                            </p>

                        </div>

                        @error('proof')

                            <p class="mt-2 text-xs text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- BUTTON --}}
                <div
                    class="border-t border-slate-100
                           bg-slate-50
                           px-6 py-4"
                >

                    <button
                        type="submit"
                        @disabled($paymentSettings->count() === 0)
                        class="flex w-full items-center justify-center
                               rounded-xl
                               bg-blue-600
                               px-4 py-3
                               text-sm font-semibold text-white
                               shadow-lg shadow-blue-600/20
                               transition hover:bg-blue-700
                               focus:outline-none
                               focus:ring-4 focus:ring-blue-500/20
                               disabled:cursor-not-allowed
                               disabled:opacity-50"
                    >
                        Kirim Pengajuan Top Up
                    </button>

                </div>

            </form>

        </div>


        {{-- =========================================================
             HISTORY
        ========================================================== --}}
        <div
            class="rounded-2xl border border-slate-200
                   bg-white shadow-sm"
        >

            <div
                class="border-b border-slate-100
                       px-6 py-5"
            >

                <h3 class="text-lg font-semibold text-slate-900">
                    Riwayat Top Up
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Riwayat pengajuan top up Anda.
                </p>

            </div>


            @if ($transactions->count() > 0)

                <div class="divide-y divide-slate-100">

                    @foreach ($transactions as $transaction)

                        <div
                            class="flex flex-col gap-3
                                   px-6 py-5
                                   sm:flex-row
                                   sm:items-center
                                   sm:justify-between"
                        >

                            <div>

                                <p class="text-sm font-semibold text-slate-900">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ ucfirst($transaction->payment_method) }}
                                    ·
                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                </p>

                            </div>


                            @if ($transaction->status === 'approved')

                                <span
                                    class="w-fit rounded-lg
                                           border border-emerald-200
                                           bg-emerald-50
                                           px-2.5 py-1
                                           text-[11px]
                                           font-semibold
                                           text-emerald-700"
                                >
                                    Berhasil
                                </span>

                            @elseif ($transaction->status === 'rejected')

                                <span
                                    class="w-fit rounded-lg
                                           border border-red-200
                                           bg-red-50
                                           px-2.5 py-1
                                           text-[11px]
                                           font-semibold
                                           text-red-700"
                                >
                                    Ditolak
                                </span>

                            @else

                                <span
                                    class="w-fit rounded-lg
                                           border border-amber-200
                                           bg-amber-50
                                           px-2.5 py-1
                                           text-[11px]
                                           font-semibold
                                           text-amber-700"
                                >
                                    Menunggu Konfirmasi
                                </span>

                            @endif

                        </div>

                    @endforeach

                </div>

            @else

                <div class="px-6 py-12 text-center">

                    <p class="text-sm text-slate-400">
                        Belum ada riwayat top up.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         COPY NUMBER
    ========================================================== --}}
    <script>

        function copyPayment(value) {

            navigator.clipboard.writeText(value)
                .then(function () {

                    if (window.Swal) {

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Nomor berhasil disalin',
                            showConfirmButton: false,
                            timer: 1800,
                            timerProgressBar: true
                        });

                    } else {

                        alert('Nomor berhasil disalin.');

                    }

                })
                .catch(function () {

                    alert('Nomor tidak dapat disalin.');

                });

        }

    </script>

</x-app-layout>
