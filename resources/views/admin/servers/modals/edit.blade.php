<div
    x-show="openEdit"
    x-cloak
    x-transition
    class="fixed inset-0 z-[9999] flex items-center justify-center
           bg-black/60 p-4"
>

    <div
        @click.outside="openEdit = false"
        class="w-full max-w-lg bg-white rounded-xl shadow-2xl"
    >

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">

            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    Edit Server
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Perbarui informasi server MikroTik
                </p>
            </div>

            <button
                type="button"
                @click="openEdit = false"
                class="text-gray-400 hover:text-gray-700 text-2xl"
            >
                &times;
            </button>

        </div>


        <!-- FORM -->
        <form
            method="POST"
            :action="'/admin/servers/' + editServer.id"
        >

            @csrf
            @method('PUT')


            <div class="p-6 space-y-4">

                <!-- NAMA SERVER -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Nama Server
                    </label>

                    <input
                        type="text"
                        name="name"
                        x-model="editServer.name"
                        required
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                </div>


                <!-- HOST -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Host / IP
                    </label>

                    <input
                        type="text"
                        name="host"
                        x-model="editServer.host"
                        required
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                </div>


                <!-- API PORT -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        API Port
                    </label>

                    <input
                        type="number"
                        name="api_port"
                        x-model="editServer.api_port"
                        required
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                </div>


                <!-- API USER -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        API User
                    </label>

                    <input
                        type="text"
                        name="api_user"
                        x-model="editServer.api_user"
                        required
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                </div>


                <!-- PASSWORD -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        API Password Baru
                    </label>

                    <input
                        type="password"
                        name="api_password"
                        x-model="editServer.api_password"
                        class="mt-1 block w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                    <p class="mt-1 text-xs text-gray-400">
                        Kosongkan jika password tidak ingin diubah.
                    </p>

                </div>


                <!-- HARGA VPN -->
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
                            tabindex="-1"
                            class="block w-full rounded-lg
                                   border-gray-300
                                   bg-gray-50
                                   pl-10 pr-4
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   cursor-default"
                        >

                    </div>

                    <p class="mt-1 text-xs text-gray-400">
                        Informasi harga pembuatan akun VPN.
                    </p>

                </div>

            </div>


            <!-- FOOTER -->
            <div
                class="flex justify-end gap-3
                       px-6 py-4
                       border-t
                       bg-gray-50"
            >

                <button
                    type="button"
                    @click="openEdit = false"
                    class="px-4 py-2 rounded-lg
                           border border-gray-300
                           text-gray-700
                           hover:bg-gray-100"
                >
                    Batal
                </button>


                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg
                           bg-blue-600
                           text-white
                           hover:bg-blue-700"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>
