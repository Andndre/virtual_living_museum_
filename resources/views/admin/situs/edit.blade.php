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
                                <a href="{{ route('admin.situs.show', $situs->situs_id) }}"
                                    class="text-gray-400 hover:text-gray-500">{{ $situs->nama }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right mr-4 text-gray-400"></i>
                                <span class="font-medium text-gray-900">Edit</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Edit Situs Peninggalan</h1>
                    <p class="mt-2 text-sm text-gray-600 sm:text-base">Mengedit situs:
                        <strong>{{ $situs->nama }}</strong></p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3 sm:flex-row sm:gap-3">
                    <a href="{{ route('admin.situs.show', $situs->situs_id) }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                        <i class="fas fa-eye mr-2"></i>
                        <span class="hidden sm:inline">Lihat Detail</span>
                        <span class="sm:hidden">Detail</span>
                    </a>
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
                <form id="situsForm" action="{{ route('admin.situs.update', $situs->situs_id) }}" method="POST"
                    class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_method" value="PUT">
                    <div class="space-y-6 px-4 py-5 sm:p-6">
                        <!-- Nama Situs -->
                        <div>
                            <label for="nama" class="mb-1 block text-sm font-medium text-gray-700">
                                Nama Situs <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $situs->nama) }}"
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
                                placeholder="Contoh: Jl. Badrawati, Borobudur, Magelang, Jawa Tengah" required>{{ old('alamat', $situs->alamat) }}</textarea>
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
                                        value="{{ old('lat', $situs->lat) }}"
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
                                        value="{{ old('lng', $situs->lng) }}"
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
                                                {{ old('materi_id', $situs->materi_id) == $materi->materi_id ? 'selected' : '' }}>
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
                                placeholder="Jelaskan tentang situs peninggalan ini, sejarah, fungsi, dan hal menarik lainnya..." required>{{ old('deskripsi', $situs->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thumbnail Image -->
                        <div>
                            <label for="thumbnail" class="mb-1 block text-sm font-medium text-gray-700">
                                Gambar Thumbnail
                            </label>
                            <div class="mt-1">
                                <!-- Hidden input to track thumbnail removal -->
                                <input type="hidden" name="remove_thumbnail" id="remove_thumbnail" value="0">

                                <!-- Thumbnail Preview -->
                                <div id="thumbnail-preview"
                                    class="{{ $situs->thumbnail ? 'block' : 'hidden' }} mb-3">
                                    <div class="relative inline-block">
                                        <img src="{{ $situs->thumbnail ? asset('storage/' . $situs->thumbnail) : '#' }}"
                                            alt="Thumbnail Preview"
                                            class="h-32 w-auto rounded-lg border border-gray-300 object-cover">
                                        <button type="button" id="remove-thumbnail"
                                            class="absolute -right-2 -top-2 rounded-full bg-red-500 p-1 text-white transition-colors hover:bg-red-600">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Upload Area -->
                                <div id="upload-area" class="{{ $situs->thumbnail ? 'hidden' : 'block' }}">
                                    <label
                                        class="block w-full cursor-pointer rounded-md border-2 border-dashed border-gray-300 px-3 py-6 text-center hover:bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-cloud-upload-alt mb-2 text-3xl text-gray-400"></i>
                                            <span class="text-sm text-gray-500">Klik untuk mengunggah gambar</span>
                                            <span class="mt-1 text-xs text-gray-400">Format: JPG, PNG, GIF (Maks.
                                                2MB)</span>
                                        </div>
                                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                                            class="sr-only">
                                    </label>
                                </div>

                                @error('thumbnail')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('situsForm');
                                const thumbnailInput = document.getElementById('thumbnail');
                                const removeThumbnailBtn = document.getElementById('remove-thumbnail');
                                const removeThumbnailInput = document.getElementById('remove_thumbnail');
                                const thumbnailPreview = document.getElementById('thumbnail-preview');
                                const uploadArea = document.getElementById('upload-area');

                                // Handle file selection
                                if (thumbnailInput) {
                                    thumbnailInput.addEventListener('change', function(e) {
                                        const file = e.target.files[0];
                                        if (file) {
                                            // Validate file type
                                            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                                            if (!validTypes.includes(file.type)) {
                                                alert('Format file tidak didukung. Harap unggah file JPG, PNG, atau GIF.');
                                                this.value = '';
                                                return;
                                            }

                                            // Validate file size (2MB)
                                            if (file.size > 2 * 1024 * 1024) {
                                                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                                                this.value = '';
                                                return;
                                            }

                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                const img = thumbnailPreview.querySelector('img');
                                                img.src = e.target.result;
                                                thumbnailPreview.classList.remove('hidden');
                                                uploadArea.classList.add('hidden');
                                                removeThumbnailInput.value = '0'; // Reset remove flag
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    });
                                }

                                // Handle remove thumbnail
                                if (removeThumbnailBtn) {
                                    removeThumbnailBtn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        thumbnailInput.value = ''; // Clear file input
                                        removeThumbnailInput.value = '1'; // Set remove flag
                                        thumbnailPreview.classList.add('hidden');
                                        uploadArea.classList.remove('hidden');
                                    });
                                }

                                // Initialize preview if thumbnail exists
                                @if ($situs->thumbnail)
                                    thumbnailPreview.classList.remove('hidden');
                                    uploadArea.classList.add('hidden');
                                @endif

                                // Form submission handler
                                if (form) {
                                    form.addEventListener('submit', function(e) {
                                        // No need to prevent default, let the form submit normally
                                        // Just show loading state if needed
                                        const submitBtn = form.querySelector('button[type="submit"]');
                                        if (submitBtn) {
                                            submitBtn.disabled = true;
                                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                                        }
                                    });
                                }
                            });
                        </script>

                        <!-- Virtual Objects Info -->
                        @if ($situs->virtualMuseumObject->count() > 0)
                            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Objek Virtual Living Museum
                                            Terkait</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>Situs ini memiliki {{ $situs->virtualMuseumObject->count() }} objek
                                                virtual living museum:</p>
                                            <ul class="mt-1 list-inside list-disc">
                                                @foreach ($situs->virtualMuseumObject as $object)
                                                    <li>{{ $object->nama }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col justify-end space-y-2 border-t border-gray-200 bg-gray-50 px-4 py-4 sm:flex-row sm:space-x-3 sm:space-y-0 sm:px-6">
                        <a href="{{ route('admin.situs.show', $situs->situs_id) }}"
                            class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 sm:w-auto">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 sm:w-auto">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
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
            // Get current coordinates from the form
            const currentLat = {{ old('lat', $situs->lat) }};
            const currentLng = {{ old('lng', $situs->lng) }};

            // Initialize map with current location
            const map = L.map('map').setView([currentLat, currentLng], 15);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Initialize marker with current location
            let marker = L.marker([currentLat, currentLng], {
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

            // Search location button
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
