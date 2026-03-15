<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Tambah Materi Baru</h1>
                        <p class="mt-2 text-sm text-gray-600 sm:text-base">Menambahkan materi pembelajaran baru ke sistem
                        </p>
                    </div>
                    <a href="{{ route('admin.materi') }}"
                        class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 sm:w-auto">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Kembali ke Materi</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.materi.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6 px-4 py-5 sm:p-6">
                        <!-- Era -->
                        <div>
                            <label for="era_id" class="mb-1 block text-sm font-medium text-gray-700">
                                Era <span class="text-red-500">*</span>
                            </label>
                            <select name="era_id" id="era_id"
                                class="@error('era_id') border-red-300 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                required>
                                <option value="">Pilih Era</option>
                                @foreach ($eras as $era)
                                    <option value="{{ $era->era_id }}"
                                        {{ old('era_id') == $era->era_id ? 'selected' : '' }}>
                                        {{ $era->kode }}. {{ $era->nama }} ({{ $era->rentang_waktu }})
                                    </option>
                                @endforeach
                            </select>
                            @error('era_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bab -->
                        <div>
                            <label for="bab" class="mb-1 block text-sm font-medium text-gray-700">
                                Nomor Bab <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="bab" id="bab" min="1"
                                value="{{ old('bab') }}"
                                class="@error('bab') border-red-300 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                placeholder="Contoh: 1" required>
                            @error('bab')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Judul -->
                        <div>
                            <label for="judul" class="mb-1 block text-sm font-medium text-gray-700">
                                Judul Materi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                                class="@error('judul') border-red-300 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                placeholder="Masukkan judul materi" required>
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="mb-1 block text-sm font-medium text-gray-700">
                                Deskripsi Materi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="6"
                                class="@error('deskripsi') border-red-300 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                placeholder="Masukkan deskripsi lengkap materi pembelajaran" required>{{ old('deskripsi') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Jelaskan materi pembelajaran secara lengkap dan
                                detail. Urutan materi akan dibuat otomatis.</p>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6">
                        <div class="flex flex-col space-y-2 sm:flex-row sm:justify-end sm:space-x-3 sm:space-y-0">
                            <a href="{{ route('admin.materi') }}"
                                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Materi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
