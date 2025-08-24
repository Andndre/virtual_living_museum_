<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Tugas Baru</h1>
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
                    <form action="{{ route('admin.tugas.store', $materi->materi_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700">Judul Tugas <span class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <input type="text" name="judul" id="judul" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Masukkan judul tugas" value="{{ old('judul') }}" required>
                                </div>
                                @error('judul')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Tugas <span class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <textarea name="deskripsi" id="deskripsi" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Deskripsi detail tentang tugas ini">{{ old('deskripsi') }}</textarea>
                                </div>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gambar -->
                            <div>
                                <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar (Opsional)</label>
                                <div class="mt-1">
                                    <input type="file" name="gambar" id="gambar" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" accept="image/*">
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maks: 2MB</p>
                                </div>
                                @error('gambar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Materi ID Hidden -->
                            <input type="hidden" name="materi_id" value="{{ $materi->materi_id }}">

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Tugas
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
