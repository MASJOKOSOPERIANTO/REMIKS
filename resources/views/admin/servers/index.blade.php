<x-app-layout>

    <div

        x-data="{
            openCreate: false,
            openEdit: false,
            searchServer: '',

            editServer: {
                id: null,
                name: '',
                host: '',
                api_port: 8728,
                api_user: '',
                api_password: ''
            },

            get filteredServers() {
                return this.searchServer.trim().toLowerCase();
            }
        }"

    >

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Card -->

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <!-- Header Table -->

                <div class="px-6 py-5 border-b border-gray-200">

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                        <!-- Judul -->

                        <div>

                            <h3 class="text-lg font-semibold text-gray-800">

                                Daftar Server

                            </h3>

                            <p class="text-sm text-gray-500 mt-1">

                                Server MikroTik yang terdaftar

                            </p>

                        </div>

                        <!-- Search + Tambah -->

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                            <!-- PENCARIAN -->

                            <div class="relative w-full sm:w-80">

                                <!-- Icon Search -->

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                    <svg

                                        class="w-5 h-5 text-gray-400"

                                        fill="none"

                                        stroke="currentColor"

                                        viewBox="0 0 24 24"

                                    >

                                        <path

                                            stroke-linecap="round"

                                            stroke-linejoin="round"

                                            stroke-width="2"

                                            d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"

                                        />

                                    </svg>

                                </div>

                                <input

                                    type="text"

                                    x-model="searchServer"

                                    placeholder="Cari server, host / IP..."

                                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200

                                           rounded-xl text-sm text-slate-700 placeholder-slate-400

                                           focus:outline-none focus:ring-2 focus:ring-blue-500/20

                                           focus:border-blue-500 transition"

                                >

                                <!-- Tombol Clear -->

                                <button

                                    type="button"

                                    x-show="searchServer !== ''"



                                    @click="searchServer = ''"

                                    class="absolute inset-y-0 right-0 pr-3 flex items-center

                                           text-gray-400 hover:text-gray-600 transition"

                                >

                                    <svg

                                        class="w-4 h-4"

                                        fill="none"

                                        stroke="currentColor"

                                        viewBox="0 0 24 24"

                                    >

                                        <path

                                            stroke-linecap="round"

                                            stroke-linejoin="round"

                                            stroke-width="2"

                                            d="M6 6l12 12M18 6 6 18"

                                        />

                                    </svg>

                                </button>

                            </div>

                            <!-- Tombol Tambah -->

                            <button

                                type="button"

                                @click="openCreate = true"

                                class="inline-flex items-center justify-center px-4 py-2.5

                                       rounded-xl bg-blue-600 text-white

                                       font-medium text-sm

                                       hover:bg-blue-700 transition

                                       whitespace-nowrap"

                            >

                                + Tambah Server

                            </button>

                        </div>

                    </div>

                </div>



                <!-- Tabel -->

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <!-- NO -->

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">

                                    No.

                                </th>





                                <!-- NAMA -->

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">

                                    Nama Server

                                </th>

                                <!-- HOST -->

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">

                                    Host / IP

                                </th>

                                <!-- API PORT -->

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">

                                    API Port

                                </th>

                                <!-- STATUS -->

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">

                                    Status

                                </th>

                                <!-- KONEKSI -->
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                    Koneksi
                                </th>

                                <!-- AKSI -->

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">

                                    Aksi

                                </th>

                            </tr>

                        </thead>



                        <tbody>

                            @forelse($servers as $index => $server)

                                @php

                                    $searchText = strtolower(

                                        $server->name . ' ' .

                                        $server->host . ' ' .

                                        $server->api_port . ' ' .

                                        ($server->status ? 'aktif active' : 'nonaktif inactive')

                                    );

                                @endphp

                                <tr

                                    class="border-b hover:bg-gray-50"

                                    x-show="

                                        searchServer === '' ||

                                        @js($searchText).includes(searchServer.toLowerCase())

                                    "

                                >

                                    <!-- NO -->

                                    <td class="px-6 py-4 text-sm text-gray-700">

                                        {{ $index + 1 }}

                                    </td>





                                    <!-- NAMA SERVER -->

                                    <td class="px-6 py-4 font-semibold text-gray-800">

                                        {{ $server->name }}

                                    </td>

                                    <!-- HOST -->

                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        {{ $server->host }}

                                    </td>

                                    <!-- API PORT -->

                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        {{ $server->api_port }}

                                    </td>

                                    <!-- STATUS -->

                                    <td class="px-6 py-4">

                                        @if($server->status)

                                            <span

                                                class="px-2.5 py-1 text-xs rounded-full

                                                       bg-green-100 text-green-700"

                                            >

                                                Aktif

                                            </span>

                                        @else

                                            <span

                                                class="px-2.5 py-1 text-xs rounded-full

                                                       bg-red-100 text-red-700"

                                            >

                                                Nonaktif

                                            </span>

                                        @endif

                                    </td>
                                    <!-- KONEKSI -->
                                    <td class="px-6 py-4">
                                        <div
                                            id="server-monitor-{{ $server->id }}"
                                            data-monitor-url="{{ route('admin.servers.monitor', $server->id) }}"
                                            class="inline-flex items-center gap-2 px-2.5 py-1 text-xs rounded-full bg-gray-100 text-gray-500"
                                        >
                                            <span class="monitor-dot w-2 h-2 rounded-full bg-gray-400 animate-pulse"></span>
                                            <span class="monitor-label">Memeriksa...</span>
                                        </div>
                                        <div
                                            id="server-monitor-check-{{ $server->id }}"
                                            class="mt-1 text-[10px] text-gray-400"
                                        >
                                            Menghubungkan...
                                        </div>
                                    </td>

                                    <!-- AKSI -->
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-2">

                                            <!-- MONITOR -->
                                            <button
                                                type="button"
                                                onclick="openServerMonitor({{ $server->id }})"
                                                class="inline-flex items-center px-3 py-1.5
                                                       rounded-md text-xs font-medium
                                                       bg-indigo-50 text-indigo-600
                                                       hover:bg-indigo-100
                                                       transition"
                                            >
                                                Monitor
                                            </button>

                                            <!-- EDIT -->

                                            <button

                                                type="button"

                                                @click="
                                                editServer = {

                                                        id: {{ $server->id }},

                                                        name: @js($server->name),

                                                        host: @js($server->host),

                                                        api_port: {{ $server->api_port }},

                                                        api_user: @js($server->api_user),

                                                        api_password: ''

                                                    };
                                                openEdit = true;
                                            "

                                                class="inline-flex items-center px-3 py-1.5

                                                       rounded-md text-xs font-medium

                                                       bg-blue-50 text-blue-600

                                                       hover:bg-blue-100

                                                       transition"

                                            >

                                                Edit

                                            </button>



                                            <!-- HAPUS -->

                                            <form

                                                action="{{ route('admin.servers.destroy', $server->id) }}"

                                                method="POST"

                                                class="delete-server-form inline"

                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button

                                                    type="submit"

                                                    class="inline-flex items-center px-3 py-1.5

                                                           rounded-md text-xs font-medium

                                                           bg-red-50 text-red-600

                                                           hover:bg-red-100 transition"

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

                                        class="px-6 py-12 text-center text-gray-500"

                                    >

                                        Belum ada data server.

                                    </td>

                                </tr>

                            @endforelse



                            <!-- Tidak ditemukan -->

                            @if($servers->count() > 0)

                                <tr

                                    x-show="searchServer !== ''"



                                >

                                    <td

                                        colspan="7"

                                        class="px-6 py-10 text-center text-gray-500"

                                    >

                                        <div class="flex flex-col items-center justify-center">

                                            <svg

                                                class="w-10 h-10 text-gray-300 mb-3"

                                                fill="none"

                                                stroke="currentColor"

                                                viewBox="0 0 24 24"

                                            >

                                                <path

                                                    stroke-linecap="round"

                                                    stroke-linejoin="round"

                                                    stroke-width="1.5"

                                                    d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"

                                                />

                                            </svg>

                                            <p class="text-sm">

                                                Tidak ada server yang cocok dengan pencarian.

                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>

            </div>

        </div>



        <!-- Modal Tambah -->

        @include('admin.servers.modals.create')

        <!-- Modal Edit -->

        @include('admin.servers.modals.edit')

    </div>
    <!-- Modal Monitoring Server -->
    <div
        id="server-monitor-modal"
        class="fixed inset-0 z-[60] hidden items-center justify-center p-4"
        aria-hidden="true"
    >
        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            onclick="closeServerMonitor()"
        ></div>

        <div
            class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden"
            onclick="event.stopPropagation()"
        >
            <!-- Header -->
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M3 12h4l3-8 4 16 3-8h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">
                                    Monitoring Server
                                </h3>
                                <p id="monitor-server-name" class="text-sm text-slate-500">
                                    Memuat...
                                </p>
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        onclick="closeServerMonitor()"
                        class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-white transition"
                        aria-label="Tutup"
                    >
                        <svg class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 6l12 12M18 6 6 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Loading -->
            <div id="monitor-loading" class="p-8">
                <div class="py-10 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 mb-4">
                        <svg class="w-6 h-6 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"/>
                            <path opacity=".9" fill="currentColor"
                                  d="M4 12a8 8 0 0 1 8-8v3a5 5 0 0 0-5 5H4z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Mengambil data MikroTik...</p>
                    <p class="text-xs text-slate-400 mt-1">Mohon tunggu sebentar</p>
                </div>
            </div>

            <!-- Offline -->
            <div id="monitor-offline" class="hidden p-8">
                <div class="py-8 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-100 text-red-600 mb-4">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v4m0 4h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.75 3h14.9a2 2 0 0 0 1.75 0Z"/>
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold text-slate-800">Server Offline</h4>
                    <p id="monitor-offline-message" class="text-sm text-slate-500 mt-1">
                        Server tidak dapat terhubung.
                    </p>

                    <button
                        type="button"
                        onclick="refreshServerMonitor()"
                        class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition"
                    >
                        Coba Lagi
                    </button>
                </div>
            </div>

            <!-- Data -->
            <div id="monitor-data" class="hidden p-6 max-h-[70vh] overflow-y-auto">
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl border border-green-200 bg-green-50">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <div>
                            <p class="text-sm font-semibold text-green-800">Online</p>
                            <p class="text-xs text-green-700">API RouterOS berhasil terhubung</p>
                        </div>
                    </div>

                    <button
                        type="button"
                        onclick="refreshServerMonitor()"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-green-200 text-green-700 text-xs font-medium hover:bg-green-100 transition"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h5M20 20v-5h-5M19 9a7 7 0 0 0-12.9-3.9L4 9m16 6-2.1 3.9A7 7 0 0 1 5 15"/>
                        </svg>
                        Refresh
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Identity</p>
                        <p id="monitor-identity" class="mt-2 text-base font-semibold text-slate-800">-</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">RouterOS Version</p>
                        <p id="monitor-version" class="mt-2 text-base font-semibold text-slate-800">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Uptime</p>
                        <p id="monitor-uptime" class="mt-2 text-base font-semibold text-slate-800">-</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 pr-4">
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">CPU Load</p>
                                <p id="monitor-cpu-text" class="mt-2 text-xl font-bold text-slate-800">-</p>
                                <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div id="monitor-cpu-bar"
                                         class="h-full rounded-full bg-blue-500 transition-all duration-500"
                                         style="width: 0%"></div>
                                </div>
                            </div>

                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M5 19V9m7 10V5m7 14v-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 p-4 mt-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Memory</p>
                            <p id="monitor-memory-text" class="mt-2 text-sm font-semibold text-slate-800">-</p>
                        </div>
                        <p id="monitor-memory-percent" class="text-sm font-semibold text-blue-600">0%</p>
                    </div>

                    <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                        <div
                            id="monitor-memory-bar"
                            class="h-full rounded-full bg-blue-500 transition-all duration-500"
                            style="width: 0%"
                        ></div>
                    </div>

                    <div class="flex justify-between mt-2 text-xs text-slate-400">
                        <span>Terpakai</span>
                        <span>Bebas: <span id="monitor-memory-free">-</span></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">Board</p>
                        <p id="monitor-board" class="mt-1 text-sm font-semibold text-slate-700">-</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">CPU</p>
                        <p id="monitor-cpu" class="mt-1 text-sm font-semibold text-slate-700">-</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-400">Arsitektur</p>
                        <p id="monitor-architecture" class="mt-1 text-sm font-semibold text-slate-700">-</p>
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 mt-5 text-right">
                    Update terakhir: <span id="monitor-checked-at">-</span>
                </p>
            </div>
        </div>
    </div>

