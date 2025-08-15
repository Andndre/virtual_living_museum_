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
                                <span class="text-gray-900 font-medium">Edit Soal</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="mb-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Soal Posttest</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Mengedit soal posttest untuk materi: <strong>{{ $posttest->materi->judul }}</strong></p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-3">
                    <a href="{{ route('admin.posttest.show', [$posttest->materi_id, $posttest->posttest_id]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        <span class="hidden sm:inline">Lihat Detail</span>
                        <span class="sm:hidden">Detail</span>
                    </a>
                    <a href="{{ route('admin.posttest', $posttest->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Kembali ke Posttest</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.posttest.update', [$posttest->materi_id, $posttest->posttest_id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-4 py-5 sm:p-6 space-y-6">
                        <!-- Pertanyaan -->
                        <div>
                            <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-1">
                                Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="pertanyaan" 
                                id="pertanyaan" 
                                rows="4" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan pertanyaan posttest"
                                required
                            >{{ old('pertanyaan', $posttest->pertanyaan) }}</textarea>
                            @error('pertanyaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pilihan Jawaban -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pilihan A -->
                            <div>
                                <label for="pilihan_a" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilihan A <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="pilihan_a" 
                                    id="pilihan_a" 
                                    value="{{ old('pilihan_a', $posttest->pilihan_a) }}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan pilihan A"
                                    required
                                >
                                @error('pilihan_a')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilihan B -->
                            <div>
                                <label for="pilihan_b" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilihan B <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="pilihan_b" 
                                    id="pilihan_b" 
                                    value="{{ old('pilihan_b', $posttest->pilihan_b) }}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan pilihan B"
                                    required
                                >
                                @error('pilihan_b')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilihan C -->
                            <div>
                                <label for="pilihan_c" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilihan C <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="pilihan_c" 
                                    id="pilihan_c" 
                                    value="{{ old('pilihan_c', $posttest->pilihan_c) }}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan pilihan C"
                                    required
                                >
                                @error('pilihan_c')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilihan D -->
                            <div>
                                <label for="pilihan_d" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilihan D <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="pilihan_d" 
                                    id="pilihan_d" 
                                    value="{{ old('pilihan_d', $posttest->pilihan_d) }}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan pilihan D"
                                    required
                                >
                                @error('pilihan_d')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Jawaban Benar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Jawaban Benar <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors {{ old('jawaban_benar', $posttest->jawaban_benar) === 'A' ? 'bg-green-50 border-green-300' : '' }}">
                                    <input type="radio" name="jawaban_benar" value="A" class="mr-3 text-blue-600" {{ old('jawaban_benar', $posttest->jawaban_benar) === 'A' ? 'checked' : '' }} required>
                                    <span class="text-sm font-medium">Pilihan A</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors {{ old('jawaban_benar', $posttest->jawaban_benar) === 'B' ? 'bg-green-50 border-green-300' : '' }}">
                                    <input type="radio" name="jawaban_benar" value="B" class="mr-3 text-blue-600" {{ old('jawaban_benar', $posttest->jawaban_benar) === 'B' ? 'checked' : '' }} required>
                                    <span class="text-sm font-medium">Pilihan B</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors {{ old('jawaban_benar', $posttest->jawaban_benar) === 'C' ? 'bg-green-50 border-green-300' : '' }}">
                                    <input type="radio" name="jawaban_benar" value="C" class="mr-3 text-blue-600" {{ old('jawaban_benar', $posttest->jawaban_benar) === 'C' ? 'checked' : '' }} required>
                                    <span class="text-sm font-medium">Pilihan C</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors {{ old('jawaban_benar', $posttest->jawaban_benar) === 'D' ? 'bg-green-50 border-green-300' : '' }}">
                                    <input type="radio" name="jawaban_benar" value="D" class="mr-3 text-blue-600" {{ old('jawaban_benar', $posttest->jawaban_benar) === 'D' ? 'checked' : '' }} required>
                                    <span class="text-sm font-medium">Pilihan D</span>
                                </label>
                            </div>
                            @error('jawaban_benar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Informasi Soal</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Dibuat pada: {{ $posttest->created_at->format('d M Y H:i') }}</p>
                                        @if($posttest->updated_at != $posttest->created_at)
                                            <p>Terakhir diperbarui: {{ $posttest->updated_at->format('d M Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                        <a href="{{ route('admin.posttest', $posttest->materi_id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
