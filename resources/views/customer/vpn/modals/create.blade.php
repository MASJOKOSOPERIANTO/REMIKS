<!-- MODAL BUAT VPN -->

<div
    x-show="openCreate"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>

    <!-- OVERLAY -->
    <div
        class="absolute inset-0 bg-black/50"
        @click="openCreate = false"
    ></div>


    <!-- MODAL -->
    <div
        class="relative bg-white w-full max-w-lg mx-4 rounded-2xl shadow-2xl"
        @click.stop
    >

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">

            <div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Buat VPN
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Buat akun VPN baru
                </p>

            </div>


            <button
                type="button"
                @click="openCreate = false"
                class="text-gray-400 hover:text-gray-600 text-2xl leading-none"
            >
                &times;
            </button>

        </div>


        <!-- BODY -->
        <div class="p-6">

            <form
                method="POST"
                action="{{ route('customer.vpn.store') }}"
            >

                @csrf


                <!-- SERVER -->
                <div>

                    <label
                        for="server_id"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Server
                    </label>


                    <select
                        id="server_id"
                        name="server_id"
                        required
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                        <option value="">
                            -- Pilih Server --
                        </option>

                        @foreach($servers as $server)

                            <option
                                value="{{ $server->id }}"
                                {{ old('server_id') == $server->id ? 'selected' : '' }}
                            >
                                {{ $server->name }}
                            </option>

                        @endforeach

                    </select>


                    @error('server_id')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror


                    <!-- INFORMASI HARGA -->
                    <div
                        class="mt-3 flex items-center justify-between
                               rounded-lg border border-blue-100
                               bg-blue-50 px-4 py-3"
                    >

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-9 w-9 items-center justify-center
                                       rounded-lg bg-blue-100
                                       text-blue-600"
                            >
                                Rp
                            </div>

                            <div>

                                <p class="text-xs font-medium text-blue-600">
                                    Harga Pembuatan VPN
                                </p>

                                <p class="mt-0.5 text-sm text-gray-700">
                                    Biaya pembuatan akun VPN
                                </p>

                            </div>

                        </div>


                        <div class="text-right">

                            <p class="text-lg font-bold text-blue-700">
                                Rp2.000
                            </p>

                            <p class="text-[11px] text-gray-400">
                                / akun
                            </p>

                        </div>

                    </div>

                </div>


                <!-- JENIS VPN -->
                <div class="mt-5">

                    <label
                        for="vpn_type"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Jenis VPN
                    </label>


                    <select
                        id="vpn_type"
                        name="vpn_type"
                        required
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    >

                        <option value="">
                            -- Pilih Jenis VPN --
                        </option>

                        <option
                            value="l2tp"
                            {{ old('vpn_type') === 'l2tp' ? 'selected' : '' }}
                        >
                            L2TP
                        </option>

                        <option
                            value="sstp"
                            {{ old('vpn_type') === 'sstp' ? 'selected' : '' }}
                        >
                            SSTP
                        </option>

                    </select>


                    @error('vpn_type')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


               <!-- USERNAME -->
<div class="mt-5">

    <label
        for="username"
        class="block text-sm font-medium text-gray-700 mb-2"
    >
        Username VPN
    </label>

    <input
        type="text"
        id="username"
        name="username"
        value="{{ old('username') }}"
        required
        autocomplete="off"
        placeholder="Masukkan username VPN"
        pattern="^\S+$"
        title="Username tidak boleh mengandung spasi"
        class="w-full rounded-lg border-gray-300
               focus:border-blue-500
               focus:ring-blue-500"
    />

    <p class="mt-1 text-xs text-gray-500">
        Username tidak boleh menggunakan spasi.
        Contoh: <span class="font-medium">vpnuser01</span>
    </p>

    @error('username')
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>


                <!-- PASSWORD -->
                <div class="mt-5">

                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Password VPN
                    </label>


                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Masukkan password VPN"
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    />


                    @error('password')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                <!-- PORT TUJUAN -->
                <div class="mt-5">

                    <label
                        for="to_port"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Port Tujuan
                    </label>


                    <input
                        type="number"
                        id="to_port"
                        name="to_port"
                        value="{{ old('to_port') }}"
                        min="1"
                        max="65535"
                        required
                        placeholder="Contoh: 8291"
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                    />


                    <p class="mt-1 text-xs text-gray-500">
                        Masukkan port API Mikrotik, contoh 8291.
                    </p>


                    @error('to_port')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                <!-- GENERAL ERROR -->
                @error('vpn')

                    <div
                        class="mt-5 p-3 rounded-lg
                               bg-red-50
                               border border-red-200"
                    >

                        <p class="text-sm text-red-600">
                            {{ $message }}
                        </p>

                    </div>

                @enderror


                <!-- FOOTER -->
                <div class="mt-6 flex justify-end gap-3">

                    <button
                        type="button"
                        @click="openCreate = false"
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
                               bg-blue-600
                               text-white
                               font-medium
                               hover:bg-blue-700
                               transition"
                    >
                        Buat VPN
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
