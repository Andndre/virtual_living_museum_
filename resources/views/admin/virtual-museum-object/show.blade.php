<x-app-layout>
    <x-slot name="header">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <!-- Breadcrumb -->
                    <nav class="mb-2 flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.virtual-museum') }}"
                                    class="text-gray-400 hover:text-gray-500">{{ config('app.name') }}</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('admin.virtual-museum.show', $object->virtualMuseum->museum_id) }}"
                                        class="ml-1 text-gray-400 hover:text-gray-500">{{ $object->virtualMuseum->nama }}</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="ml-1 text-gray-500">{{ $object->nama }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">{{ $object->nama }}</h1>
                    <p class="mt-2 text-sm text-gray-600 sm:text-base">Object dari {{ $object->virtualMuseum->nama }} -
                        {{ $object->situsPeninggalan->nama }}</p>
                </div>
                <div class="mt-4 flex flex-col gap-2 sm:mt-0 sm:flex-row">
                    <a href="{{ route('admin.virtual-museum-object.edit', $object->object_id) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Object
                    </a>
                    <a href="{{ route('admin.virtual-museum.show', $object->virtualMuseum->museum_id) }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        @php
            $activeMarker = $object->arMarker;
            $markerPathPatt = $activeMarker->path_patt ?? $object->path_patt;
            $markerImagePath = $activeMarker->path_gambar_marker ?? $object->path_gambar_marker;
        @endphp

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <!-- Main Content -->
            <div class="space-y-6 lg:col-span-2">

                <!-- Basic Information -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900">Informasi Object</h2>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 gap-6">

                            <!-- Nama Object -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Object</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $object->nama }}</dd>
                            </div>

                            <!-- Deskripsi -->
                            @if ($object->deskripsi)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                    <dd class="mt-1 whitespace-pre-line text-sm text-gray-900">{{ $object->deskripsi }}
                                    </dd>
                                </div>
                            @endif

                            <!-- Virtual Living Museum -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Virtual Living Museum</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.virtual-museum.show', $object->virtualMuseum->museum_id) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        {{ $object->virtualMuseum->nama }}
                                    </a>
                                </dd>
                            </div>

                            <!-- Situs Peninggalan -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Situs Peninggalan</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.situs.show', $object->situsPeninggalan->situs_id) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        {{ $object->situsPeninggalan->nama }}
                                    </a>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Marker AR</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if ($activeMarker)
                                        {{ $activeMarker->nama ?: 'Marker #' . $activeMarker->marker_id }}
                                    @else
                                        <span class="italic text-gray-400">Belum ditautkan</span>
                                    @endif
                                </dd>
                            </div>

                        </dl>
                    </div>
                </div>

                <!-- Files Section -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900">File dan Asset</h2>
                    </div>
                    <div class="space-y-6 px-6 py-6">

                        <!-- Gambar Real -->
                        @if ($object->gambar_real)
                            <div>
                                <h3 class="mb-2 text-sm font-medium text-gray-700">Gambar Real Object</h3>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <img src="{{ asset('storage/' . $object->gambar_real) }}"
                                        alt="Gambar Real {{ $object->nama }}"
                                        class="h-auto max-h-64 max-w-full rounded-lg object-cover shadow-sm">
                                    <p class="mt-2 text-xs text-gray-500">{{ basename($object->gambar_real) }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- 3D Object File -->
                        @if ($object->path_obj)
                            <div>
                                <h3 class="mb-2 text-sm font-medium text-gray-700">File 3D Object (GLB/GLTF)</h3>
                                <div
                                    class="flex items-center space-x-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Model 3D (Web & Android)</p>
                                        <p class="break-all text-sm text-gray-500">{{ basename($object->path_obj) }}
                                        </p>
                                        <a href="{{ asset('storage/' . $object->path_obj) }}" target="_blank"
                                            class="text-xs text-blue-600 hover:text-blue-800">Download File</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- 3D USDZ File (iOS Quick Look) -->
                        <div>
                            <h3 class="mb-2 text-sm font-medium text-gray-700">File 3D iOS (.usdz)</h3>
                            @if ($object->path_usdz)
                                <div
                                    class="flex items-center space-x-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm font-medium text-gray-900">Model USDZ (Apple AR Quick Look)</p>
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">iOS Ready</span>
                                        </div>
                                        <p class="break-all text-sm text-gray-500">{{ basename($object->path_usdz) }}</p>
                                        <a href="{{ route('usdz.serve', ['path' => basename($object->path_usdz)]) }}" target="_blank"
                                            class="text-xs text-blue-600 hover:text-blue-800">Download / Buka USDZ</a>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-lg border border-dashed border-gray-300 p-4 text-center">
                                    <p class="text-xs italic text-gray-400">Belum diunggah. Model iOS akan menggunakan preview browser standar tanpa native Quick Look.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Audio Narasi -->
                        @if ($object->path_audio)
                            <div>
                                <h3 class="mb-2 text-sm font-medium text-gray-700">Audio Narasi</h3>
                                <div
                                    class="flex items-center space-x-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Audio Narasi</p>
                                        <p class="break-all text-sm text-gray-500">{{ basename($object->path_audio) }}</p>
                                        <audio controls class="mt-2 w-full max-w-sm">
                                            <source src="{{ asset('/storage/' . $object->path_audio) }}">
                                            Browser tidak mendukung audio
                                        </audio>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Marker AR (QR Code) -->
                        @if ($activeMarker || $object->marker_id)
                            @php
                                $markerId = $activeMarker ? $activeMarker->marker_id : $object->marker_id;
                                $markerName = $activeMarker ? ($activeMarker->nama ?: 'Marker #' . $markerId) : ('Marker #' . $markerId);
                                $scanUrl = url('/ar/scan/' . $markerId);
                            @endphp
                            <div>
                                <h3 class="mb-2 text-sm font-medium text-gray-700">Marker AR (QR Code)</h3>
                                <div class="rounded-xl border border-blue-200 bg-blue-50/60 p-4 shadow-sm flex flex-col sm:flex-row items-center gap-4">
                                    <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm flex items-center justify-center shrink-0">
                                        <canvas id="qr-canvas-show" class="w-32 h-32" data-url="{{ $scanUrl }}"></canvas>
                                    </div>
                                    <div class="flex-1 text-center sm:text-left min-w-0">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-100 text-blue-800 mb-1">
                                            Siap Cetak / Scan
                                        </span>
                                        <h4 class="text-sm font-bold text-gray-900 truncate">{{ $markerName }}</h4>
                                        <p class="text-xs text-gray-500 font-mono break-all mt-1">{{ $scanUrl }}</p>
                                        <div class="mt-3 flex flex-wrap gap-2 justify-center sm:justify-start">
                                            <button type="button" onclick="downloadQrShowPng('{{ Str::slug($markerName) }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition active:scale-95">
                                                <svg class="w-3.5 h-3.5 shrink-0" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                <span>Unduh QR Code (.png)</span>
                                            </button>
                                            <a href="{{ $scanUrl }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                <span>Buka AR</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Legacy AR Marker Image (Bila ada) -->
                        @if ($markerImagePath)
                            <div>
                                <h3 class="mb-2 text-sm font-medium text-gray-700">Gambar Marker Pendukung</h3>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <img src="{{ asset('storage/' . $markerImagePath) }}"
                                        alt="Gambar Marker AR {{ $object->nama }}"
                                        class="h-auto max-h-48 max-w-full rounded-lg bg-white object-contain shadow-sm">
                                    <p class="mt-2 break-all text-xs text-gray-500">
                                        {{ basename($markerImagePath) }}</p>
                                </div>
                            </div>
                        @endif

                        @if (!$object->gambar_real && !$object->path_obj && !$object->path_usdz && !$object->path_audio && !$activeMarker && !$object->marker_id)
                            <div class="py-8 text-center">
                                <div class="mx-auto h-12 w-12 text-gray-400">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada file</h3>
                                <p class="mt-1 text-sm text-gray-500">Object ini belum memiliki file atau asset apapun.
                                </p>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Quick Actions -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
                    </div>
                    <div class="space-y-3 px-6 py-6">
                        <a href="{{ route('admin.virtual-museum-object.edit', $object->object_id) }}"
                            class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-3 py-2 text-sm font-medium leading-4 text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Object
                        </a>

                        <form method="POST"
                            action="{{ route('admin.virtual-museum-object.destroy', $object->object_id) }}"
                            onsubmit="return confirm('Yakin ingin menghapus object ini? Semua file terkait akan ikut terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-md border border-red-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-red-700 transition-colors hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Object
                            </button>
                        </form>
                    </div>
                </div>

                <!-- File Summary -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">Ringkasan File</h3>
                    </div>
                    <div class="space-y-4 px-6 py-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Gambar Real</span>
                            <span
                                class="{{ $object->gambar_real ? 'text-green-600' : 'text-gray-400' }} text-sm font-semibold">
                                {{ $object->gambar_real ? 'Ada' : 'Tidak Ada' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Model 3D (Web/Android)</span>
                            <span
                                class="{{ $object->path_obj ? 'text-green-600' : 'text-gray-400' }} text-sm font-semibold">
                                {{ $object->path_obj ? 'Ada' : 'Tidak Ada' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Model 3D iOS (.usdz)</span>
                            <span
                                class="{{ $object->path_usdz ? 'text-green-600' : 'text-gray-400' }} text-sm font-semibold">
                                {{ $object->path_usdz ? 'Ada' : 'Tidak Ada' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Audio Narasi</span>
                            <span
                                class="{{ $object->path_audio ? 'text-green-600' : 'text-gray-400' }} text-sm font-semibold">
                                {{ $object->path_audio ? 'Ada' : 'Tidak Ada' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Marker AR (QR Code)</span>
                            <span
                                class="{{ ($activeMarker || $object->marker_id) ? 'text-green-600' : 'text-gray-400' }} text-sm font-semibold">
                                {{ ($activeMarker || $object->marker_id) ? 'Tersedia' : 'Belum Ada' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @if ($activeMarker || $object->marker_id)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('qr-canvas-show');
                if (canvas && canvas.dataset.url) {
                    new QRious({
                        element: canvas,
                        value: canvas.dataset.url,
                        size: 240,
                        level: 'M'
                    });
                }
            });

            function downloadQrShowPng(filename) {
                const canvas = document.getElementById('qr-canvas-show');
                if (!canvas) return;
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = (filename || 'qr_marker') + '.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        </script>
    @endif
</x-app-layout>
