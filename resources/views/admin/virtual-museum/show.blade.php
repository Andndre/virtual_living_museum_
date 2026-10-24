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
                                    <span class="ml-1 text-gray-500">{{ $museum->nama }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">{{ $museum->nama }}</h1>
                    <p class="mt-2 text-sm text-gray-600 sm:text-base">{{ config('app.name') }}
                        untuk {{ $museum->situsPeninggalan->nama }}</p>
                </div>
                <div class="mt-4 flex flex-col gap-2 sm:mt-0 sm:flex-row">
                    <a href="{{ route('admin.virtual-museum.edit', $museum->museum_id) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Museum
                    </a>
                    <a href="{{ route('admin.virtual-museum') }}"
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

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <!-- Main Content -->
            <div class="space-y-6 lg:col-span-2">

                <!-- Museum Information Card -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900">Informasi Museum</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <!-- Nama Museum -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Virtual Living Museum</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $museum->nama }}</dd>
                            </div>

                            <!-- Situs -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Situs Peninggalan</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.situs.show', $museum->situsPeninggalan->situs_id) }}"
                                        class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $museum->situsPeninggalan->nama }}
                                    </a>
                                </dd>
                            </div>

                            <!-- Era/Masa -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Materi</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if ($museum->situsPeninggalan->materi)
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            {{ $museum->situsPeninggalan->materi->judul }}
                                        </span>
                                    @else
                                        <span class="italic text-gray-400">Tidak ada</span>
                                    @endif
                                </dd>
                            </div>

                            <!-- Tanggal Dibuat -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $museum->created_at->format('d M Y H:i') }}
                                </dd>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- File Information Card -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900">File 3D Museum</h2>
                    </div>
                    <div class="px-6 py-6">
                        @if ($museum->path_obj)
                            @php
                                $is3D = preg_match('/\.(glb|gltf)$/i', $museum->path_obj);
                            @endphp
                            @if ($is3D)
                                <div class="relative w-full h-80 rounded-xl overflow-hidden bg-slate-900 border border-gray-200 shadow-inner mb-4">
                                    <model-viewer
                                        src="{{ asset('storage/' . $museum->path_obj) }}"
                                        alt="Model 3D Museum {{ $museum->nama }}"
                                        camera-controls
                                        auto-rotate
                                        shadow-intensity="1"
                                        environment-image="neutral"
                                        exposure="1"
                                        class="w-full h-full"
                                        style="width: 100%; height: 100%; background-color: #0f172a;"
                                    >
                                        <div slot="poster" class="flex items-center justify-center w-full h-full text-gray-400 text-xs">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Memuat Model 3D Museum...</span>
                                        </div>
                                    </model-viewer>
                                </div>
                            @endif

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-lg bg-gray-50 p-4 border border-gray-200">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">File Model 3D Museum</p>
                                        <p class="break-all text-xs font-mono text-gray-500">{{ basename($museum->path_obj) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ asset('storage/' . $museum->path_obj) }}" target="_blank" download
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold shadow-sm transition">
                                        <svg class="w-3.5 h-3.5 text-gray-500" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span>Unduh File</span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="py-6 text-center text-sm text-gray-500">
                                Belum ada file 3D model museum yang diunggah.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Virtual Living Museum Objects -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <h2 class="text-lg font-medium text-gray-900">Objek AR Marker</h2>
                            <span
                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                {{ $museum->virtualMuseumObjects->count() }} Objek
                            </span>
                        </div>
                        <a href="{{ route('admin.virtual-museum-object.create', $museum->museum_id) }}"
                            class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-3 py-2 text-sm font-medium leading-4 text-white transition-colors hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Object
                        </a>
                    </div>
                    <div class="px-6 py-6">
                        @if ($museum->virtualMuseumObjects->count() > 0)
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach ($museum->virtualMuseumObjects as $object)
                                    <div
                                        class="rounded-xl border border-gray-200 bg-white p-4 transition-all hover:shadow-md flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="min-w-0 flex-1">
                                                    <h4 class="text-sm font-bold text-gray-900 truncate">{{ $object->nama }}</h4>
                                                    @if ($object->deskripsi)
                                                        <p class="mt-1 line-clamp-2 text-xs text-gray-500">{{ Str::limit($object->deskripsi, 80) }}</p>
                                                    @endif
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex items-center space-x-1 shrink-0">
                                                    <a href="{{ route('admin.virtual-museum-object.show', $object->object_id) }}"
                                                        class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                                        title="Lihat Detail">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('admin.virtual-museum-object.edit', $object->object_id) }}"
                                                        class="p-1.5 rounded-lg text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 transition-colors"
                                                        title="Edit">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('admin.virtual-museum-object.destroy', $object->object_id) }}"
                                                        class="inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus object ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                            title="Hapus">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- 3D Preview Container -->
                                            @if ($object->path_obj)
                                                <div class="relative w-full h-48 rounded-xl overflow-hidden bg-slate-900 border border-gray-200 shadow-inner my-3">
                                                    <model-viewer
                                                        src="{{ asset('storage/' . $object->path_obj) }}"
                                                        alt="{{ $object->nama }}"
                                                        camera-controls
                                                        auto-rotate
                                                        shadow-intensity="1"
                                                        environment-image="neutral"
                                                        exposure="1"
                                                        loading="lazy"
                                                        class="w-full h-full"
                                                        style="width: 100%; height: 100%; background-color: #0f172a;"
                                                    >
                                                        <div slot="poster" class="flex items-center justify-center w-full h-full text-gray-400 text-xs">
                                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            <span>Memuat 3D...</span>
                                                        </div>
                                                    </model-viewer>
                                                </div>
                                            @elseif ($object->gambar_real)
                                                <div class="relative w-full h-48 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 my-3 flex items-center justify-center">
                                                    <img src="{{ asset('storage/' . $object->gambar_real) }}" alt="{{ $object->nama }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-full h-28 rounded-xl bg-gray-50 border border-dashed border-gray-200 my-3 flex items-center justify-center text-gray-400 text-xs">
                                                    <span>Belum ada model 3D atau gambar</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Badges at bottom -->
                                        <div class="mt-2 pt-2 border-t border-gray-100 flex flex-wrap items-center gap-1.5 text-[11px]">
                                            @if ($object->path_obj)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <i class="fas fa-cube text-[10px]"></i> GLB
                                                </span>
                                            @endif
                                            @if ($object->path_usdz)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                                    <i class="fab fa-apple text-[10px]"></i> USDZ
                                                </span>
                                            @endif
                                            @if ($object->marker_id)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <i class="fas fa-qrcode text-[10px]"></i> QR #{{ $object->marker_id }}
                                                </span>
                                            @endif
                                            @if ($object->path_audio)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <i class="fas fa-volume-up text-[10px]"></i> Audio
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center">
                                <div class="mx-auto h-12 w-12 text-gray-400">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada Objek</h3>
                                <p class="mt-1 text-sm text-gray-500">Virtual Living Museum ini belum memiliki objek
                                    apapun.</p>
                            </div>
                        @endif
                    </div>
                </div>



            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Quick Stats -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">Statistik</h3>
                    </div>
                    <div class="space-y-4 px-6 py-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Objek</span>
                            <span
                                class="text-sm font-semibold text-gray-900">{{ $museum->virtualMuseumObjects->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Objek dengan AR</span>
                            <span
                                class="text-sm font-semibold text-gray-900">{{ $museum->virtualMuseumObjects->whereNotNull('path_patt')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Objek dengan Gambar</span>
                            <span
                                class="text-sm font-semibold text-gray-900">{{ $museum->virtualMuseumObjects->whereNotNull('gambar_real')->count() }}</span>
                        </div>

                    </div>
                </div>

                <!-- Location Info -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">Lokasi Situs</h3>
                    </div>
                    <div class="space-y-3 px-6 py-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $museum->situsPeninggalan->alamat }}</dd>
                        </div>
                        @if ($museum->situsPeninggalan->lat && $museum->situsPeninggalan->lng)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Koordinat</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ number_format($museum->situsPeninggalan->lat, 6) }},
                                    {{ number_format($museum->situsPeninggalan->lng, 6) }}
                                </dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
                    </div>
                    <div class="space-y-3 px-6 py-6">
                        <a href="{{ route('admin.virtual-museum.edit', $museum->museum_id) }}"
                            class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Museum
                        </a>

                        <form action="{{ route('admin.virtual-museum.destroy', $museum->museum_id) }}" method="POST"
                            class="w-full"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus Virtual Living Museum ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-md border border-red-300 bg-red-50 px-3 py-2 text-sm font-medium leading-4 text-red-700 shadow-sm transition-colors hover:bg-red-100">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Museum
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('head-scripts')
        <script>
            self.ModelViewerElement = self.ModelViewerElement || {};
            self.ModelViewerElement.meshoptDecoderLocation = 'https://cdn.jsdelivr.net/npm/meshoptimizer@0.21.0/meshopt_decoder.js';
        </script>
        <script type="module" src="https://cdn.jsdelivr.net/npm/@google/model-viewer@3.5.0/dist/model-viewer.min.js"></script>
    @endpush

    <!-- Fallback direct script load in case head-scripts stack is omitted -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@google/model-viewer@3.5.0/dist/model-viewer.min.js"></script>
</x-app-layout>
