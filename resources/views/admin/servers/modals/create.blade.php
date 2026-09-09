<div
    x-show="openCreate"
    x-cloak
    x-transition
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4"
>

    <div
        @click.outside="openCreate = false"
        class="w-full max-w-lg bg-white rounded-xl shadow-2xl"
    >

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b">

            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    Tambah Server
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Tambahkan server MikroTik
                </p>
            </div>

            <button
                type="button"
                @click="openCreate = false"
                class="text-gray-400 hover:text-gray-700 text-2xl"
            >
                &times;
            </button>

        </div>


        <!-- Form -->
        <form
            action="{{ route('admin.servers.store') }}"
            method="POST"
        >

            @csrf

            <div class="p-6 space-y-4">

                <!-- Nama Server -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Nama Server
                    </label>

                    <input
                        type="text"
                        name="name"
                        required
                        placeholder="Contoh: CHR Jakarta"
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                <!-- Host / IP -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Host / IP
                    </label>

                    <input
                        type="text"
                        name="host"
                        required
                        placeholder="Contoh: 103.xxx.xxx.xxx"
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                <!-- API Port -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        API Port
                    </label>

                    <input
                        type="number"
                        name="api_port"
                        value="8728"
                        required
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                <!-- API User -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        API User
                    </label>

                    <input
                        type="text"
                        name="api_user"
                        required
                        placeholder="Contoh: laravel"
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                <!-- API Password -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        API Password
                    </label>

                    <input
                        type="password"
                        name="api_password"
                        required
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                <!-- Harga VPN -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Harga VPN
                    </label>

                    <div class="relative mt-1">

                        <div
                            class="pointer-events-none absolute inset-y-0 left-0
                                   flex items-center pl-3"
                        >
                            <span class="text-sm font-medium text-gray-500">
                                Rp
                            </span>
                        </div>

                        <input
                            type="text"
                            value="2.000 / akun"
                            readonly
                            class="block w-full rounded-lg
                                   border-gray-300
                                   bg-gray-50
                                   pl-10 pr-4
                                   text-sm font-semibold
                                   text-gray-700
                                   cursor-default"
                        >

                    </div>

                    <p class="mt-1 text-xs text-gray-400">
                        Informasi harga pembuatan akun VPN.
                    </p>

                </div>

            </div>


            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">

                <button
                    type="button"
                    @click="openCreate = false"
                    class="px-4 py-2 rounded-lg border border-gray-300
                           text-gray-700 hover:bg-gray-100"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600
                           text-white hover:bg-blue-700"
                >
                    Simpan Server
                </button>

            </div>

        </form>

    </div>

</div>
