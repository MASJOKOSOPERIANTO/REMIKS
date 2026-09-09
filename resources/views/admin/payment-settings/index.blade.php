<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-xl font-semibold text-slate-900">
                Payment Settings
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Kelola rekening dan metode pembayaran customer.
            </p>
        </div>

    </x-slot>


    <div
        x-data="{
            openCreate: false,
            openEdit: false,

            editData: {
                id: '',
                type: 'bank',
                name: '',
                account_number: '',
                account_name: '',
                status: true
            },

            startEdit(data) {
                this.editData = data;
                this.openEdit = true;
            }
        }"
        class="space-y-6"
    >


        {{-- SUCCESS --}}
        @if (session('success'))

            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center
                               rounded-lg bg-emerald-100 text-emerald-600"
                    >
                        ✓
                    </div>

                    <div>

                        <p class="text-sm font-semibold text-emerald-800">
                            Berhasil
                        </p>

                        <p class="text-sm text-emerald-700">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- ERROR --}}
        @if ($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

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


        {{-- HEADER --}}
        <div
            class="rounded-2xl border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <div
                class="flex flex-col gap-4
                       sm:flex-row sm:items-center
                       sm:justify-between"
            >

                <div>

                    <h1 class="text-lg font-semibold text-slate-900">
                        Metode Pembayaran
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Rekening, QRIS, dan e-wallet yang tersedia untuk customer.
                    </p>

                </div>


                <button
                    type="button"
                    @click="openCreate = true"
                    class="inline-flex items-center justify-center gap-2
                           rounded-xl
                           bg-blue-600
                           px-4 py-2.5
                           text-sm font-semibold text-white
                           shadow-lg shadow-blue-600/20
                           transition hover:bg-blue-700"
                >

                    <span class="text-lg leading-none">
                        +
                    </span>

                    Tambah Pembayaran

                </button>

            </div>

        </div>


        {{-- =========================================================
            PAYMENT TABLE
        ========================================================== --}}
        <div
            class="overflow-hidden rounded-2xl
                   border border-slate-200
                   bg-white shadow-sm"
        >

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                No
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Nama
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Jenis
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Rekening / Tujuan
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                Atas Nama
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold text-slate-600">
                                QRIS
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

                        @forelse ($paymentSettings as $index => $payment)

                            <tr class="transition hover:bg-slate-50">

                                {{-- NO --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                    {{ $index + 1 }}
                                </td>


                                {{-- NAME --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="font-semibold text-slate-900">
                                        {{ $payment->name }}
                                    </div>

                                </td>


                                {{-- TYPE --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    @if ($payment->type === 'qris')

                                        <span
                                            class="inline-flex rounded-lg
                                                   border border-purple-200
                                                   bg-purple-50
                                                   px-2.5 py-1
                                                   text-xs font-semibold
                                                   text-purple-700"
                                        >
                                            QRIS
                                        </span>

                                    @elseif ($payment->type === 'ewallet')

                                        <span
                                            class="inline-flex rounded-lg
                                                   border border-emerald-200
                                                   bg-emerald-50
                                                   px-2.5 py-1
                                                   text-xs font-semibold
                                                   text-emerald-700"
                                        >
                                            E-Wallet
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex rounded-lg
                                                   border border-blue-200
                                                   bg-blue-50
                                                   px-2.5 py-1
                                                   text-xs font-semibold
                                                   text-blue-700"
                                        >
                                            Bank
                                        </span>

                                    @endif

                                </td>


                                {{-- ACCOUNT --}}
                                <td class="px-5 py-4">

                                    @if ($payment->account_number)

                                        <span class="font-mono text-sm text-slate-800">
                                            {{ $payment->account_number }}
                                        </span>

                                    @else

                                        <span class="text-slate-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- ACCOUNT NAME --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    @if ($payment->account_name)

                                        <span class="text-slate-800">
                                            {{ $payment->account_name }}
                                        </span>

                                    @else

                                        <span class="text-slate-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- QRIS --}}
                                <td class="px-5 py-4">

                                    @if ($payment->qris_image)

                                        <a
                                            href="{{ asset('storage/' . $payment->qris_image) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2"
                                        >

                                            <img
                                                src="{{ asset('storage/' . $payment->qris_image) }}"
                                                alt="QRIS"
                                                class="h-12 w-12 rounded-lg
                                                       border border-slate-200
                                                       bg-white
                                                       object-contain"
                                            >

                                            <span
                                                class="text-xs font-medium
                                                       text-blue-600
                                                       hover:text-blue-700"
                                            >
                                                Lihat
                                            </span>

                                        </a>

                                    @else

                                        <span class="text-slate-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    @if ($payment->status)

                                        <span
                                            class="inline-flex rounded-lg
                                                   border border-emerald-200
                                                   bg-emerald-50
                                                   px-2.5 py-1
                                                   text-xs font-semibold
                                                   text-emerald-700"
                                        >
                                            Aktif
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex rounded-lg
                                                   border border-slate-200
                                                   bg-slate-50
                                                   px-2.5 py-1
                                                   text-xs font-semibold
                                                   text-slate-500"
                                        >
                                            Nonaktif
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="px-5 py-4">

                                    <div
                                        class="flex flex-wrap
                                               justify-end gap-2"
                                    >

                                        {{-- EDIT --}}
                                        <button
                                            type="button"
                                            @click="startEdit({
                                                id: {{ $payment->id }},
                                                type: @js($payment->type),
                                                name: @js($payment->name),
                                                account_number: @js($payment->account_number),
                                                account_name: @js($payment->account_name),
                                                status: {{ $payment->status ? 'true' : 'false' }}
                                            })"
                                            class="rounded-lg
                                                   border border-slate-200
                                                   bg-white
                                                   px-3 py-2
                                                   text-xs font-medium
                                                   text-slate-700
                                                   transition hover:bg-slate-50"
                                        >
                                            Edit
                                        </button>


                                        {{-- TOGGLE --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.payment-settings.toggle', $payment) }}"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="rounded-lg
                                                       border px-3 py-2
                                                       text-xs font-medium
                                                       transition
                                                       {{ $payment->status
                                                            ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100'
                                                            : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                            >
                                                {{ $payment->status ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>

                                        </form>


                                        {{-- DELETE --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.payment-settings.destroy', $payment) }}"
                                            class="delete-payment-form"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg
                                                       border border-red-200
                                                       bg-red-50
                                                       px-3 py-2
                                                       text-xs font-medium
                                                       text-red-700
                                                       transition hover:bg-red-100"
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
                                    colspan="8"
                                    class="px-6 py-16 text-center"
                                >

                                    <p class="text-base font-semibold text-slate-700">
                                        Belum ada metode pembayaran
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Tambahkan rekening bank, QRIS, atau e-wallet.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
            CREATE MODAL
        ========================================================== --}}
        <div
            x-show="openCreate"
            x-cloak
            class="fixed inset-0 z-50
                   flex items-center justify-center
                   bg-slate-900/50
                   px-4 py-6"
        >

            <div
                @click.outside="openCreate = false"
                class="w-full max-w-lg overflow-hidden
                       rounded-2xl bg-white shadow-2xl"
            >

                <div
                    class="flex items-center justify-between
                           border-b border-slate-100
                           px-6 py-5"
                >

                    <div>

                        <h3 class="text-lg font-semibold text-slate-900">
                            Tambah Pembayaran
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Tambahkan rekening, QRIS, atau e-wallet.
                        </p>

                    </div>

                    <button
                        type="button"
                        @click="openCreate = false"
                        class="rounded-lg px-3 py-2
                               text-slate-400
                               hover:bg-slate-100
                               hover:text-slate-600"
                    >
                        ✕
                    </button>

                </div>


                <form
                    method="POST"
                    action="{{ route('admin.payment-settings.store') }}"
                    enctype="multipart/form-data"
                >

                    @csrf

                    <div class="space-y-5 px-6 py-6">

                        <div>

                            <label
                                for="create_type"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Jenis Pembayaran
                            </label>

                            <select
                                id="create_type"
                                name="type"
                                required
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3 text-sm"
                            >

                                <option value="">
                                    Pilih jenis pembayaran
                                </option>

                                <option value="bank">
                                    Bank
                                </option>

                                <option value="qris">
                                    QRIS
                                </option>

                                <option value="ewallet">
                                    E-Wallet
                                </option>

                            </select>

                        </div>


                        <div>

                            <label
                                for="create_name"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Nama Pembayaran
                            </label>

                            <input
                                id="create_name"
                                name="name"
                                type="text"
                                required
                                placeholder="Contoh: BRI"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3 text-sm"
                            >

                        </div>


                        <div>

                            <label
                                for="create_account_number"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Nomor Rekening / Tujuan
                            </label>

                            <input
                                id="create_account_number"
                                name="account_number"
                                type="text"
                                placeholder="Contoh: 1234567890"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3 text-sm"
                            >

                        </div>


                        <div>

                            <label
                                for="create_account_name"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Atas Nama
                            </label>

                            <input
                                id="create_account_name"
                                name="account_name"
                                type="text"
                                placeholder="Contoh: VPN PANEL"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3 text-sm"
                            >

                        </div>


                        <div>

                            <label
                                for="create_qris_image"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Gambar QRIS
                            </label>

                            <input
                                id="create_qris_image"
                                name="qris_image"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50 p-2
                                       text-sm"
                            >

                            <p class="mt-1 text-xs text-slate-400">
                                JPG, JPEG, PNG atau WEBP — maksimal 5 MB.
                            </p>

                        </div>


                        <label class="flex items-center gap-3">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                checked
                                class="h-4 w-4 rounded
                                       border-slate-300
                                       text-blue-600"
                            >

                            <span class="text-sm text-slate-700">
                                Aktifkan metode pembayaran
                            </span>

                        </label>

                    </div>


                    <div
                        class="flex justify-end gap-2
                               border-t border-slate-100
                               bg-slate-50
                               px-6 py-4"
                    >

                        <button
                            type="button"
                            @click="openCreate = false"
                            class="rounded-xl
                                   border border-slate-200
                                   bg-white
                                   px-4 py-2.5
                                   text-sm font-medium
                                   text-slate-700"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="rounded-xl
                                   bg-blue-600
                                   px-4 py-2.5
                                   text-sm font-semibold
                                   text-white
                                   hover:bg-blue-700"
                        >
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- =========================================================
            EDIT MODAL
        ========================================================== --}}
        <div
            x-show="openEdit"
            x-cloak
            class="fixed inset-0 z-50
                   flex items-center justify-center
                   bg-slate-900/50
                   px-4 py-6"
        >

            <div
                @click.outside="openEdit = false"
                class="w-full max-w-lg overflow-hidden
                       rounded-2xl bg-white shadow-2xl"
            >

                <div
                    class="flex items-center justify-between
                           border-b border-slate-100
                           px-6 py-5"
                >

                    <div>

                        <h3 class="text-lg font-semibold text-slate-900">
                            Edit Pembayaran
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Perbarui informasi pembayaran.
                        </p>

                    </div>

                    <button
                        type="button"
                        @click="openEdit = false"
                        class="rounded-lg px-3 py-2
                               text-slate-400
                               hover:bg-slate-100
                               hover:text-slate-600"
                    >
                        ✕
                    </button>

                </div>


                <form
                    method="POST"
                    :action="'{{ url('/admin/payment-settings') }}/' + editData.id"
                    enctype="multipart/form-data"
                >

                    @csrf

                    @method('PUT')

                    <div class="space-y-5 px-6 py-6">

                        <div>

                            <label
                                for="edit_type"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Jenis Pembayaran
                            </label>

                            <select
                                id="edit_type"
                                name="type"
                                x-model="editData.type"
                                required
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3 text-sm"
                            >

                                <option value="bank">
                                    Bank
                                </option>

                                <option value="qris">
                                    QRIS
                                </option>

                                <option value="ewallet">
                                    E-Wallet
                                </option>

                            </select>

                        </div>


                        <div>

                            <label
                                for="edit_name"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Nama Pembayaran
                            </label>

                            <input
                                id="edit_name"
                                name="name"
                                type="text"
                                x-model="editData.name"
                                required
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3 text-sm"
                            >

                        </div>


                        <div>

                            <label
                                for="edit_account_number"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Nomor Rekening / Tujuan
                            </label>

                            <input
                                id="edit_account_number"
                                name="account_number"
                                type="text"
                                x-model="editData.account_number"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3 text-sm"
                            >

                        </div>


                        <div>

                            <label
                                for="edit_account_name"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Atas Nama
                            </label>

                            <input
                                id="edit_account_name"
                                name="account_name"
                                type="text"
                                x-model="editData.account_name"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50
                                       px-4 py-3 text-sm"
                            >

                        </div>


                        <div>

                            <label
                                for="edit_qris_image"
                                class="block text-sm font-medium text-slate-700"
                            >
                                Ganti Gambar QRIS
                            </label>

                            <input
                                id="edit_qris_image"
                                name="qris_image"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="mt-2 w-full rounded-xl
                                       border border-slate-300
                                       bg-slate-50 p-2
                                       text-sm"
                            >

                            <p class="mt-1 text-xs text-slate-400">
                                Kosongkan jika tidak ingin mengganti QRIS.
                            </p>

                        </div>


                        <label class="flex items-center gap-3">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                x-model="editData.status"
                                class="h-4 w-4 rounded
                                       border-slate-300
                                       text-blue-600"
                            >

                            <span class="text-sm text-slate-700">
                                Metode pembayaran aktif
                            </span>

                        </label>

                    </div>


                    <div
                        class="flex justify-end gap-2
                               border-t border-slate-100
                               bg-slate-50
                               px-6 py-4"
                    >

                        <button
                            type="button"
                            @click="openEdit = false"
                            class="rounded-xl
                                   border border-slate-200
                                   bg-white
                                   px-4 py-2.5
                                   text-sm font-medium
                                   text-slate-700"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="rounded-xl
                                   bg-blue-600
                                   px-4 py-2.5
                                   text-sm font-semibold
                                   text-white
                                   hover:bg-blue-700"
                        >
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELETE CONFIRMATION
    ========================================================== --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            document
                .querySelectorAll('.delete-payment-form')
                .forEach(function (form) {

                    form.addEventListener('submit', function (event) {

                        event.preventDefault();

                        if (window.Swal) {

                            Swal.fire({
                                title: 'Hapus metode pembayaran?',
                                text: 'Data pembayaran ini akan dihapus.',
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

                            if (confirm('Hapus metode pembayaran ini?')) {
                                form.submit();
                            }

                        }

                    });

                });

        });

    </script>

</x-app-layout>