<!-- Monitoring Server MikroTik -->
    <script>
        function formatMemory(bytes) {
            if (bytes === null || bytes === undefined || bytes === '' || isNaN(Number(bytes))) {
                return '-';
            }

            const value = Number(bytes);

            if (value >= 1024 * 1024 * 1024) {
                return (value / (1024 * 1024 * 1024)).toFixed(2) + ' GB';
            }

            if (value >= 1024 * 1024) {
                return (value / (1024 * 1024)).toFixed(2) + ' MB';
            }

            if (value >= 1024) {
                return (value / 1024).toFixed(2) + ' KB';
            }

            return value + ' B';
        }

        let activeMonitorServerId = null;

        function showMonitorSection(section) {
            const loading = document.getElementById('monitor-loading');
            const offline = document.getElementById('monitor-offline');
            const data = document.getElementById('monitor-data');

            loading.classList.add('hidden');
            offline.classList.add('hidden');
            data.classList.add('hidden');

            if (section === 'loading') {
                loading.classList.remove('hidden');
            }

            if (section === 'offline') {
                offline.classList.remove('hidden');
            }

            if (section === 'data') {
                data.classList.remove('hidden');
            }
        }

        function openServerMonitor(id) {
            activeMonitorServerId = id;

            const modal = document.getElementById('server-monitor-modal');

            if (!modal) {
                console.error('Modal monitoring tidak ditemukan.');
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');

            document.body.classList.add('overflow-hidden');

            document.getElementById('monitor-server-name').textContent = 'Memuat...';

            showMonitorSection('loading');

            loadServerMonitor(id);
        }

        function closeServerMonitor() {
            const modal = document.getElementById('server-monitor-modal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');

            document.body.classList.remove('overflow-hidden');
        }

        function refreshServerMonitor() {
            if (activeMonitorServerId !== null) {
                loadServerMonitor(activeMonitorServerId);
            }
        }

        async function loadServerMonitor(id) {
            showMonitorSection('loading');

            const statusElement = document.getElementById('server-monitor-' + id);

            const url = statusElement
                ? statusElement.dataset.monitorUrl
                : '/admin/servers/' + id + '/monitor';

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-store'
                });

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const result = await response.json();

                if (result.success && result.status === 'online') {
                    updateMonitorModal(result);
                    showMonitorSection('data');
                } else {
                    document.getElementById('monitor-offline-message').textContent =
                        result.message || 'Server tidak dapat terhubung.';

                    showMonitorSection('offline');
                }

            } catch (error) {
                console.error('Monitoring server error:', error);

                document.getElementById('monitor-offline-message').textContent =
                    'Gagal mengambil data monitoring server.';

                showMonitorSection('offline');
            }
        }

        function updateMonitorModal(result) {
            const server = result.server || {};
            const mikrotik = result.mikrotik || {};

            document.getElementById('monitor-server-name').textContent =
                server.name || 'Server MikroTik';

            document.getElementById('monitor-identity').textContent =
                mikrotik.identity || '-';

            document.getElementById('monitor-version').textContent =
                mikrotik.version || '-';

            document.getElementById('monitor-uptime').textContent =
                mikrotik.uptime || '-';

            const cpuLoad = Number(mikrotik.cpu_load);

            document.getElementById('monitor-cpu-text').textContent =
                isNaN(cpuLoad) ? '-' : cpuLoad + '%';

            document.getElementById('monitor-cpu-bar').style.width =
                (isNaN(cpuLoad) ? 0 : Math.min(Math.max(cpuLoad, 0), 100)) + '%';

            const totalMemory = Number(mikrotik.total_memory || 0);
            const freeMemory = Number(mikrotik.free_memory || 0);

            let memoryPercent = 0;

            if (totalMemory > 0 && freeMemory >= 0 && freeMemory <= totalMemory) {
                memoryPercent = Math.round(((totalMemory - freeMemory) / totalMemory) * 100);
            }

            document.getElementById('monitor-memory-text').textContent =
                formatMemory(totalMemory - freeMemory) +
                ' / ' +
                formatMemory(totalMemory);

            document.getElementById('monitor-memory-percent').textContent =
                memoryPercent + '%';

            document.getElementById('monitor-memory-bar').style.width =
                memoryPercent + '%';

            document.getElementById('monitor-memory-free').textContent =
                formatMemory(freeMemory);

            document.getElementById('monitor-board').textContent =
                mikrotik.board_name || '-';

            document.getElementById('monitor-cpu').textContent =
                mikrotik.cpu || '-';

            document.getElementById('monitor-architecture').textContent =
                mikrotik.architecture_name || '-';

            document.getElementById('monitor-checked-at').textContent =
                new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const monitorElements = document.querySelectorAll('[id^="server-monitor-"]');

            async function checkConnection(container) {
                const id = container.id.replace('server-monitor-', '');
                const label = container.querySelector('.monitor-label');
                const dot = container.querySelector('.monitor-dot');
                const checkText = document.getElementById('server-monitor-check-' + id);

                try {
                    const response = await fetch(container.dataset.monitorUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        cache: 'no-store'
                    });

                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }

                    const result = await response.json();

                    container.classList.remove(
                        'bg-gray-100', 'text-gray-500',
                        'bg-green-100', 'text-green-700',
                        'bg-red-100', 'text-red-700'
                    );

                    dot.classList.remove(
                        'bg-gray-400',
                        'bg-green-500',
                        'bg-red-500',
                        'animate-pulse'
                    );

                    if (result.status === 'online') {
                        container.classList.add('bg-green-100', 'text-green-700');
                        dot.classList.add('bg-green-500');
                        label.textContent = 'Online';
                    } else {
                        container.classList.add('bg-red-100', 'text-red-700');
                        dot.classList.add('bg-red-500');
                        label.textContent = 'Offline';
                    }

                    checkText.textContent =
                        'Cek terakhir: ' +
                        new Date().toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });

                } catch (error) {
                    container.classList.remove(
                        'bg-gray-100', 'text-gray-500',
                        'bg-green-100', 'text-green-700'
                    );

                    container.classList.add('bg-red-100', 'text-red-700');

                    dot.classList.remove('bg-gray-400', 'animate-pulse');
                    dot.classList.add('bg-red-500');

                    label.textContent = 'Offline';

                    checkText.textContent =
                        'Cek terakhir: ' +
                        new Date().toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });

                    console.error('Connection monitoring error:', error);
                }
            }

            monitorElements.forEach(function (container) {
                checkConnection(container);
            });

            setInterval(function () {
                monitorElements.forEach(function (container) {
                    checkConnection(container);
                });
            }, 30000);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeServerMonitor();
                }
            });
        });
    </script>

    <!-- SweetAlert Hapus Server -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.delete-server-form').forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    event.preventDefault();

                    const serverName =

                        form.querySelector('button')

                            .closest('tr')

                            .querySelector('td:nth-child(3)')

                            .innerText

                            .trim();

                    Swal.fire({

                        title: 'Hapus Server?',

                        text: 'Server "' + serverName + '" akan dihapus secara permanen.',

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonColor: '#dc2626',

                        cancelButtonColor: '#6b7280',

                        confirmButtonText: 'Ya, Hapus',

                        cancelButtonText: 'Batal',

                        reverseButtons: true

                    }).then((result) => {

                        if (result.isConfirmed) {

                            form.submit();

                        }

                    });

                });

            });

        });

    </script>

</x-app-layout>
