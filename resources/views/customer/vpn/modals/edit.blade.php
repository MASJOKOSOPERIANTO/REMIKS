<!-- MODAL EDIT VPN -->
<div
    x-show="openEdit"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>

    <!-- OVERLAY -->
    <div
        class="absolute inset-0 bg-black/50"
        @click="openEdit = false"
    ></div>

    <!-- MODAL -->
    <div
        class="relative w-full max-w-lg mx-4 bg-white rounded-2xl shadow-2xl"
        @click.stop
    >

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">

            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Edit VPN
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Ubah informasi akun VPN
                </p>
            </div>

            <button
                type="button"
                @click="openEdit = false"
                class="text-2xl leading-none text-gray-400 hover:text-gray-600"
            >
                &times;
            </button>

        </div>

        <!-- BODY -->
        <div class="p-6">

            <form
                method="POST"
                :action="'{{ url('/customer/vpn') }}/' + editVpn.id"
            >

                @csrf
                @method('PUT')

                <!-- SERVER -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Server
                    </label>

                    <input
                        type="text"
                        x-model="editVpn.server"
                        readonly
                        class="w-full rounded-lg border-gray-300 bg-gray-100
                               text-gray-600 cursor-not-allowed"
                    >
                </div>

                <!-- JENIS VPN -->
                <div class="mt-5">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Jenis VPN
                    </label>

                    <input
                        type="text"
                        x-model="editVpn.vpn_type"
                        readonly
                        class="w-full rounded-lg border-gray-300 bg-gray-100
                               text-gray-600 cursor-not-allowed uppercase"
                    >
                </div>

                <!-- USERNAME -->
                <div class="mt-5">
                    <label
                        for="edit_username"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        Username VPN
                    </label>

                    <input
                        type="text"
                        id="edit_username"
                        name="username"
                        x-model="editVpn.username"
                        required
                        autocomplete="off"
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>

                <!-- PASSWORD -->
                <div class="mt-5">
                    <label
                        for="edit_password"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        Password VPN
                    </label>

                    <input
                        type="password"
                        id="edit_password"
                        name="password"
                        x-model="editVpn.password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>

                <!-- PORT TUJUAN -->
                <div class="mt-5">
                    <label
                        for="edit_to_port"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        Port Tujuan
                    </label>

                    <input
                        type="number"
                        id="edit_to_port"
                        name="to_port"
                        x-model="editVpn.to_port"
                        min="1"
                        max="65535"
                        required
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                    <p class="mt-1 text-xs text-gray-500">
                        Masukkan port tujuan MikroTik.
                    </p>
                </div>

                <!-- FOOTER -->
                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        @click="openEdit = false"
                        class="px-4 py-2.5 rounded-lg
                               border border-gray-300
                               text-gray-700
                               hover:bg-gray-50
                               transition"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-lg
                               bg-blue-600 text-white font-medium
                               hover:bg-blue-700
                               transition"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
