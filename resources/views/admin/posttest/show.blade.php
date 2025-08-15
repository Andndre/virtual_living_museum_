<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                                <a href="{{ route('admin.posttest', $posttest->materi_id) }}" class="text-gray-400 hover:text-gray-500">Posttest {{ $posttest->materi->judul }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                                <span class="text-gray-900 font-medium">Detail Soal</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="mb-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Soal Posttest</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Detail soal posttest untuk materi: <strong>{{ $posttest->materi->judul }}</strong></p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-3">
                    <a href="{{ route('admin.posttest.edit', [$posttest->materi_id, $posttest->posttest_id]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        <span class="hidden sm:inline">Edit Soal</span>
                        <span class="sm:hidden">Edit</span>
                    </a>
                    <form action="{{ route('admin.posttest.destroy', [$posttest->materi_id, $posttest->posttest_id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            <span class="hidden sm:inline">Hapus Soal</span>
                            <span class="sm:hidden">Hapus</span>
                        </button>
                    </form>
                    <a href="{{ route('admin.posttest', $posttest->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Kembali ke Posttest</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-6">
                <!-- Soal -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pertanyaan</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 leading-relaxed">{{ $posttest->pertanyaan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pilihan Jawaban -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pilihan Jawaban</h3>
                        <div class="space-y-3">
                            <!-- Pilihan A -->
                            <div class="flex items-center p-4 border rounded-lg {{ $posttest->jawaban_benar === 'A' ? 'border-green-500 bg-green-50' : 'border-gray-300 bg-gray-50' }}">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $posttest->jawaban_benar === 'A' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }} font-medium">
                                        A
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-gray-900">{{ $posttest->pilihan_a }}</p>
                                </div>
                                @if($posttest->jawaban_benar === 'A')
                                    <div class="ml-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Jawaban Benar
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Pilihan B -->
                            <div class="flex items-center p-4 border rounded-lg {{ $posttest->jawaban_benar === 'B' ? 'border-green-500 bg-green-50' : 'border-gray-300 bg-gray-50' }}">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $posttest->jawaban_benar === 'B' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }} font-medium">
                                        B
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-gray-900">{{ $posttest->pilihan_b }}</p>
                                </div>
                                @if($posttest->jawaban_benar === 'B')
                                    <div class="ml-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Jawaban Benar
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Pilihan C -->
                            <div class="flex items-center p-4 border rounded-lg {{ $posttest->jawaban_benar === 'C' ? 'border-green-500 bg-green-50' : 'border-gray-300 bg-gray-50' }}">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $posttest->jawaban_benar === 'C' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }} font-medium">
                                        C
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-gray-900">{{ $posttest->pilihan_c }}</p>
                                </div>
                                @if($posttest->jawaban_benar === 'C')
                                    <div class="ml-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Jawaban Benar
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Pilihan D -->
                            <div class="flex items-center p-4 border rounded-lg {{ $posttest->jawaban_benar === 'D' ? 'border-green-500 bg-green-50' : 'border-gray-300 bg-gray-50' }}">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $posttest->jawaban_benar === 'D' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }} font-medium">
                                        D
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-gray-900">{{ $posttest->pilihan_d }}</p>
                                </div>
                                @if($posttest->jawaban_benar === 'D')
                                    <div class="ml-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Jawaban Benar
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Soal</h3>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-blue-900">ID Soal</dt>
                                    <dd class="mt-1 text-sm text-blue-700">{{ $posttest->posttest_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-blue-900">Materi</dt>
                                    <dd class="mt-1 text-sm text-blue-700">{{ $posttest->materi->judul }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-blue-900">Jawaban Benar</dt>
                                    <dd class="mt-1 text-sm text-blue-700">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Pilihan {{ $posttest->jawaban_benar }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-blue-900">Dibuat pada</dt>
                                    <dd class="mt-1 text-sm text-blue-700">{{ $posttest->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                @if($posttest->updated_at != $posttest->created_at)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-blue-900">Terakhir diperbarui</dt>
                                    <dd class="mt-1 text-sm text-blue-700">{{ $posttest->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Navigasi Soal Lain -->
                @if($previousPosttest || $nextPosttest)
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Navigasi Soal</h3>
                        <div class="flex justify-between">
                            <div>
                                @if($previousPosttest)
                                    <a href="{{ route('admin.posttest.show', [$posttest->materi_id, $previousPosttest->posttest_id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-left mr-2"></i>
                                        Soal Sebelumnya
                                    </a>
                                @endif
                            </div>
                            <div>
                                @if($nextPosttest)
                                    <a href="{{ route('admin.posttest.show', [$posttest->materi_id, $nextPosttest->posttest_id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        Soal Selanjutnya
                                        <i class="fas fa-chevron-right ml-2"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
