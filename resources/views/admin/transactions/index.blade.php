<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-xl font-semibold text-slate-900">
                Transactions
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Kelola pengajuan top up saldo customer.
            </p>
        </div>

    </x-slot>


    <div
        x-data="{
            openProof: false,
            openReject: false,

            selectedProof: '',
            selectedCustomer: '',
            selectedAmount: '',
            selectedDate: '',

            rejectId: '',
            rejectCustomer: '',
            rejectAmount: '',

            showProof(data) {
                this.selectedProof = data.proof;
                this.selectedCustomer = data.customer;
                this.selectedAmount = data.amount;
                this.selectedDate = data.date;
                this.openProof = true;
            },

            showReject(data) {
                this.rejectId = data.id;
                this.rejectCustomer = data.customer;
                this.rejectAmount = data.amount;
                this.openReject = true;
            }
        }"
        class="space-y-6"
    >


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
                               rounded-xl bg-emerald-100
                               text-emerald-600"
                    >
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


        {{-- =========================================================
            ERROR
        ========================================================== --}}
        @if ($errors->any())

            <div
                class="rounded-2xl border border-red-200
                       bg-red-50 px-5 py-4"
            >

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


        {{-- =========================================================
            SUMMARY
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- TOTAL TRANSACTIONS --}}
            <div
                class="rounded-2xl border border-slate-200
                       bg-white p-6 shadow-sm"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Total Pengajuan
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ $transactions->count() }}
                        </p>

                    </div>

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-xl bg-blue-50 text-blue-600"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <rect
                                x="3"
                                y="4"
                                width="18"
                                height="16"
                                rx="2"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7 8h10M7 12h7M7 16h5"
                            />
                        </svg>
                    </div>

                </div>

            </div>


            {{-- PENDING --}}
            <div
                class="rounded-2xl border border-amber-200
                       bg-amber-50 p-6 shadow-sm"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-amber-700">
                            Menunggu Konfirmasi
                        </p>

                        <p class="mt-3 text-3xl font-bold text-amber-800">
                            {{ $transactions->where('status', 'pending')->count() }}
                        </p>

                    </div>

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-xl bg-amber-100 text-amber-600"
                    >
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
                                r="8"
                            />

                            <path
                                stroke-linecap="round"
                                d="M12 8v4l2.5 2"
                            />
                        </svg>
                    </div>

                </div>

            </div>


            {{-- APPROVED --}}
            <div
                class="rounded-2xl border border-emerald-200
                       bg-emerald-50 p-6 shadow-sm"
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-emerald-700">
                            Berhasil
                        </p>

                        <p class="mt-3 text-3xl font-bold text-emerald-800">
                            {{ $transactions->where('status', 'approved')->count() }}
                        </p>

                    </div>

                    <div
                        class="flex h-11 w-11 items-center justify-center
                               rounded-xl bg-emerald-100
                               text-emerald-600"
                    >
                        ✓
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            TRANSACTION TABLE
        ========================================================== --}}
        <div
            class="overflow-hidden rounded-2xl
                   border border-slate-200
                   bg-white shadow-sm"
        >

            <div
                class="border-b border-slate-100
                       px-6 py-5"
            >

                <div>

                    <h3 class="text-lg font-semibold text-slate-900">
                        Daftar Pengajuan Top Up
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Periksa pembayaran customer sebelum menyetujui top up.
                    </p>

                </div>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                No
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Customer
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Nominal
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Metode
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Tanggal
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Status
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 text-right font-semibold text-slate-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse ($transactions as $index => $transaction)

                            <tr class="transition hover:bg-slate-50">

                                {{-- NO --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                    {{ $index + 1 }}
                                </td>


                                {{-- CUSTOMER --}}
                                <td class="px-5 py-4">

                                    <div class="font-semibold text-slate-900">
                                        {{ $transaction->user->name ?? '-' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-400">
                                        {{ $transaction->user->email ?? '-' }}
                                    </div>

                                </td>


                                {{-- AMOUNT --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <span
                                        class="font-semibold text-slate-900"
                                    >
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </span>

                                </td>


                                {{-- PAYMENT METHOD --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <span
                                        class="rounded-lg
                                               border border-slate-200
                                               bg-slate-50
                                               px-2.5 py-1
                                               text-xs font-medium
                                               text-slate-700"
                                    >
                                        {{ $transaction->payment_method ?? '-' }}
                                    </span>

                                </td>


                                {{-- DATE --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <p class="text-sm text-slate-700">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $transaction->created_at->format('H:i') }}
                                    </p>

                                </td>


                                {{-- STATUS --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    @if ($transaction->status === 'approved')

                                        <span
                                            class="inline-flex rounded-lg
                                                   border border-emerald-200
                                                   bg-emerald-50
                                                   px-2.5 py-1
                                                   text-xs font-semibold
                                                   text-emerald-700"
                                        >
                                            Approved
                                        </span>

                                    @elseif ($transaction->status === 'rejected')

                                        <span
                                            class="inline-flex rounded-lg
                                                   border border-red-200
                                                   bg-red-50
                                                   px-2.5 py-1
                                                   text-xs font-semibold
                                                   text-red-700"
                                        >
                                            Rejected
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex rounded-lg
                                                   border border-amber-200
                                                   bg-amber-50
                                                   px-2.5 py-1
                                                   text-xs font-semibold
                                                   text-amber-700"
                                        >
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="px-5 py-4">

                                    <div
                                        class="flex flex-wrap justify-end gap-2"
                                    >

                                        {{-- VIEW PROOF --}}
                                        <button
                                            type="button"
                                            @click="showProof({
                                                proof: @js(
                                                    $transaction->proof
                                                        ? asset('storage/' . $transaction->proof)
                                                        : ''
                                                ),
                                                customer: @js($transaction->user->name ?? '-'),
                                                amount: @js(
                                                    'Rp ' . number_format(
                                                        $transaction->amount,
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                ),
                                                date: @js(
                                                    $transaction->created_at->format('d M Y H:i')
                                                )
                                            })"
                                            class="rounded-lg
                                                   border border-blue-200
                                                   bg-blue-50
                                                   px-3 py-2
                                                   text-xs font-medium
                                                   text-blue-700
                                                   transition
                                                   hover:bg-blue-100"
                                        >
                                            Lihat Bukti
                                        </button>


                                        {{-- PENDING ACTIONS --}}
                                        @if ($transaction->status === 'pending')

                                            {{-- APPROVE --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.transactions.approve', $transaction) }}"
                                                class="approve-transaction-form"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="rounded-lg
                                                           border border-emerald-200
                                                           bg-emerald-50
                                                           px-3 py-2
                                                           text-xs font-medium
                                                           text-emerald-700
                                                           transition
                                                           hover:bg-emerald-100"
                                                >
                                                    Konfirmasi
                                                </button>

                                            </form>


                                            {{-- REJECT --}}
                                            <button
                                                type="button"
                                                @click="showReject({
                                                    id: {{ $transaction->id }},
                                                    customer: @js($transaction->user->name ?? '-'),
                                                    amount: @js(
                                                        'Rp ' . number_format(
                                                            $transaction->amount,
                                                            0,
                                                            ',',
                                                            '.'
                                                        )
                                                    )
                                                })"
                                                class="rounded-lg
                                                       border border-red-200
                                                       bg-red-50
                                                       px-3 py-2
                                                       text-xs font-medium
                                                       text-red-700
                                                       transition
                                                       hover:bg-red-100"
                                            >
                                                Tolak
                                            </button>

                                        @endif


                                        {{-- DELETE --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.transactions.destroy', $transaction) }}"
                                            class="delete-transaction-form"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg
                                                       border border-slate-200
                                                       bg-white
                                                       px-3 py-2
                                                       text-xs font-medium
                                                       text-slate-600
                                                       transition
                                                       hover:bg-slate-50"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="px-6 py-16 text-center"
                                >

                                    <div
                                        class="mx-auto flex h-14 w-14
                                               items-center justify-center
                                               rounded-2xl
                                               bg-slate-100
                                               text-slate-400"
                                    >
                                        —
                                    </div>

                                    <p class="mt-4 text-sm font-semibold text-slate-700">
                                        Belum ada transaksi
                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Pengajuan top up customer akan muncul di sini.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
            PROOF MODAL
        ========================================================== --}}
        <div
            x-show="openProof"
            x-cloak
            class="fixed inset-0 z-50
                   flex items-center justify-center
                   overflow-y-auto
                   bg-slate-900/60
                   px-4 py-6"
        >

            <div
                @click.outside="openProof = false"
                class="w-full max-w-2xl
                       overflow-hidden
                       rounded-2xl
                       bg-white
                       shadow-2xl"
            >

                {{-- HEADER --}}
                <div
                    class="flex items-center justify-between
                           border-b border-slate-100
                           px-6 py-5"
                >

                    <div>

                        <h3 class="text-lg font-semibold text-slate-900">
                            Bukti Pembayaran
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Periksa bukti transfer customer.
                        </p>

                    </div>

                    <button
                        type="button"
                        @click="openProof = false"
                        class="rounded-lg px-3 py-2
                               text-slate-400
                               hover:bg-slate-100"
                    >
                        ✕
                    </button>

                </div>


                {{-- INFO --}}
                <div class="border-b border-slate-100 px-6 py-4">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                        <div>

                            <p class="text-xs text-slate-400">
                                Customer
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold text-slate-800"
                                x-text="selectedCustomer"
                            ></p>

                        </div>


                        <div>

                            <p class="text-xs text-slate-400">
                                Nominal
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold text-slate-800"
                                x-text="selectedAmount"
                            ></p>

                        </div>


                        <div>

                            <p class="text-xs text-slate-400">
                                Tanggal
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold text-slate-800"
                                x-text="selectedDate"
                            ></p>

                        </div>

                    </div>

                </div>


                {{-- PROOF --}}
                <div class="p-6">

                    <template x-if="selectedProof">

                        <div class="flex justify-center">

                            <img
                                :src="selectedProof"
                                alt="Bukti pembayaran"
                                class="max-h-[65vh]
                                       max-w-full
                                       rounded-xl
                                       border border-slate-200
                                       object-contain
                                       shadow-sm"
                            >

                        </div>

                    </template>


                    <template x-if="!selectedProof">

                        <div
                            class="rounded-xl
                                   bg-slate-50
                                   px-5 py-10
                                   text-center"
                        >

                            <p class="text-sm text-slate-500">
                                Bukti pembayaran tidak tersedia.
                            </p>

                        </div>

                    </template>

                </div>


                {{-- FOOTER --}}
                <div
                    class="flex justify-end
                           border-t border-slate-100
                           bg-slate-50
                           px-6 py-4"
                >

                    <button
                        type="button"
                        @click="openProof = false"
                        class="rounded-xl
                               border border-slate-200
                               bg-white
                               px-4 py-2.5
                               text-sm font-medium
                               text-slate-700
                               hover:bg-slate-50"
                    >
                        Tutup
                    </button>

                </div>

            </div>

        </div>


        {{-- =========================================================
            REJECT MODAL
        ========================================================== --}}
        <div
            x-show="openReject"
            x-cloak
            class="fixed inset-0 z-50
                   flex items-center justify-center
                   bg-slate-900/60
                   px-4 py-6"
        >

            <div
                @click.outside="openReject = false"
                class="w-full max-w-md
                       overflow-hidden
                       rounded-2xl
                       bg-white
                       shadow-2xl"
            >

                <div
                    class="border-b border-slate-100
                           px-6 py-5"
                >

                    <h3 class="text-lg font-semibold text-slate-900">
                        Tolak Top Up
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Berikan alasan agar customer mengetahui penyebab penolakan.
                    </p>

                </div>


                <form
                    method="POST"
                    :action="'{{ url('/admin/transactions') }}/' + rejectId + '/reject'"
                >

                    @csrf

                    <div class="space-y-5 px-6 py-6">

                        {{-- CUSTOMER --}}
                        <div
                            class="rounded-xl
                                   border border-slate-200
                                   bg-slate-50
                                   px-4 py-3"
                        >

                            <p class="text-xs text-slate-400">
                                Customer
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold text-slate-800"
                                x-text="rejectCustomer"
                            ></p>

                        </div>


                        {{-- NOMINAL --}}
                        <div
                            class="rounded-xl
                                   border border-slate-200
                                   bg-slate-50
                                   px-4 py-3"
                        >

                            <p class="text-xs text-slate-400">
                                Nominal
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold text-slate-800"
                                x-text="rejectAmount"
                            ></p>

                        </div>


                        {{-- REASON --}}
                        <div>

                            <label
                                for="reject_reason"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Alasan Penolakan
                            </label>

                            <textarea
                                id="reject_reason"
                                name="reason"
                                rows="4"
                                placeholder="Contoh: Bukti transfer tidak sesuai."
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3
                                       text-sm text-slate-900
                                       outline-none
                                       transition
                                       focus:border-red-400
                                       focus:bg-white
                                       focus:ring-4
                                       focus:ring-red-500/10"
                            ></textarea>

                        </div>

                    </div>


                    <div
                        class="flex justify-end gap-2
                               border-t border-slate-100
                               bg-slate-50
                               px-6 py-4"
                    >

                        <button
                            type="button"
                            @click="openReject = false"
                            class="rounded-xl
                                   border border-slate-200
                                   bg-white
                                   px-4 py-2.5
                                   text-sm font-medium
                                   text-slate-700
                                   hover:bg-slate-50"
                        >
                            Batal
                        </button>


                        <button
                            type="submit"
                            class="rounded-xl
                                   bg-red-600
                                   px-5 py-2.5
                                   text-sm font-semibold
                                   text-white
                                   shadow-lg shadow-red-600/20
                                   transition hover:bg-red-700"
                        >
                            Tolak Top Up
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CONFIRMATION
    ========================================================== --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /*
             * Konfirmasi approve
             */
            document
                .querySelectorAll('.approve-transaction-form')
                .forEach(function (form) {

                    form.addEventListener('submit', function (event) {

                        event.preventDefault();

                        if (window.Swal) {

                            Swal.fire({
                                title: 'Konfirmasi Top Up?',
                                text: 'Saldo customer akan bertambah sesuai nominal transaksi.',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, konfirmasi',
                                cancelButtonText: 'Batal',
                                reverseButtons: true
                            }).then(function (result) {

                                if (result.isConfirmed) {
                                    form.submit();
                                }

                            });

                        } else {

                            if (
                                confirm(
                                    'Konfirmasi top up dan tambahkan saldo customer?'
                                )
                            ) {
                                form.submit();
                            }

                        }

                    });

                });


            /*
             * Konfirmasi delete
             */
            document
                .querySelectorAll('.delete-transaction-form')
                .forEach(function (form) {

                    form.addEventListener('submit', function (event) {

                        event.preventDefault();

                        if (window.Swal) {

                            Swal.fire({
                                title: 'Hapus transaksi?',
                                text: 'Data transaksi akan dihapus.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, hapus',
                                cancelButtonText: 'Batal',
                                reverseButtons: true
                            }).then(function (result) {

                                if (result.isConfirmed) {
                                    form.submit();
                                }

                            });

                        } else {

                            if (confirm('Hapus transaksi ini?')) {
                                form.submit();
                            }

                        }

                    });

                });

        });

    </script>

</x-app-layout>
