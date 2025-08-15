<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('admin.materi') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-book mr-2"></i>Kelola Materi
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                                <span class="text-gray-900 font-medium">Posttest {{ $materi->judul }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Posttest</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Mengelola soal posttest untuk materi: <strong>{{ $materi->judul }}</strong></p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.posttest.create', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            <span class="hidden sm:inline">Tambah Soal</span>
                            <span class="sm:hidden">Tambah</span>
                        </a>
                        <a href="{{ route('admin.materi.show', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="hidden sm:inline">Kembali ke Materi</span>
                            <span class="sm:hidden">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-question-circle text-xl sm:text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Soal</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $posttests->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-xl sm:text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Jawaban A</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $posttests->where('jawaban_benar', 'A')->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-xl sm:text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Jawaban B</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $posttests->where('jawaban_benar', 'B')->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-double text-xl sm:text-2xl text-purple-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Jawaban C & D</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $posttests->whereIn('jawaban_benar', ['C', 'D'])->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            @if($posttests->count() > 0)
                <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Soal Posttest</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Klik pada soal untuk melihat detail, atau gunakan tombol aksi untuk mengelola.
                                </p>
                            </div>
                            <div class="mt-3 sm:mt-0 text-sm text-gray-500">
                                Total: {{ $posttests->count() }} soal
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop View -->
                    <div class="hidden lg:block">
                        <ul class="divide-y divide-gray-200">
                            @foreach($posttests as $index => $posttest)
                            <li class="px-4 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-start space-x-4 flex-1">
                                        <!-- Nomor Soal -->
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Konten Soal -->
                                        <div class="flex-1 min-w-0">
                                            <div class="focus:outline-none">
                                                <!-- Pertanyaan -->
                                                <p class="text-sm font-medium text-gray-900 mb-2">
                                                    {{ Str::limit($posttest->pertanyaan, 100, '...') }}
                                                </p>
                                                
                                                <!-- Pilihan Jawaban -->
                                                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                                                    <div class="flex items-center">
                                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full {{ $posttest->jawaban_benar === 'A' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} mr-2 text-xs font-medium">A</span>
                                                        <span class="truncate">{{ Str::limit($posttest->pilihan_a, 30) }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full {{ $posttest->jawaban_benar === 'B' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} mr-2 text-xs font-medium">B</span>
                                                        <span class="truncate">{{ Str::limit($posttest->pilihan_b, 30) }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full {{ $posttest->jawaban_benar === 'C' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} mr-2 text-xs font-medium">C</span>
                                                        <span class="truncate">{{ Str::limit($posttest->pilihan_c, 30) }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full {{ $posttest->jawaban_benar === 'D' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} mr-2 text-xs font-medium">D</span>
                                                        <span class="truncate">{{ Str::limit($posttest->pilihan_d, 30) }}</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Metadata -->
                                                <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Jawaban: {{ $posttest->jawaban_benar }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $posttest->created_at->format('d M Y') }}
                                                    </span>
                                                    @if($posttest->updated_at != $posttest->created_at)
                                                    <span class="flex items-center">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Diperbarui {{ $posttest->updated_at->diffForHumans() }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.posttest.show', [$materi->materi_id, $posttest->posttest_id]) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </a>
                                        <a href="{{ route('admin.posttest.edit', [$materi->materi_id, $posttest->posttest_id]) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-blue-300 shadow-sm text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.posttest.destroy', [$materi->materi_id, $posttest->posttest_id]) }}" 
                                              method="POST" 
                                              class="inline-block" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs leading-4 font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                                <i class="fas fa-trash mr-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Mobile View -->
                    <div class="lg:hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($posttests as $index => $posttest)
                            <div class="p-4">
                                <div class="flex items-start space-x-3">
                                    <!-- Nomor Soal -->
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Pertanyaan -->
                                        <p class="text-sm font-medium text-gray-900 mb-3">
                                            {{ Str::limit($posttest->pertanyaan, 80, '...') }}
                                        </p>
                                        
                                        <!-- Pilihan Jawaban - Compact -->
                                        <div class="space-y-1 mb-3">
                                            <div class="flex items-center text-xs">
                                                <span class="inline-flex items-center justify-center h-4 w-4 rounded-full {{ $posttest->jawaban_benar === 'A' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} mr-2 text-xs font-medium">A</span>
                                                <span class="truncate text-gray-600">{{ Str::limit($posttest->pilihan_a, 40) }}</span>
                                            </div>
                                            <div class="flex items-center text-xs">
                                                <span class="inline-flex items-center justify-center h-4 w-4 rounded-full {{ $posttest->jawaban_benar === 'B' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} mr-2 text-xs font-medium">B</span>
                                                <span class="truncate text-gray-600">{{ Str::limit($posttest->pilihan_b, 40) }}</span>
                                            </div>
                                            <div class="flex items-center text-xs">
                                                <span class="inline-flex items-center justify-center h-4 w-4 rounded-full {{ $posttest->jawaban_benar === 'C' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} mr-2 text-xs font-medium">C</span>
                                                <span class="truncate text-gray-600">{{ Str::limit($posttest->pilihan_c, 40) }}</span>
                                            </div>
                                            <div class="flex items-center text-xs">
                                                <span class="inline-flex items-center justify-center h-4 w-4 rounded-full {{ $posttest->jawaban_benar === 'D' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} mr-2 text-xs font-medium">D</span>
                                                <span class="truncate text-gray-600">{{ Str::limit($posttest->pilihan_d, 40) }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Metadata -->
                                        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 mb-3">
                                            <span class="flex items-center">
                                                <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                                {{ $posttest->jawaban_benar }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $posttest->created_at->format('d/m') }}
                                            </span>
                                        </div>
                                        
                                        <!-- Mobile Actions -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.posttest.show', [$materi->materi_id, $posttest->posttest_id]) }}" 
                                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.posttest.edit', [$materi->materi_id, $posttest->posttest_id]) }}" 
                                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.posttest.destroy', [$materi->materi_id, $posttest->posttest_id]) }}" 
                                                  method="POST" 
                                                  class="flex-1" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-lg sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-12 text-center">
                        <div class="mx-auto h-20 w-20 sm:h-24 sm:w-24 text-gray-400">
                            <i class="fas fa-question-circle text-5xl sm:text-6xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Soal Posttest</h3>
                        <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto px-4">
                            Mulai membuat soal posttest untuk materi "{{ $materi->judul }}" dengan mengklik tombol di bawah.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('admin.posttest.create', $materi->materi_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                <span class="hidden sm:inline">Tambah Soal Pertama</span>
                                <span class="sm:hidden">Tambah Soal</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
