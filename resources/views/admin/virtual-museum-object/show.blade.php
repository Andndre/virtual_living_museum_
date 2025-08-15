<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <!-- Breadcrumb -->
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.virtual-museum') }}" class="text-gray-400 hover:text-gray-500">Virtual Museum</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <a href="{{ route('admin.virtual-museum.show', $object->virtualMuseum->museum_id) }}" class="ml-1 text-gray-400 hover:text-gray-500">{{ $object->virtualMuseum->nama }}</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 text-gray-500">{{ $object->nama }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $object->nama }}</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Object dari {{ $object->virtualMuseum->nama }} - {{ $object->situsPeninggalan->nama }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('admin.virtual-museum-object.edit', $object->object_id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Object
                    </a>
                    <a href="{{ route('admin.virtual-museum.show', $object->virtualMuseum->museum_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
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
                <span class="text-green-800 text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Information -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
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
                        @if($object->deskripsi)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $object->deskripsi }}</dd>
                        </div>
                        @endif

                        <!-- Virtual Museum -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Virtual Museum</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.virtual-museum.show', $object->virtualMuseum->museum_id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $object->virtualMuseum->nama }}
                                </a>
                            </dd>
                        </div>

                        <!-- Situs Peninggalan -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Situs Peninggalan</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.situs.show', $object->situsPeninggalan->situs_id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $object->situsPeninggalan->nama }}
                                </a>
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

            <!-- Files Section -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">File dan Asset</h2>
                </div>
                <div class="px-6 py-6 space-y-6">
                    
                    <!-- Gambar Real -->
                    @if($object->gambar_real)
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Gambar Real Object</h3>
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <img src="{{ asset('storage/' . $object->gambar_real) }}" 
                                 alt="Gambar Real {{ $object->nama }}"
                                 class="max-w-full h-auto rounded-lg shadow-sm max-h-64 object-cover">
                            <p class="mt-2 text-xs text-gray-500">{{ basename($object->gambar_real) }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- 3D Object File -->
                    @if($object->path_obj)
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">File 3D Object</h3>
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900">Model 3D</p>
                                <p class="text-sm text-gray-500 break-all">{{ basename($object->path_obj) }}</p>
                                <a href="{{ asset('storage/' . $object->path_obj) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">Download File</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- AR Pattern -->
                    @if($object->path_patt)
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">AR Pattern File</h3>
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m-6 0h8m-8 0a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900">AR Pattern</p>
                                <p class="text-sm text-gray-500 break-all">{{ basename($object->path_patt) }}</p>
                                <a href="{{ asset('storage/' . $object->path_patt) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">Download File</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$object->gambar_real && !$object->path_obj && !$object->path_patt)
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada file</h3>
                            <p class="mt-1 text-sm text-gray-500">Object ini belum memiliki file atau asset apapun.</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
                </div>
                <div class="px-6 py-6 space-y-3">
                    <a href="{{ route('admin.virtual-museum-object.edit', $object->object_id) }}" class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Object
                    </a>
                    
                    <form method="POST" action="{{ route('admin.virtual-museum-object.destroy', $object->object_id) }}" onsubmit="return confirm('Yakin ingin menghapus object ini? Semua file terkait akan ikut terhapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Object
                        </button>
                    </form>
                </div>
            </div>

            <!-- File Summary -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ringkasan File</h3>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Gambar Real</span>
                        <span class="text-sm font-semibold {{ $object->gambar_real ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $object->gambar_real ? 'Ada' : 'Tidak Ada' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Model 3D</span>
                        <span class="text-sm font-semibold {{ $object->path_obj ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $object->path_obj ? 'Ada' : 'Tidak Ada' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">AR Pattern</span>
                        <span class="text-sm font-semibold {{ $object->path_patt ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $object->path_patt ? 'Ada' : 'Tidak Ada' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>
</x-app-layout>
