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
                                    <a href="{{ route('admin.virtual-museum-object.show', $object->object_id) }}"
                                        class="ml-1 text-gray-400 hover:text-gray-500">{{ $object->nama }}</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="ml-1 text-gray-500">Edit</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Edit Object: {{ $object->nama }}</h1>
                    <p class="mt-2 text-sm text-gray-600 sm:text-base">Untuk {{ $object->virtualMuseum->nama }} -
                        {{ $object->situsPeninggalan->nama }}</p>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                <div class="mb-2 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada input:</h3>
                </div>
                <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.virtual-museum-object.update', $object->object_id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900">Informasi Object</h2>
                    <p class="mt-1 text-sm text-gray-600">Edit data object {{ config('app.name') }}.</p>
                </div>

                <div class="space-y-6 px-6 py-6">

                    <!-- Nama Object -->
                    <div>
                        <label for="nama" class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Object <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $object->nama) }}"
                            class="block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Arca Buddha Amitabha" required>
                        <p class="mt-1 text-xs text-gray-500">Nama object yang akan ditampilkan dalam Virtual Living
                            Museum</p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="mb-2 block text-sm font-medium text-gray-700">
                            Deskripsi Object
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Deskripsi detail tentang object ini...">{{ old('deskripsi', $object->deskripsi) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Deskripsi yang akan ditampilkan kepada pengunjung museum
                        </p>
                    </div>

                    <!-- Audio Narasi -->
                    <div>
                        <label for="audio_file" class="mb-2 block text-sm font-medium text-gray-700">
                            File Audio Narasi <span class="text-gray-400">(Opsional)</span>
                        </label>
                        @if($object->path_audio)
                            <div class="mb-2 flex items-center space-x-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ basename($object->path_audio) }}</p>
                                    <audio controls class="mt-2 w-full max-w-sm">
                                        <source src="{{ asset('/storage/' . $object->path_audio) }}">
                                        Browser tidak mendukung audio
                                    </audio>
                                </div>
                            </div>
                            <div class="mb-2 flex items-center">
                                <input type="checkbox" name="remove_audio" id="remove_audio" value="1" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <label for="remove_audio" class="ml-2 text-sm text-red-600">Hapus audio</label>
                            </div>
                        @endif
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors"
                            id="audio-drop-area">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M24 4c-4.418 0-8 3.582-8 8v16c0 4.418 3.582 8 8 8s8-3.582 8-8V12c0-4.418-3.582-8-8-8zm-6 24c-3.314 0-6-2.686-6-6s2.686-6 6-6 6 2.686 6 6-2.686 6-6 6z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M42 24c0 9.941-8.059 18-18 18S6 33.941 6 24s8.059-18 18-18 18 8.059 18 18z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="audio_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file audio baru</span>
                                        <input id="audio_file" name="audio_file" type="file" class="sr-only" accept=".mp3,.wav,.ogg,.aac" onchange="updateAudioFileName(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">MP3, WAV, OGG, AAC up to 10MB</p>
                            </div>
                        </div>
                        <div id="audio-file-name" class="mt-2 text-sm text-gray-600 hidden"></div>
                    </div>

                    <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-bold text-blue-950 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                Pengaturan Marker AR (QR Code)
                            </h3>
                            <span class="text-[11px] font-semibold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">Otomatis Terintegrasi</span>
                        </div>
                        <p class="text-xs text-blue-800 leading-relaxed mb-4">
                            Gunakan QR Code sebagai marker. Pengunjung cukup mengarahkan kamera untuk membuka dan memproyeksikan objek 3D ini.
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label for="marker_id" class="mb-1.5 block text-xs font-semibold text-gray-700">
                                    Pilih Marker AR
                                </label>
                                <select id="marker_id" name="marker_id" onchange="handleMarkerSelectionChange()"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" @selected(empty($object->marker_id))>-- Buat Marker Baru / Lepas Marker --</option>
                                    @foreach ($markers as $marker)
                                        <option value="{{ $marker->marker_id }}"
                                                data-name="{{ $marker->nama ?: 'Marker #' . $marker->marker_id }}"
                                                @selected(old('marker_id', $object->marker_id) == $marker->marker_id)>
                                            {{ $marker->nama ?: 'Marker #' . $marker->marker_id }}
                                            @if ($marker->virtual_museum_objects_count > 0)
                                                ({{ $marker->virtual_museum_objects_count }} objek)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="new-marker-container" class="{{ $object->marker_id ? 'hidden' : '' }}">
                                <label for="nama_marker" class="mb-1.5 block text-xs font-semibold text-gray-700">
                                    Nama / Label Marker Baru (Opsional)
                                </label>
                                <input type="text" id="nama_marker" name="nama_marker"
                                    value="{{ old('nama_marker') }}" oninput="updateQrPreview()"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Contoh: Marker Candi Utama">
                                <p class="mt-1 text-[11px] text-gray-500">Jika diisi, marker baru otomatis dibuat dan ditautkan ke objek ini.</p>
                            </div>

                            <!-- Live QR Code Preview Box -->
                            <div id="qr-preview-wrapper" class="rounded-xl border border-blue-200 bg-white p-4 shadow-sm flex flex-col sm:flex-row items-center gap-4">
                                <div class="bg-white p-2 rounded-lg border border-gray-100 shadow-inner flex items-center justify-center shrink-0">
                                    <canvas id="qr-canvas" class="w-28 h-28"></canvas>
                                </div>
                                <div class="flex-1 text-center sm:text-left min-w-0">
                                    <h4 id="qr-label" class="text-xs font-bold text-gray-900 truncate">
                                        {{ $object->arMarker ? ($object->arMarker->nama ?: 'Marker #' . $object->arMarker->marker_id) : 'Preview QR Marker' }}
                                    </h4>
                                    <p id="qr-url-text" class="text-[11px] text-gray-500 font-mono truncate mt-0.5">
                                        {{ $object->marker_id ? url('/ar/scan/' . $object->marker_id) : url('/ar-marker/camera') }}
                                    </p>
                                    <div class="mt-2.5 flex flex-wrap gap-2 justify-center sm:justify-start">
                                        <button type="button" onclick="downloadQrPng()"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition active:scale-95">
                                            <svg class="w-3.5 h-3.5 shrink-0" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            <span>Unduh QR (.png)</span>
                                        </button>
                                        @if($object->marker_id)
                                            <a href="{{ url('/ar/scan/' . $object->marker_id) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                <span>Test Link Scan</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Current Files Section -->
            @if ($object->gambar_real || $object->path_obj || $object->path_usdz || $object->path_audio || $object->path_gambar_marker)
                <div class="mt-6 rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-900">File Saat Ini</h2>
                        <p class="mt-1 text-sm text-gray-600">File yang sudah diupload untuk object ini.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 px-6 py-6 md:grid-cols-2">

                        @if ($object->gambar_real)
                            <div class="rounded-lg border border-gray-200 p-3">
                                <h4 class="mb-2 text-sm font-medium text-gray-700">Gambar Real Object</h4>
                                <img src="{{ asset('storage/' . $object->gambar_real) }}" alt="Current Gambar Real"
                                    class="h-24 w-full rounded object-cover">
                                <p class="mt-1 text-xs text-gray-500">{{ basename($object->gambar_real) }}</p>
                            </div>
                        @endif

                        @if ($object->path_obj)
                            <div class="rounded-lg border border-gray-200 p-3">
                                <h4 class="mb-2 text-sm font-medium text-gray-700">File 3D Object (GLB/GLTF)</h4>
                                <div class="flex items-center space-x-2">
                                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span class="text-sm text-gray-900 truncate">{{ basename($object->path_obj) }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($object->path_usdz)
                            <div class="rounded-lg border border-gray-200 p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-medium text-gray-700">File 3D iOS (.usdz)</h4>
                                    <label class="flex items-center space-x-1 text-xs text-red-600 cursor-pointer">
                                        <input type="checkbox" name="remove_usdz" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span>Hapus</span>
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span class="text-sm text-gray-900 truncate">{{ basename($object->path_usdz) }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($object->path_audio)
                            <div class="rounded-lg border border-gray-200 p-3">
                                <h4 class="mb-2 text-sm font-medium text-gray-700">Audio Narasi</h4>
                                <div class="flex items-center space-x-2">
                                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                    </svg>
                                    <span class="text-sm text-gray-900">{{ basename($object->path_audio) }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($object->path_gambar_marker)
                            <div class="rounded-lg border border-gray-200 p-3">
                                <h4 class="mb-2 text-sm font-medium text-gray-700">Gambar Marker AR</h4>
                                <img src="{{ asset('storage/' . $object->path_gambar_marker) }}"
                                    alt="Current AR Marker" class="h-24 w-full rounded object-cover">
                                <p class="mt-1 text-xs text-gray-500">{{ basename($object->path_gambar_marker) }}</p>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            <!-- File Uploads Section -->
            <div class="mt-6 rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900">Upload File Baru</h2>
                    <p class="mt-1 text-sm text-gray-600">Upload file baru jika ingin mengganti file yang sudah ada.
                        Kosongkan jika tidak ingin mengubah.</p>
                </div>

                <div class="space-y-6 px-6 py-6">

                    <!-- Gambar Real Object -->
                    <div>
                        <label for="gambar_real" class="mb-2 block text-sm font-medium text-gray-700">
                            Gambar Real Object {{ $object->gambar_real ? '(Ganti)' : '' }}
                        </label>
                        <div id="gambar_real_dropzone"
                            class="mt-1 flex cursor-pointer justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pb-6 pt-5 transition-colors hover:border-gray-400">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="gambar_real"
                                        class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500">
                                        <span id="gambar_real_text">Upload gambar</span>
                                        <input id="gambar_real" name="gambar_real" type="file" class="sr-only"
                                            accept="image/*" onchange="handleFileSelect('gambar_real')">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Foto asli dari object untuk referensi pengunjung</p>
                    </div>

                    <!-- File 3D Object -->
                    <div>
                        <label for="path_obj" class="mb-2 block text-sm font-medium text-gray-700">
                            File 3D Object {{ $object->path_obj ? '(Ganti)' : '' }}
                        </label>
                        <div id="path_obj_dropzone"
                            class="mt-1 flex cursor-pointer justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pb-6 pt-5 transition-colors hover:border-gray-400">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="path_obj"
                                        class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500">
                                        <span id="path_obj_text">Upload file 3D</span>
                                        <input id="path_obj" name="path_obj" type="file" class="sr-only"
                                            accept=".obj,.glb,.gltf" onchange="handleFileSelect('path_obj')">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">OBJ, GLB, GLTF up to 50MB</p>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Model 3D object untuk ditampilkan dalam Virtual Living
                            Museum</p>
                    </div>

                    <!-- File 3D Object (iOS USDZ) -->
                    <div>
                        <label for="path_usdz" class="mb-2 block text-sm font-medium text-gray-700">
                            File 3D Object iOS (.usdz) {{ $object->path_usdz ? '(Ganti)' : '' }} <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <div id="path_usdz_dropzone"
                            class="mt-1 flex cursor-pointer justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pb-6 pt-5 transition-colors hover:border-gray-400">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="path_usdz"
                                        class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500">
                                        <span id="path_usdz_text">Upload file USDZ</span>
                                        <input id="path_usdz" name="path_usdz" type="file" class="sr-only"
                                            accept=".usdz" onchange="handleFileSelect('path_usdz')">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">USDZ hingga 100MB</p>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Format USDZ dibutuhkan agar model 3D dapat dibuka langsung di aplikasi AR bawaan perangkat iOS / iPadOS.</p>
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end sm:gap-4">
                <a href="{{ route('admin.virtual-museum-object.show', $object->object_id) }}"
                    class="order-2 inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:order-1 sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit"
                    class="order-1 inline-flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:order-2 sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        // File upload handling
        function handleFileSelect(inputId) {
            const input = document.getElementById(inputId);
            const textElement = document.getElementById(inputId + '_text');
            const dropzoneElement = document.getElementById(inputId + '_dropzone');

            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB

                textElement.textContent = `${fileName} (${fileSize} MB)`;
                dropzoneElement.classList.remove('border-gray-300');
                dropzoneElement.classList.add('border-green-400', 'bg-green-50');
            } else {
                // Reset to default state
                resetFileInput(inputId);
            }
        }

        function resetFileInput(inputId) {
            const textElement = document.getElementById(inputId + '_text');
            const dropzoneElement = document.getElementById(inputId + '_dropzone');

            dropzoneElement.classList.remove('border-green-400', 'bg-green-50');
            dropzoneElement.classList.add('border-gray-300');

            switch (inputId) {
                case 'gambar_real':
                    textElement.textContent = 'Upload gambar';
                    break;
                case 'path_obj':
                    textElement.textContent = 'Upload file 3D';
                    break;
                case 'path_usdz':
                    textElement.textContent = 'Upload file USDZ';
                    break;
            }
        }

        function updateAudioFileName(input) {
            const fileNameDiv = document.getElementById('audio-file-name');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (!file.name.match(/\.(mp3|wav|ogg|aac)$/i)) {
                    alert('Hanya file audio MP3, WAV, OGG, AAC yang diizinkan!');
                    input.value = '';
                    return;
                }
                if (file.size > 10 * 1024 * 1024) {
                    alert('File terlalu besar! Maksimal 10MB.');
                    input.value = '';
                    return;
                }
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileNameDiv.innerHTML = `
                    <div class="flex items-center space-x-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                            <p class="text-xs text-gray-500">${fileSize} MB</p>
                        </div>
                    </div>
                `;
                fileNameDiv.classList.remove('hidden');
            }
        }

        // Drag and drop for audio
        const audioDropArea = document.getElementById('audio-drop-area');
        const audioFileInput = document.getElementById('audio_file');
        if (audioDropArea && audioFileInput) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                audioDropArea.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }, false);
            });
            ['dragenter', 'dragover'].forEach(eventName => {
                audioDropArea.addEventListener(eventName, () => audioDropArea.classList.add('border-blue-500', 'bg-blue-50'), false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                audioDropArea.addEventListener(eventName, () => audioDropArea.classList.remove('border-blue-500', 'bg-blue-50'), false);
            });
            audioDropArea.addEventListener('drop', e => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.name.match(/\.(mp3|wav|ogg|aac)$/i) && file.size <= 10 * 1024 * 1024) {
                        audioFileInput.files = files;
                        updateAudioFileName(audioFileInput);
                    } else {
                        alert('File tidak valid! Gunakan MP3, WAV, OGG, atau AAC (maksimal 10MB).');
                    }
                }
            }, false);
        }

        // Drag and drop functionality
        function setupDragAndDrop(inputId) {
            const dropzoneElement = document.getElementById(inputId + '_dropzone');
            const inputElement = document.getElementById(inputId);
            if (!dropzoneElement || !inputElement) return;

            dropzoneElement.addEventListener('click', function() {
                inputElement.click();
            });

            dropzoneElement.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneElement.classList.add('border-blue-400', 'bg-blue-50');
            });

            dropzoneElement.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneElement.classList.remove('border-blue-400', 'bg-blue-50');
            });

            dropzoneElement.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneElement.classList.remove('border-blue-400', 'bg-blue-50');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    inputElement.files = files;
                    handleFileSelect(inputId);
                }
            });
        }

        // QRious live preview logic with centered logo
        const qrLogo = new Image();
        qrLogo.src = '{{ asset('images/logo/logo.png') }}';
        let qrLogoLoaded = false;
        qrLogo.onload = function() {
            qrLogoLoaded = true;
            drawLogoOnCanvas(document.getElementById('qr-canvas'));
        };

        function drawLogoOnCanvas(canvas) {
            if (!canvas || !qrLogoLoaded) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            const size = canvas.width;
            const logoSize = Math.round(size * 0.22);
            const bgPadding = 5;
            const bgSize = logoSize + (bgPadding * 2);
            const bgX = (size - bgSize) / 2;
            const bgY = (size - bgSize) / 2;

            ctx.save();
            ctx.fillStyle = '#ffffff';
            const radius = 8;
            ctx.beginPath();
            ctx.moveTo(bgX + radius, bgY);
            ctx.lineTo(bgX + bgSize - radius, bgY);
            ctx.quadraticCurveTo(bgX + bgSize, bgY, bgX + bgSize, bgY + radius);
            ctx.lineTo(bgX + bgSize, bgY + bgSize - radius);
            ctx.quadraticCurveTo(bgX + bgSize, bgY + bgSize, bgX + bgSize - radius, bgY + bgSize);
            ctx.lineTo(bgX + radius, bgY + bgSize);
            ctx.quadraticCurveTo(bgX, bgY + bgSize, bgX, bgY + bgSize - radius);
            ctx.lineTo(bgX, bgY + radius);
            ctx.quadraticCurveTo(bgX, bgY + radius, bgX, bgY);
            ctx.closePath();
            ctx.fill();

            ctx.strokeStyle = '#e2e8f0';
            ctx.lineWidth = 1.5;
            ctx.stroke();

            let drawW = logoSize;
            let drawH = logoSize;
            if (qrLogo.naturalWidth && qrLogo.naturalHeight) {
                const aspect = qrLogo.naturalWidth / qrLogo.naturalHeight;
                if (aspect > 1) {
                    drawH = logoSize / aspect;
                } else {
                    drawW = logoSize * aspect;
                }
            }
            const drawX = (size - drawW) / 2;
            const drawY = (size - drawH) / 2;
            ctx.drawImage(qrLogo, drawX, drawY, drawW, drawH);
            ctx.restore();
        }

        function updateQrCode(url) {
            const canvas = document.getElementById('qr-canvas');
            if (!canvas || typeof QRious === 'undefined') return;

            new QRious({
                element: canvas,
                size: 400,
                value: url,
                level: 'H'
            });

            if (qrLogoLoaded) {
                drawLogoOnCanvas(canvas);
            }
        }

        function initQrGenerator() {
            handleMarkerSelectionChange();
        }

        function handleMarkerSelectionChange() {
            const markerSelect = document.getElementById('marker_id');
            const newMarkerContainer = document.getElementById('new-marker-container');
            const qrLabel = document.getElementById('qr-label');
            const qrUrlText = document.getElementById('qr-url-text');

            if (!markerSelect) return;

            const selectedOption = markerSelect.options[markerSelect.selectedIndex];
            const markerId = markerSelect.value;

            if (markerId) {
                if (newMarkerContainer) newMarkerContainer.classList.add('hidden');
                const markerName = selectedOption.getAttribute('data-name') || ('Marker #' + markerId);
                const scanUrl = '{{ url('/ar/scan') }}/' + markerId;

                if (qrLabel) qrLabel.textContent = markerName;
                if (qrUrlText) qrUrlText.textContent = scanUrl;

                updateQrCode(scanUrl);
            } else {
                if (newMarkerContainer) newMarkerContainer.classList.remove('hidden');
                updateQrPreview();
            }
        }

        function updateQrPreview() {
            const namaMarkerInput = document.getElementById('nama_marker');
            const qrLabel = document.getElementById('qr-label');
            const qrUrlText = document.getElementById('qr-url-text');

            const markerName = (namaMarkerInput && namaMarkerInput.value.trim()) ? namaMarkerInput.value.trim() : 'Marker Baru';
            if (qrLabel) qrLabel.textContent = markerName + ' (Dibuat Saat Simpan)';
            const sampleUrl = '{{ url('/ar-marker/camera') }}';
            if (qrUrlText) qrUrlText.textContent = sampleUrl;

            updateQrCode(sampleUrl);
        }

        function downloadQrPng() {
            const canvas = document.getElementById('qr-canvas');
            const qrLabel = document.getElementById('qr-label');
            if (!canvas) return;

            const fileName = (qrLabel ? qrLabel.textContent.replace(/[^a-zA-Z0-9_-]/g, '_') : 'qr_marker') + '.png';
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Initialize drag and drop for all file inputs
        document.addEventListener('DOMContentLoaded', function() {
            setupDragAndDrop('gambar_real');
            setupDragAndDrop('path_obj');
            setupDragAndDrop('path_usdz');
            initQrGenerator();
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const namaInput = document.getElementById('nama');

            if (!namaInput.value.trim()) {
                e.preventDefault();
                alert('Nama object harus diisi!');
                namaInput.focus();
                return false;
            }

            // Show loading state
            const submitButton = document.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;

            // Reset button after 30 seconds (failsafe)
            setTimeout(function() {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }, 30000);
        });
    </script>
</x-app-layout>
