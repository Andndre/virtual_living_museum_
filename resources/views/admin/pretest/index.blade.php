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
                                <span class="text-gray-500">{{ $materi->judul }}</span>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                                <span class="text-gray-900 font-medium">Pretest</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Soal Pretest</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Kelola soal pretest untuk materi: <strong>{{ $materi->judul }}</strong></p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.pretest.create', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
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

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-question-circle text-xl sm:text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Soal</dt>
                                    <dd class="text-lg sm:text-lg font-medium text-gray-900">{{ $pretests->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-xl sm:text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Soal A</dt>
                                    <dd class="text-lg sm:text-lg font-medium text-gray-900">{{ $pretests->where('jawaban_benar', 'A')->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-xl sm:text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Soal B</dt>
                                    <dd class="text-lg sm:text-lg font-medium text-gray-900">{{ $pretests->where('jawaban_benar', 'B')->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-xl sm:text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Soal C & D</dt>
                                    <dd class="text-lg sm:text-lg font-medium text-gray-900">{{ $pretests->whereIn('jawaban_benar', ['C', 'D'])->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:p-6">
                    @forelse($pretests as $index => $pretest)
                        <div class="mb-4 sm:mb-6 p-3 sm:p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3 space-y-2 sm:space-y-0">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Soal {{ $pretests->firstItem() + $index }}
                                    </span>
                                </div>
                                <div class="flex space-x-2 self-end sm:self-auto">
                                    <a href="{{ route('admin.pretest.show', [$materi->materi_id, $pretest->pretest_id]) }}" class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.pretest.edit', [$materi->materi_id, $pretest->pretest_id]) }}" class="p-2 text-yellow-600 hover:text-yellow-900 hover:bg-yellow-50 rounded" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.pretest.destroy', [$materi->materi_id, $pretest->pretest_id]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded" title="Hapus">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Question -->
                            <div class="mb-4">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2 leading-relaxed">{{ $pretest->pertanyaan }}</h3>
                            </div>
                            
                            <!-- Options -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 mb-3">
                                <div class="flex items-start space-x-2 p-2 rounded {{ $pretest->jawaban_benar === 'A' ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex-shrink-0 mt-0.5">A</span>
                                    <span class="text-sm text-gray-900">{{ $pretest->pilihan_a }}</span>
                                    @if($pretest->jawaban_benar === 'A')
                                        <i class="fas fa-check text-green-600 ml-auto"></i>
                                    @endif
                                </div>
                                <div class="flex items-start space-x-2 p-2 rounded {{ $pretest->jawaban_benar === 'B' ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex-shrink-0 mt-0.5">B</span>
                                    <span class="text-sm text-gray-900">{{ $pretest->pilihan_b }}</span>
                                    @if($pretest->jawaban_benar === 'B')
                                        <i class="fas fa-check text-green-600 ml-auto"></i>
                                    @endif
                                </div>
                                <div class="flex items-start space-x-2 p-2 rounded {{ $pretest->jawaban_benar === 'C' ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex-shrink-0 mt-0.5">C</span>
                                    <span class="text-sm text-gray-900">{{ $pretest->pilihan_c }}</span>
                                    @if($pretest->jawaban_benar === 'C')
                                        <i class="fas fa-check text-green-600 ml-auto"></i>
                                    @endif
                                </div>
                                <div class="flex items-start space-x-2 p-2 rounded {{ $pretest->jawaban_benar === 'D' ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex-shrink-0 mt-0.5">D</span>
                                    <span class="text-sm text-gray-900">{{ $pretest->pilihan_d }}</span>
                                    @if($pretest->jawaban_benar === 'D')
                                        <i class="fas fa-check text-green-600 ml-auto"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-question-circle text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada soal pretest</h3>
                            <p class="text-gray-500 mb-4">Mulai tambahkan soal pretest untuk materi ini.</p>
                            <a href="{{ route('admin.pretest.create', $materi->materi_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 hover:text-blue-500">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Soal Pertama
                            </a>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($pretests->hasPages())
                        <div class="mt-6">
                            {{ $pretests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
