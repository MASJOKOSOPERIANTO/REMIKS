<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">
                Customers
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Kelola seluruh akun customer pada sistem VPN.
            </p>
        </div>
    </x-slot>


    <div
        x-data="{
            openCreateCustomer: false,
            openEditCustomer: false,

            searchCustomer: '',

            editCustomerUrl: '',

            editCustomer: {
                name: '',
                email: ''
            },

            openEdit(customer) {
                this.editCustomerUrl = customer.url;
                this.editCustomer.name = customer.name;
                this.editCustomer.email = customer.email;
                this.openEditCustomer = true;
            },

            closeEdit() {
                this.openEditCustomer = false;
                this.editCustomerUrl = '';
                this.editCustomer.name = '';
                this.editCustomer.email = '';
            }
        }"
        class="space-y-6"
    >

        {{-- SUCCESS --}}
        @if(session('success'))

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
        @if($errors->any())

            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4">

                <p class="text-sm font-semibold text-red-800">
                    Terjadi kesalahan
                </p>

                <div class="mt-2 space-y-1 text-sm text-red-700">

                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </div>

            </div>

        @endif


        {{-- SUMMARY --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- TOTAL CUSTOMER --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Total Customer
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900">
                            {{ $customers->count() }}
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
                                  d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>

                            <circle cx="9"
                                    cy="7"
                                    r="4"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M22 21v-2a4 4 0 00-3-3.87"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M16 3.13a4 4 0 010 7.75"/>

                        </svg>

                    </div>

                </div>

            </div>


            {{-- CUSTOMER AKTIF --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Customer Aktif
                        </p>

                        <p class="mt-3 text-3xl font-bold text-emerald-600">
                            {{ $customers->where('is_active', true)->count() }}
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

                            <circle cx="12"
                                    cy="12"
                                    r="9"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M8 12l2.5 2.5L16 9"/>

                        </svg>

                    </div>

                </div>

            </div>


            {{-- CUSTOMER NONAKTIF --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Customer Nonaktif
                        </p>

                        <p class="mt-3 text-3xl font-bold text-red-600">
                            {{ $customers->where('is_active', false)->count() }}
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-red-50 text-red-600">

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
                                  d="M9 9l6 6M15 9l-6 6"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- CUSTOMER TABLE --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- TABLE HEADER --}}
            <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5
                        lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h3 class="text-lg font-semibold text-slate-800">
                        Daftar Customer
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Seluruh customer yang terdaftar pada sistem.
                    </p>

                </div>


                {{-- SEARCH + TAMBAH --}}
                <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">

                    {{-- SEARCH --}}
                    <div class="relative w-full sm:w-80">

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


                        <input
                            type="text"
                            x-model="searchCustomer"
                            placeholder="Cari nama, email, atau ID..."
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


                        {{-- CLEAR SEARCH --}}
                        <button
                            type="button"
                            x-show="searchCustomer.length > 0"
                            x-cloak
                            @click="searchCustomer = ''"
                            class="absolute inset-y-0 right-0
                                   flex items-center pr-3
                                   text-slate-400 transition
                                   hover:text-slate-600"
                        >

                            ✕

                        </button>

                    </div>


                    {{-- TAMBAH CUSTOMER --}}
                    <button
                        type="button"
                        @click="openCreateCustomer = true"
                        class="inline-flex shrink-0 items-center justify-center gap-2
                               rounded-xl bg-blue-600 px-4 py-2.5
                               text-sm font-semibold text-white
                               shadow-lg shadow-blue-600/20
                               transition hover:bg-blue-700"
                    >

                        <span class="text-lg leading-none">
                            +
                        </span>

                        Tambah Customer

                    </button>

                </div>

            </div>


            @if($customers->count() > 0)

                {{-- TABLE --}}
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
                                    Email
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Terdaftar
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold
                                           uppercase tracking-wider text-slate-500">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($customers as $customer)

                                {{-- CUSTOMER ROW --}}
                                <tr
                                    x-show="
                                        searchCustomer === '' ||
                                        '{{ strtolower($customer->name) }}'.includes(searchCustomer.toLowerCase()) ||
                                        '{{ strtolower($customer->email) }}'.includes(searchCustomer.toLowerCase()) ||
                                        '{{ $customer->id }}'.includes(searchCustomer.toLowerCase())
                                    "
                                    class="transition hover:bg-slate-50"
                                >

                                    {{-- NO --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- CUSTOMER --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center
                                                       rounded-full bg-blue-100
                                                       text-sm font-bold text-blue-700"
                                            >

                                                {{ strtoupper(substr($customer->name, 0, 1)) }}

                                            </div>


                                            <div class="min-w-0">

                                                <p class="truncate text-sm font-semibold text-slate-800">
                                                    {{ $customer->name }}
                                                </p>

                                                <p class="text-xs text-slate-400">
                                                    ID #{{ $customer->id }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- EMAIL --}}
                                    <td class="px-6 py-4">

                                        <span class="text-sm text-slate-600">
                                            {{ $customer->email }}
                                        </span>

                                    </td>


                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">

                                        @if($customer->is_active)

                                            <span
                                                class="inline-flex items-center gap-2
                                                       rounded-full bg-emerald-50
                                                       px-3 py-1.5
                                                       text-xs font-semibold
                                                       text-emerald-700"
                                            >

                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                                Aktif

                                            </span>

                                        @else

                                            <span
                                                class="inline-flex items-center gap-2
                                                       rounded-full bg-red-50
                                                       px-3 py-1.5
                                                       text-xs font-semibold
                                                       text-red-700"
                                            >

                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                                Nonaktif

                                            </span>

                                        @endif

                                    </td>


                                    {{-- TERDAFTAR --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">

                                        {{ $customer->created_at->format('d/m/Y') }}

                                        <div class="mt-1 text-xs text-slate-400">
                                            {{ $customer->created_at->format('H:i') }}
                                        </div>

                                    </td>


                                    {{-- AKSI --}}
                                    <td class="px-6 py-4">

                                        <div class="flex justify-end gap-2">

                                            {{-- DETAIL --}}
                                            <a
                                                href="{{ route('admin.customers.show', $customer) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center
                                                       rounded-lg border border-slate-200
                                                       bg-white text-slate-500
                                                       transition hover:bg-slate-50
                                                       hover:text-slate-700"
                                                title="Detail"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M2.46 12a19.1 19.1 0 013.04-4.22A9.77 9.77 0 0112 5c4.14 0 7.64 2.58 9.54 7a19.1 19.1 0 01-3.04 4.22A9.77 9.77 0 0112 19c-4.14 0-7.64-2.58-9.54-7z"
                                                    />

                                                </svg>

                                            </a>


                                            {{-- EDIT --}}
                                            <button
                                                type="button"
                                                @click="openEdit({
                                                    url: '{{ route('admin.customers.update', $customer) }}',
                                                    name: @js($customer->name),
                                                    email: @js($customer->email)
                                                })"
                                                class="inline-flex h-9 w-9 items-center justify-center
                                                       rounded-lg border border-blue-200
                                                       bg-blue-50 text-blue-600
                                                       transition hover:bg-blue-100"
                                                title="Edit"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"
                                                    />

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                                    />

                                                </svg>

                                            </button>


                                            {{-- STATUS --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.customers.toggle-status', $customer) }}"
                                            >

                                                @csrf

                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center
                                                           rounded-lg border
                                                           {{ $customer->is_active
                                                               ? 'border-amber-200 bg-amber-50 text-amber-600 hover:bg-amber-100'
                                                               : 'border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}"
                                                    title="{{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                >

                                                    @if($customer->is_active)

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                        >

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M18.36 6.64a9 9 0 11-12.73 0"
                                                            />

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M12 3v9"
                                                            />

                                                        </svg>

                                                    @else

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                        >

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M15 3h4a2 2 0 012 2v4"
                                                            />

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M10 14L21 3"
                                                            />

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M21 14v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h3"
                                                            />

                                                        </svg>

                                                    @endif

                                                </button>

                                            </form>


                                            {{-- DELETE --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.customers.destroy', $customer) }}"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center
                                                           rounded-lg border border-red-200
                                                           bg-red-50 text-red-600
                                                           transition hover:bg-red-100"
                                                    title="Hapus"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')"
                                                >

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3m-9 0h12"
                                                        />

                                                    </svg>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach


                            {{-- NO SEARCH RESULT --}}
                            <tr
                                x-show="
                                    searchCustomer !== '' &&
                                    ![...$el.parentElement.querySelectorAll('tr[data-customer-row]')].some(row => row.style.display !== 'none')
                                "
                                style="display: none;"
                            >

                                <td
                                    colspan="6"
                                    class="px-6 py-12 text-center text-sm text-slate-500"
                                >
                                    Tidak ada customer yang sesuai dengan pencarian.
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
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"
                            />

                            <circle
                                cx="9"
                                cy="7"
                                r="4"
                            />

                        </svg>

                    </div>


                    <h3 class="mt-5 text-lg font-semibold text-slate-800">
                        Belum Ada Customer
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Belum ada akun customer yang terdaftar.
                    </p>


                    <button
                        type="button"
                        @click="openCreateCustomer = true"
                        class="mt-6 inline-flex items-center gap-2
                               rounded-xl bg-blue-600 px-5 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-blue-700"
                    >

                        <span class="text-lg leading-none">
                            +
                        </span>

                        Tambah Customer

                    </button>

                </div>

            @endif


        </div>


        {{-- MODAL TAMBAH CUSTOMER --}}
        @include('admin.customers.modals.create')


        {{-- MODAL EDIT CUSTOMER --}}
        @include('admin.customers.modals.edit')

    </div>

</x-app-layout>
