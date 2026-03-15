<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="mb-4 flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('admin.materi') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-book mr-2"></i>Kelola Materi
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right mr-4 text-gray-400"></i>
                                <a href="{{ route('admin.pretest', $materi->materi_id) }}"
                                    class="text-gray-400 hover:text-gray-500">Pretest {{ $materi->judul }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right mr-4 text-gray-400"></i>
                                <span class="font-medium text-gray-900">Tambah Soal</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Tambah Soal Pretest</h1>
                    <p class="mt-2 text-sm text-gray-600 sm:text-base">Menambahkan soal pretest untuk materi:
                        <strong>{{ $materi->judul }}</strong></p>
                </div>

                <!-- Action Button -->
                <div class="flex">
                    <a href="{{ route('admin.pretest', $materi->materi_id) }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Kembali ke Pretest</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.pretest.store', $materi->materi_id) }}" method="POST">
                    @csrf
                    <div class="space-y-6 px-4 py-5 sm:p-6">
                        <!-- Pertanyaan -->
                        <div>
                            <label for="pertanyaan" class="mb-1 block text-sm font-medium text-gray-700">
                                Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="pertanyaan" id="pertanyaan" rows="4"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                placeholder="Masukkan pertanyaan pretest" required>{{ old('pertanyaan') }}</textarea>
                            @error('pertanyaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pilihan Jawaban -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Pilihan A -->
                            <div>
                                <label for="pilihan_a" class="mb-1 block text-sm font-medium text-gray-700">
                                    Pilihan A <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="pilihan_a" id="pilihan_a" value="{{ old('pilihan_a') }}"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                    placeholder="Masukkan pilihan A" required>
                                @error('pilihan_a')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilihan B -->
                            <div>
                                <label for="pilihan_b" class="mb-1 block text-sm font-medium text-gray-700">
                                    Pilihan B <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="pilihan_b" id="pilihan_b" value="{{ old('pilihan_b') }}"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                    placeholder="Masukkan pilihan B" required>
                                @error('pilihan_b')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilihan C -->
                            <div>
                                <label for="pilihan_c" class="mb-1 block text-sm font-medium text-gray-700">
                                    Pilihan C <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="pilihan_c" id="pilihan_c" value="{{ old('pilihan_c') }}"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                    placeholder="Masukkan pilihan C" required>
                                @error('pilihan_c')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilihan D -->
                            <div>
                                <label for="pilihan_d" class="mb-1 block text-sm font-medium text-gray-700">
                                    Pilihan D <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="pilihan_d" id="pilihan_d" value="{{ old('pilihan_d') }}"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                    placeholder="Masukkan pilihan D" required>
                                @error('pilihan_d')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilihan E -->
                            <div>
                                <label for="pilihan_e" class="mb-1 block text-sm font-medium text-gray-700">
                                    Pilihan E <span class="text-xs text-gray-400">(opsional)</span>
                                </label>
                                <input type="text" name="pilihan_e" id="pilihan_e" value="{{ old('pilihan_e') }}"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                    placeholder="Masukkan pilihan E">
                                @error('pilihan_e')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Jawaban Benar -->
                        <div>
                            <label class="mb-3 block text-sm font-medium text-gray-700">
                                Jawaban Benar <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3 md:grid-cols-5">
                                <label
                                    class="flex cursor-pointer items-center rounded-md border border-gray-300 p-3 transition-colors hover:bg-gray-50">
                                    <input type="radio" name="jawaban_benar" value="A" class="mr-3 text-blue-600"
                                        {{ old('jawaban_benar') === 'A' ? 'checked' : '' }} required>
                                    <span class="text-sm font-medium">Pilihan A</span>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center rounded-md border border-gray-300 p-3 transition-colors hover:bg-gray-50">
                                    <input type="radio" name="jawaban_benar" value="B"
                                        class="mr-3 text-blue-600" {{ old('jawaban_benar') === 'B' ? 'checked' : '' }}
                                        required>
                                    <span class="text-sm font-medium">Pilihan B</span>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center rounded-md border border-gray-300 p-3 transition-colors hover:bg-gray-50">
                                    <input type="radio" name="jawaban_benar" value="C"
                                        class="mr-3 text-blue-600" {{ old('jawaban_benar') === 'C' ? 'checked' : '' }}
                                        required>
                                    <span class="text-sm font-medium">Pilihan C</span>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center rounded-md border border-gray-300 p-3 transition-colors hover:bg-gray-50">
                                    <input type="radio" name="jawaban_benar" value="D"
                                        class="mr-3 text-blue-600" {{ old('jawaban_benar') === 'D' ? 'checked' : '' }}
                                        required>
                                    <span class="text-sm font-medium">Pilihan D</span>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center rounded-md border border-gray-300 p-3 transition-colors hover:bg-gray-50">
                                    <input type="radio" name="jawaban_benar" value="E"
                                        class="mr-3 text-blue-600" {{ old('jawaban_benar') === 'E' ? 'checked' : '' }}
                                        required>
                                    <span class="text-sm font-medium">Pilihan E</span>
                                </label>
                            </div>
                            @error('jawaban_benar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="space-x-3 bg-gray-50 px-4 py-3 text-right sm:px-6">
                        <a href="{{ route('admin.pretest', $materi->materi_id) }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
