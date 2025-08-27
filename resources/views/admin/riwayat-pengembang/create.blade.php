<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Tambah Riwayat Pengembangan
                    </h2>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('admin.riwayat-pengembang') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('admin.riwayat-pengembang.store') }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <!-- Judul -->
                                <div>
                                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                                    <div class="mt-1">
                                        <input type="text" name="judul" id="judul" required
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               value="{{ old('judul') }}">
                                        @error('judul')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tahun Mulai -->
                                <div>
                                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun Mulai</label>
                                    <div class="mt-1">
                                        <input type="date" name="tahun" id="tahun" required
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               value="{{ old('tahun') }}">
                                        @error('tahun')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tahun Selesai -->
                                <div>
                                    <label for="tahun_selesai" class="block text-sm font-medium text-gray-700">Tahun Selesai (Opsional)</label>
                                    <div class="mt-1">
                                        <input type="date" name="tahun_selesai" id="tahun_selesai"
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               value="{{ old('tahun_selesai') }}">
                                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika masih berlangsung</p>
                                        @error('tahun_selesai')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="pt-5">
                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-save mr-2"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
