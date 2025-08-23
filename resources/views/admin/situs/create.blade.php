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
                                <span class="text-gray-900 font-medium">Tambah Situs</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="mb-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Situs Peninggalan</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Menambahkan situs peninggalan baru ke dalam sistem</p>
                </div>
                
                <!-- Action Button -->
                <div class="flex">
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
                <form action="{{ route('admin.situs.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
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
                                value="{{ old('nama') }}" 
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
                            >{{ old('alamat') }}</textarea>
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
                                        value="{{ old('lat', '') }}" 
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
                                        value="{{ old('lng', '') }}" 
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
                                    <option value="{{ $materi->materi_id }}" {{ old('materi_id') == $materi->materi_id ? 'selected' : '' }}>
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
                            >{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thumbnail Image -->
                        <div>
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">
                                Gambar Thumbnail
                            </label>
                            <div class="mt-1 flex items-center">
                                <div id="thumbnail-preview" class="hidden mb-3">
                                    <img src="#" alt="Thumbnail Preview" class="h-32 w-auto object-cover rounded-lg border border-gray-300">
                                    <button type="button" id="remove-thumbnail" class="mt-1 text-xs text-red-600 hover:text-red-800">
                                        <i class="fas fa-times mr-1"></i> Hapus
                                    </button>
                                </div>
                                <div class="mt-1 w-full">
                                    <label class="block w-full px-3 py-2 border border-gray-300 border-dashed rounded-md cursor-pointer hover:bg-gray-50">
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-500">Klik untuk unggah gambar</span>
                                        </div>
                                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="sr-only"
                                            onchange="previewThumbnail(this)">
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
                    <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.situs') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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
                        alert('Tidak dapat mengakses lokasi Anda. Pastikan GPS diaktifkan dan izin lokasi diberikan.');
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
