<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Tugas</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Materi: {{ $materi->judul }}</p>
                    </div>
                    <a href="{{ route('admin.tugas', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali ke Daftar Tugas</span>
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <div class="p-4 sm:p-6">
                    <form action="{{ route('admin.tugas.update', [$materi->materi_id, $tugas->tugas_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700">Judul Tugas <span class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <input type="text" name="judul" id="judul" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Masukkan judul tugas" value="{{ old('judul', $tugas->judul) }}" required>
                                </div>
                                @error('judul')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Tugas <span class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <textarea name="deskripsi" id="deskripsi" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Deskripsi detail tentang tugas ini">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                                </div>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Current Image -->
                            @if($tugas->gambar)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $tugas->gambar) }}" alt="{{ $tugas->judul }}" class="h-32 w-auto object-cover rounded border border-gray-200">
                                </div>
                            </div>
                            @endif

                            <!-- Gambar -->
                            <div>
                                <label for="gambar" class="block text-sm font-medium text-gray-700">
                                    {{ $tugas->gambar ? 'Ganti Gambar (Opsional)' : 'Gambar (Opsional)' }}
                                </label>
                                <div class="mt-1">
                                    <input type="file" name="gambar" id="gambar" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" accept="image/*">
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maks: 2MB</p>
                                </div>
                                @error('gambar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hapus Gambar -->
                            @if($tugas->gambar)
                            <div>
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="hapus_gambar" name="hapus_gambar" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="hapus_gambar" class="font-medium text-gray-700">Hapus gambar saat ini</label>
                                        <p class="text-gray-500">Centang ini jika ingin menghapus gambar tanpa menggantinya</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
