<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="mb-4 flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right mr-4 text-gray-400"></i>
                                <a href="{{ route('admin.situs') }}" class="text-gray-400 hover:text-gray-500">Kelola
                                    Situs</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right mr-4 text-gray-400"></i>
                                <span class="font-medium text-gray-900">Tambah Situs</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Tambah Situs Peninggalan</h1>
                    <p class="mt-2 text-sm text-gray-600 sm:text-base">Menambahkan situs peninggalan baru ke dalam
                        sistem</p>
                </div>

                <!-- Action Button -->
                <div class="flex">
                    <a href="{{ route('admin.situs') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Kembali ke Situs</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="relative mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="relative mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <div class="border border-gray-200 bg-white shadow-lg sm:rounded-lg">
                <form action="{{ route('admin.situs.store') }}" method="POST" class="space-y-6"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6 px-4 py-5 sm:p-6">
                        <!-- Nama Situs -->
                        <div>
                            <label for="nama" class="mb-1 block text-sm font-medium text-gray-700">
                                Nama Situs <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                class="@error('nama') border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                placeholder="Contoh: Candi Borobudur" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="mb-1 block text-sm font-medium text-gray-700">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" id="alamat" rows="3"
                                class="@error('alamat') border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                placeholder="Contoh: Jl. Badrawati, Borobudur, Magelang, Jawa Tengah" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Koordinat dengan Interactive Map -->
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Koordinat Lokasi <span class="text-red-500">*</span>
                                </label>
                                <p class="mb-3 text-sm text-gray-600">Klik pada peta atau masukkan koordinat secara
                                    manual</p>
                            </div>

                            <!-- Interactive Map -->
                            <div class="overflow-hidden rounded-md border border-gray-300">
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            </div>

                            <!-- Coordinate Inputs -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="lat" class="mb-1 block text-sm font-medium text-gray-700">
                                        Latitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="lat" id="lat" step="any"
                                        value="{{ old('lat', '') }}"
                                        class="@error('lat') border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                        placeholder="Contoh: -7.608543" required>
                                    @error('lat')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="lng" class="mb-1 block text-sm font-medium text-gray-700">
                                        Longitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="lng" id="lng" step="any"
                                        value="{{ old('lng', '') }}"
                                        class="@error('lng') border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                        placeholder="Contoh: 110.203751" required>
                                    @error('lng')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Map Controls -->
                            <div class="flex flex-wrap gap-2">
                                <button type="button" id="getCurrentLocation"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-crosshairs mr-2"></i>
                                    Lokasi Saya
                                </button>
                                <button type="button" id="searchLocation"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-search mr-2"></i>
                                    Cari Lokasi
                                </button>
                            </div>
                        </div>

                        <!-- Materi Terkait -->
                        <div>
                            <label for="materi_id" class="mb-1 block text-sm font-medium text-gray-700">
                                Materi Terkait <span class="text-red-500">*</span>
                            </label>
                            @php
                                $groupedMateris = $materis->groupBy(fn($m) => $m->era_id ?? 0);
                            @endphp
                            <select name="materi_id" id="materi_id"
                                class="@error('materi_id') border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                required>
                                <option value="">Pilih Materi</option>
                                @foreach ($groupedMateris as $eraId => $eraGroup)
                                    @php $era = $eraGroup->first()->era; @endphp
                                    <optgroup label="{{ $era ? $era->kode . '. ' . $era->nama : 'Umum' }}">
                                        @foreach ($eraGroup as $materi)
                                            <option value="{{ $materi->materi_id }}"
                                                {{ old('materi_id') == $materi->materi_id ? 'selected' : '' }}>
                                                {{ $materi->bab ? 'Bab ' . $materi->bab . ' — ' : '' }}{{ $materi->judul }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('materi_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="mb-1 block text-sm font-medium text-gray-700">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="6"
                                class="@error('deskripsi') border-red-500 @enderror w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                                placeholder="Jelaskan tentang situs peninggalan ini, sejarah, fungsi, dan hal menarik lainnya..." required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thumbnail Image -->
                        <div>
                            <label for="thumbnail" class="mb-1 block text-sm font-medium text-gray-700">
                                Gambar Thumbnail
                            </label>
                            <div class="mt-1 flex items-center">
                                <div id="thumbnail-preview" class="mb-3 hidden">
                                    <img src="#" alt="Thumbnail Preview"
                                        class="h-32 w-auto rounded-lg border border-gray-300 object-cover">
                                    <button type="button" id="remove-thumbnail"
                                        class="mt-1 text-xs text-red-600 hover:text-red-800">
                                        <i class="fas fa-times mr-1"></i> Hapus
                                    </button>
                                </div>
                                <div class="mt-1 w-full">
                                    <label
                                        class="block w-full cursor-pointer rounded-md border border-dashed border-gray-300 px-3 py-2 hover:bg-gray-50">
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-cloud-upload-alt mr-2 text-gray-400"></i>
                                            <span class="text-sm text-gray-500">Klik untuk unggah gambar</span>
                                        </div>
                                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                                            class="sr-only" onchange="previewThumbnail(this)">
                                    </label>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Ukuran maks: 2MB.</p>
                            @error('thumbnail')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col justify-end space-y-2 border-t border-gray-200 bg-gray-50 px-4 py-4 sm:flex-row sm:space-x-3 sm:space-y-0 sm:px-6">
                        <a href="{{ route('admin.situs') }}"
                            class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 sm:w-auto">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Situs
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default coordinates (Indonesia center)
            const defaultLat = {{ old('lat', '-2.5489') }};
            const defaultLng = {{ old('lng', '118.0149') }};

            // Initialize map
            const map = L.map('map').setView([defaultLat, defaultLng], 5);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Initialize marker
            let marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            // Update inputs when marker is moved
            function updateInputs(lat, lng) {
                document.getElementById('lat').value = lat.toFixed(8);
                document.getElementById('lng').value = lng.toFixed(8);
            }

            // Update marker when inputs change
            function updateMarker() {
                const lat = parseFloat(document.getElementById('lat').value);
                const lng = parseFloat(document.getElementById('lng').value);

                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng], map.getZoom());
                }
            }

            // Event listeners
            marker.on('dragend', function() {
                const position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            document.getElementById('lat').addEventListener('input', updateMarker);
            document.getElementById('lng').addEventListener('input', updateMarker);

            // Get current location button
            document.getElementById('getCurrentLocation').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 15);
                        updateInputs(lat, lng);
                    }, function() {
                        alert(
                            'Tidak dapat mengakses lokasi Anda. Pastikan GPS diaktifkan dan izin lokasi diberikan.');
                    });
                } else {
                    alert('Geolocation tidak didukung oleh browser ini.');
                }
            });

            // Search location button (simple implementation)
            document.getElementById('searchLocation').addEventListener('click', function() {
                const query = prompt('Masukkan nama lokasi yang ingin dicari:');
                if (query) {
                    // Using Nominatim API for geocoding
                    fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lng = parseFloat(data[0].lon);

                                marker.setLatLng([lat, lng]);
                                map.setView([lat, lng], 15);
                                updateInputs(lat, lng);
                            } else {
                                alert('Lokasi tidak ditemukan. Coba dengan nama yang lebih spesifik.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mencari lokasi.');
                        });
                }
            });
        });

        // Thumbnail preview function
        function previewThumbnail(input) {
            const preview = document.getElementById('thumbnail-preview');
            const img = preview.querySelector('img');
            const removeBtn = document.getElementById('remove-thumbnail');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);

                // Add remove button functionality
                removeBtn.addEventListener('click', function() {
                    input.value = '';
                    preview.classList.add('hidden');
                });
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
