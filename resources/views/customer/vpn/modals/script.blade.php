<!-- MODAL SCRIPT MIKROTIK -->
<div
    x-show="openScript"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    <!-- OVERLAY -->
    <div
        class="absolute inset-0 bg-black/50"
        @click="openScript = false"
    ></div>

    <!-- MODAL -->
    <div
        class="relative w-full max-w-3xl mx-4 bg-white rounded-2xl shadow-2xl"
        @click.stop
    >

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">

            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Script MikroTik
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Silahkan Copy Scrip Berikut Dan Pastekan Di New Terimnal Mikrotik
                </p>
            </div>

            <button
                type="button"
                @click="openScript = false"
                class="text-2xl leading-none text-gray-400 hover:text-gray-600"
            >
                &times;
            </button>

        </div>

        <!-- BODY -->
        <div class="p-6">

            <!-- SCRIPT -->
            <div class="relative">

                <pre
                    id="mikrotik-script"
                    class="w-full overflow-x-auto rounded-xl bg-slate-900 p-5 text-sm leading-6 text-green-400 font-mono"
                ><code x-text="mikrotikScript"></code></pre>

                <!-- COPY BUTTON -->
                <button
                    type="button"
                    @click="
                        navigator.clipboard.writeText(mikrotikScript);

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Script berhasil disalin',
                            showConfirmButton: false,
                            timer: 1800,
                            timerProgressBar: true
                        });
                    "
                    class="absolute right-3 top-3
                           inline-flex items-center
                           rounded-lg bg-white/10
                           px-3 py-2
                           text-xs font-semibold
                           text-white
                           backdrop-blur
                           transition hover:bg-white/20"
                >
                    Copy
                </button>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="flex justify-end px-6 py-4 border-t border-gray-200">

            <button
                type="button"
                @click="openScript = false"
                class="px-4 py-2.5 rounded-lg
                       border border-gray-300
                       text-gray-700
                       hover:bg-gray-50
                       transition"
            >
                Tutup
            </button>

        </div>

    </div>

</div>
