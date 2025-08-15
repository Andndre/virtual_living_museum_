<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Materi</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Informasi lengkap materi pembelajaran</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.materi.edit', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            <span class="hidden sm:inline">Edit Materi</span>
                            <span class="sm:hidden">Edit</span>
                        </a>
                        <a href="{{ route('admin.materi') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="hidden sm:inline">Kembali ke Materi</span>
                            <span class="sm:hidden">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Basic Info Card -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Materi</h3>
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Urutan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            #{{ $materi->urutan }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $materi->created_at->format('d M Y, H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $materi->updated_at->format('d M Y, H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Title and Description -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $materi->judul }}</h3>
                            <div class="prose max-w-none">
                                <p class="text-gray-700 leading-relaxed">{{ $materi->deskripsi }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Data -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Data Terkait</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Ebook -->
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">E-book</h4>
                                            <p class="text-sm text-gray-500">{{ $materi->ebook()->count() }} e-book</p>
                                        </div>
                                        <i class="fas fa-book text-blue-600 text-xl"></i>
                                    </div>
                                </div>

                                <!-- Situs Peninggalan -->
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Situs Peninggalan</h4>
                                            <p class="text-sm text-gray-500">{{ $materi->situsPeninggalan()->count() }} situs</p>
                                        </div>
                                        <i class="fas fa-monument text-yellow-600 text-xl"></i>
                                    </div>
                                </div>

                                <!-- Jawaban User -->
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Jawaban User</h4>
                                            <p class="text-sm text-gray-500">{{ $materi->jawabanUser()->count() }} jawaban</p>
                                        </div>
                                        <i class="fas fa-clipboard-check text-green-600 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ebook List -->
                    @if($materi->ebook()->count() > 0)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Daftar E-book</h3>
                                <a href="{{ route('admin.ebook', $materi->materi_id) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                    Lihat Semua →
                                </a>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($materi->ebook()->take(5)->get() as $ebook)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-book text-purple-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $ebook->judul }}</h4>
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-file-pdf mr-1"></i>
                                                {{ basename($ebook->path_file) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($ebook->path_file && file_exists(storage_path('app/public/' . $ebook->path_file)))
                                            <a href="{{ asset('storage/' . $ebook->path_file) }}" target="_blank" class="text-blue-600 hover:text-blue-900" title="Buka E-book">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-red-500" title="File tidak ditemukan">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                
                                @if($materi->ebook()->count() > 5)
                                <div class="text-center pt-2">
                                    <a href="{{ route('admin.ebook', $materi->materi_id) }}" class="text-sm text-gray-500 hover:text-gray-700">
                                        Dan {{ $materi->ebook()->count() - 5 }} e-book lainnya...
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="text-center">
                                <i class="fas fa-book text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada E-book</h3>
                                <p class="text-sm text-gray-500 mb-4">Belum ada e-book yang ditambahkan untuk materi ini.</p>
                                <a href="{{ route('admin.ebook.create', $materi->materi_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah E-book Pertama
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Side Statistics -->
                <div class="space-y-6">
                    <!-- Pretest Stats -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Pretest</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $materi->pretest()->count() }}</p>
                                    <p class="text-xs text-gray-500">soal tersedia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Posttest Stats -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-tasks text-green-600 text-2xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Posttest</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $materi->posttest()->count() }}</p>
                                    <p class="text-xs text-gray-500">soal tersedia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ebook Stats -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-book text-purple-600 text-2xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Ebook</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $materi->ebook()->count() }}</p>
                                    <p class="text-xs text-gray-500">ebook tersedia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Kelola Konten</h4>
                            <div class="space-y-2">
                                <a href="{{ route('admin.pretest', $materi->materi_id) }}" class="w-full inline-flex justify-center items-center px-3 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100">
                                    <i class="fas fa-clipboard-list mr-2"></i>
                                    Kelola Pretest ({{ $materi->pretest()->count() }})
                                </a>
                                <a href="{{ route('admin.posttest', $materi->materi_id) }}" class="w-full inline-flex justify-center items-center px-3 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100">
                                    <i class="fas fa-tasks mr-2"></i>
                                    Kelola Posttest ({{ $materi->posttest()->count() }})
                                </a>
                                <a href="{{ route('admin.ebook', $materi->materi_id) }}" class="w-full inline-flex justify-center items-center px-3 py-2 border border-purple-300 shadow-sm text-sm font-medium rounded-md text-purple-700 bg-purple-50 hover:bg-purple-100">
                                    <i class="fas fa-book mr-2"></i>
                                    Kelola Ebook ({{ $materi->ebook()->count() }})
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Other Actions -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Aksi Lainnya</h4>
                            <div class="space-y-2">
                                <a href="{{ route('admin.materi.edit', $materi->materi_id) }}" class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Materi
                                </a>
                                <form action="{{ route('admin.materi.destroy', $materi->materi_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                        <i class="fas fa-trash mr-2"></i>
                                        Hapus Materi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
