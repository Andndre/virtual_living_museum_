<x-app-layout>
    <x-slot name="header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <!-- Breadcrumb -->
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.virtual-museum') }}" class="text-gray-400 hover:text-gray-500">Virtual Living Museum</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-1 text-gray-500">{{ $museum->nama }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $museum->nama }}</h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600">Virtual Living Museum untuk {{ $museum->situsPeninggalan->nama }}</p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.virtual-museum.edit', $museum->museum_id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Museum
                </a>
                <a href="{{ route('admin.virtual-museum') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Museum Information Card -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Informasi Museum</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Nama Museum -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Virtual Living Museum</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $museum->nama }}</dd>
                        </div>

                        <!-- Situs -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Situs Peninggalan</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.situs.show', $museum->situsPeninggalan->situs_id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $museum->situsPeninggalan->nama }}
                                </a>
                            </dd>
                        </div>

                        <!-- Era/Masa -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Materi</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($museum->situsPeninggalan->materi)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $museum->situsPeninggalan->materi->judul }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </dd>
                        </div>

                        <!-- Tanggal Dibuat -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $museum->created_at->format('d M Y H:i') }}</dd>
                        </div>

                    </div>
                </div>
            </div>

            <!-- File Information Card -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">File 3D Museum</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">File OBJ Model</p>
                            <p class="text-sm text-gray-500 break-all">{{ $museum->path_obj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Virtual Living Museum Objects -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <h2 class="text-lg font-medium text-gray-900">Objek AR Marker</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $museum->virtualMuseumObjects->count() }} Objek
                        </span>
                    </div>
                    <a href="{{ route('admin.virtual-museum-object.create', $museum->museum_id) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Object
                    </a>
                </div>
                <div class="px-6 py-6">
                    @if($museum->virtualMuseumObjects->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($museum->virtualMuseumObjects as $object)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            <!-- Object Icon -->
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Object Details -->
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $object->nama }}</h4>
                                                @if($object->deskripsi)
                                                    <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ Str::limit($object->deskripsi, 80) }}</p>
                                                @endif
                                                <div class="mt-2 flex items-center space-x-4 text-xs text-gray-400">
                                                    @if($object->path_obj)
                                                        <span class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                            OBJ
                                                        </span>
                                                    @endif
                                                    @if($object->path_patt)
                                                        <span class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m-6 0h8m-8 0a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2"/>
                                                            </svg>
                                                            AR
                                                        </span>
                                                    @endif
                                                    @if($object->gambar_real)
                                                        <span class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            IMG
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center space-x-1">
                                            <a href="{{ route('admin.virtual-museum-object.show', $object->object_id) }}" class="p-1 text-gray-400 hover:text-blue-500 transition-colors" title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.virtual-museum-object.edit', $object->object_id) }}" class="p-1 text-gray-400 hover:text-yellow-500 transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.virtual-museum-object.destroy', $object->object_id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus object ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-gray-400 hover:text-red-500 transition-colors" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada Objek</h3>
                            <p class="mt-1 text-sm text-gray-500">Virtual Living Museum ini belum memiliki objek apapun.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Quick Stats -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistik</h3>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Objek</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $museum->virtualMuseumObjects->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Objek dengan AR</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $museum->virtualMuseumObjects->whereNotNull('path_patt')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Objek dengan Gambar</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $museum->virtualMuseumObjects->whereNotNull('gambar_real')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Location Info -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Lokasi Situs</h3>
                </div>
                <div class="px-6 py-6 space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $museum->situsPeninggalan->alamat }}</dd>
                    </div>
                    @if($museum->situsPeninggalan->lat && $museum->situsPeninggalan->lng)
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
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
                </div>
                <div class="px-6 py-6 space-y-3">
                    <a href="{{ route('admin.virtual-museum.edit', $museum->museum_id) }}" class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Museum
                    </a>
                    <form action="{{ route('admin.virtual-museum.destroy', $museum->museum_id) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Virtual Living Museum ini? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Museum
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
