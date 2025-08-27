<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                                <a href="{{ route('admin.situs') }}" class="text-gray-400 hover:text-gray-500">Kelola Situs</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                                <a href="{{ route('admin.situs.show', $situs->situs_id) }}" class="text-gray-400 hover:text-gray-500">{{ $situs->nama }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                                <span class="text-gray-900 font-medium">Edit</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="mb-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Situs Peninggalan</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Mengedit situs: <strong>{{ $situs->nama }}</strong></p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-3">
                    <a href="{{ route('admin.situs.show', $situs->situs_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        <span class="hidden sm:inline">Lihat Detail</span>
                        <span class="sm:hidden">Detail</span>
                    </a>
                    <a href="{{ route('admin.situs') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Kembali ke Situs</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white shadow-lg sm:rounded-lg border border-gray-200">
                <form id="situsForm" action="{{ route('admin.situs.update', $situs->situs_id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_method" value="PUT">
                    <div class="px-4 py-5 sm:p-6 space-y-6">
                        <!-- Nama Situs -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Situs <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                value="{{ old('nama', $situs->nama) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                                placeholder="Contoh: Candi Borobudur"
                                required
                            >
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="alamat"
                                id="alamat"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror"
                                placeholder="Contoh: Jl. Badrawati, Borobudur, Magelang, Jawa Tengah"
                                required
                            >{{ old('alamat', $situs->alamat) }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Koordinat dengan Interactive Map -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Koordinat Lokasi <span class="text-red-500">*</span>
                                </label>
                                <p class="text-sm text-gray-600 mb-3">Klik pada peta atau masukkan koordinat secara manual</p>
                            </div>

                            <!-- Interactive Map -->
                            <div class="border border-gray-300 rounded-md overflow-hidden">
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            </div>

                            <!-- Coordinate Inputs -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="lat" class="block text-sm font-medium text-gray-700 mb-1">
                                        Latitude <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        name="lat"
                                        id="lat"
                                        step="any"
                                        value="{{ old('lat', $situs->lat) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('lat') border-red-500 @enderror"
                                        placeholder="Contoh: -7.608543"
                                        required
                                    >
                                    @error('lat')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="lng" class="block text-sm font-medium text-gray-700 mb-1">
                                        Longitude <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        name="lng"
                                        id="lng"
                                        step="any"
                                        value="{{ old('lng', $situs->lng) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('lng') border-red-500 @enderror"
                                        placeholder="Contoh: 110.203751"
                                        required
                                    >
                                    @error('lng')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Map Controls -->
                            <div class="flex flex-wrap gap-2">
                                <button type="button" id="getCurrentLocation" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-crosshairs mr-2"></i>
                                    Lokasi Saya
                                </button>
                                <button type="button" id="searchLocation" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-search mr-2"></i>
                                    Cari Lokasi
                                </button>
                            </div>
                        </div>

                        <!-- Materi Terkait -->
                        <div>
                            <label for="materi_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Materi Terkait <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="materi_id"
                                id="materi_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('materi_id') border-red-500 @enderror"
                                required
                            >
                                <option value="">Pilih Materi</option>
                                @foreach($materis as $materi)
                                    <option value="{{ $materi->materi_id }}" {{ (old('materi_id', $situs->materi_id) == $materi->materi_id) ? 'selected' : '' }}>
                                        {{ $materi->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('materi_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="deskripsi"
                                id="deskripsi"
                                rows="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror"
                                placeholder="Jelaskan tentang situs peninggalan ini, sejarah, fungsi, dan hal menarik lainnya..."
                                required
                            >{{ old('deskripsi', $situs->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thumbnail Image -->
                        <div>
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">
                                Gambar Thumbnail
                            </label>
                            <div class="mt-1">
                                <!-- Hidden input to track thumbnail removal -->
                                <input type="hidden" name="remove_thumbnail" id="remove_thumbnail" value="0">
                                
                                <!-- Thumbnail Preview -->
                                <div id="thumbnail-preview" class="{{ $situs->thumbnail ? 'block' : 'hidden' }} mb-3">
                                    <div class="relative inline-block">
                                        <img src="{{ $situs->thumbnail ? asset('storage/' . $situs->thumbnail) : '#' }}"
                                             alt="Thumbnail Preview"
                                             class="h-32 w-auto object-cover rounded-lg border border-gray-300">
                                        <button type="button" id="remove-thumbnail" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Upload Area -->
                                <div id="upload-area" class="{{ $situs->thumbnail ? 'hidden' : 'block' }}">
                                    <label class="block w-full px-3 py-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:bg-gray-50 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                            <span class="text-sm text-gray-500">Klik untuk mengunggah gambar</span>
                                            <span class="text-xs text-gray-400 mt-1">Format: JPG, PNG, GIF (Maks. 2MB)</span>
                                        </div>
                                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="sr-only">
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
                                @if($situs->thumbnail)
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
                        @if($situs->virtualMuseumObject->count() > 0)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Objek Virtual Living Museum Terkait</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>Situs ini memiliki {{ $situs->virtualMuseumObject->count() }} objek virtual living museum:</p>
                                            <ul class="list-disc list-inside mt-1">
                                                @foreach($situs->virtualMuseumObject as $object)
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
                    <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.situs.show', $situs->situs_id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
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
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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
                        alert('Tidak dapat mengakses lokasi Anda. Pastikan GPS diaktifkan dan izin lokasi diberikan.');
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
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
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
