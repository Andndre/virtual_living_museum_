<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 leading-tight">
                        {{ __('Tur 360° Panorama') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">Kelola Media Tur 360° Panorama untuk tiap Situs Peninggalan</p>
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
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-cyan-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-street-view text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Adegan (Scenes)</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_panorama_scenes'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bullseye text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Hotspots</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_hotspots'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Situs dengan Tur 360</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_situs_with_panorama'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Situs tanpa Tur 360</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_situs_without_panorama'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Situs List Grid -->
    @if($situsList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($situsList as $situs)
                <div class="bg-white rounded-lg shadow-sm border {{ $situs->panorama_count > 0 ? 'border-cyan-200' : 'border-gray-200' }} hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col">
                    <div class="h-40 overflow-hidden relative">
                        <img src="{{ $situs->getThumbnailUrlAttribute() }}" alt="{{ $situs->nama }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                            @if($situs->panorama_count > 0)
                                <span class="bg-cyan-600 text-white text-xs px-2 py-1 rounded-full font-semibold shadow-sm">
                                    <i class="fas fa-street-view mr-1"></i> {{ $situs->panorama_count }} Adegan
                               </span>
                            @else
                                <span class="bg-gray-600 bg-opacity-80 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Belum ada Tur
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-1">{{ $situs->nama }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $situs->deskripsi }}</p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-auto flex items-center gap-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.panorama.editor', $situs->situs_id) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i>
                                Buka Editor
                            </a>
                            <a href="{{ route('admin.situs.show', $situs->situs_id) }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors" title="Lihat Detail Situs">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $situsList->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="rounded-2xl bg-white p-12 text-center shadow-sm">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                <i class="fas fa-landmark text-2xl text-gray-400"></i>
            </div>
            <h3 class="mb-2 text-lg font-medium text-gray-900">Belum Ada Situs Peninggalan</h3>
            <p class="text-gray-600 mb-4">Tambahkan situs peninggalan terlebih dahulu untuk mulai membuat Tur 360° Panorama.</p>
            <a href="{{ route('admin.situs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Tambah Situs Peninggalan
            </a>
        </div>
    @endif
</div>
</x-app-layout>
