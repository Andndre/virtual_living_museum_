<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Tambah Label Annotation</h1>
                <p class="mt-2 text-sm text-gray-600">Isi form di bawah untuk menambahkan label annotation baru</p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('admin.annotations.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="museum_id" class="block text-sm font-medium text-gray-700">Museum <span class="text-red-500">*</span></label>
                                <select name="museum_id" id="museum_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    <option value="">-- Pilih Museum --</option>
                                    @foreach($museums as $museum)
                                        <option value="{{ $museum->museum_id }}" {{ old('museum_id', $selectedMuseum?->museum_id) == $museum->museum_id ? 'selected' : '' }}>
                                            {{ $museum->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="label" class="block text-sm font-medium text-gray-700">Label <span class="text-red-500">*</span></label>
                                <input type="text" name="label" id="label"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('label') }}" required maxlength="100"
                                       placeholder="Contoh: Pintu Gerbang Utama">
                                <p class="mt-1 text-sm text-gray-500">Maksimal 100 karakter</p>
                            </div>

                            <div>
                                <p class="block text-sm font-medium text-gray-700 mb-3">Posisi (X, Y, Z) <span class="text-red-500">*</span></p>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label for="position_x" class="block text-xs font-medium text-gray-500 mb-1">X</label>
                                        <input type="number" name="position_x" id="position_x" step="0.000001"
                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                               value="{{ old('position_x', 0) }}" required>
                                    </div>
                                    <div>
                                        <label for="position_y" class="block text-xs font-medium text-gray-500 mb-1">Y</label>
                                        <input type="number" name="position_y" id="position_y" step="0.000001"
                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                               value="{{ old('position_y', 0) }}" required>
                                    </div>
                                    <div>
                                        <label for="position_z" class="block text-xs font-medium text-gray-500 mb-1">Z</label>
                                        <input type="number" name="position_z" id="position_z" step="0.000001"
                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                               value="{{ old('position_z', 0) }}" required>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Koordinat relatif terhadap model 3D. Gunakan nilai antara -10 hingga 10.</p>
                            </div>

                            <div>
                                <label for="display_order" class="block text-sm font-medium text-gray-700">Display Order</label>
                                <input type="number" name="display_order" id="display_order"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('display_order', 0) }}" min="0">
                                <p class="mt-1 text-sm text-gray-500">Urutan tampil (lebih kecil = lebih dulu)</p>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="is_visible" value="0">
                                <input type="checkbox" name="is_visible" id="is_visible" value="1"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ old('is_visible', '1') == '1' ? 'checked' : '' }}>
                                <label for="is_visible" class="ml-2 block text-sm text-gray-700">Visible (tampil di scene)</label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.annotations.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
