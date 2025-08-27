<x-elearning-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <button type="button" onclick="window.history.back()" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </button>
                <div class="flex-1">
                    <h1 class="text-lg font-bold">Virtual Living Museum</h1>
                    <p class="text-sm opacity-90">{{ $situs->nama }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="px-6 py-6 bg-gray-50 min-h-screen">
        {{-- Hero Image Section --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="aspect-[16/9] bg-gradient-to-br from-orange-400 to-orange-600 relative">
                {{-- Tampilkan thumbnail menggunakan accessor --}}
                <img src="{{ $situs->thumbnailUrl }}"
                     alt="{{ $situs->nama }}"
                     class="w-full h-full object-cover">

                {{-- Overlay with basic info --}}
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                    <h2 class="text-2xl font-bold text-white mb-2">{{ $situs->nama }}</h2>
                    @if($situs->alamat)
                        <div class="flex items-center text-white/90">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span class="text-sm">{{ $situs->alamat }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Location Info Card --}}
        @if($situs->lat && $situs->lng)
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marked-alt text-orange-600 mr-2"></i>
                    Lokasi
                </h3>

                <div class="space-y-3">
                    @if($situs->alamat)
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-location-dot text-gray-400 mt-1"></i>
                            <div>
                                <p class="text-gray-900 font-medium">Alamat</p>
                                <p class="text-gray-600">{{ $situs->alamat }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start space-x-3">
                        <i class="fas fa-globe text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-gray-900 font-medium">Koordinat</p>
                            <p class="text-gray-600">{{ $situs->lat }}, {{ $situs->lng }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Description Card --}}
        @if($situs->deskripsi)
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Deskripsi
                </h3>
                <div class="prose prose-gray max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $situs->deskripsi }}</p>
                </div>
            </div>
        @endif

        {{-- Virtual Living Museum Spots --}}
        @if($situs->virtualMuseum->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-location-dot text-purple-600 mr-2"></i>
                    Spot AR Virtual Living Museum
                </h3>

                <div class="grid grid-cols-1 gap-4">
                    @foreach($situs->virtualMuseum as $museum)
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-location-dot text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $museum->nama }}</h4>
                                    @if($museum->virtualMuseumObjects->count() > 0)
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $museum->virtualMuseumObjects->count() }}</span> objek peninggalan tersedia
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- AR Launch Button for this specific spot --}}
                            <button onclick="launchSpotAR({{ $museum->museum_id }}, '{{ $museum->nama }}')"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-medium rounded-lg hover:from-purple-700 hover:to-blue-700 transform hover:scale-[1.02] transition-all duration-200 shadow-md">
                                <i class="fas fa-vr-cardboard mr-2"></i>
                                Jelajahi AR
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- AR Experience Section --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-mobile-alt text-green-600 mr-2"></i>
                Cara Menggunakan AR
            </h3>

            <div class="space-y-4">
                {{-- Instructions --}}
                <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg p-4">
                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-list-ol text-gray-600 mr-2"></i>
                        Langkah-langkah:
                    </h5>
                    <ol class="text-sm text-gray-700 space-y-2 ml-4">
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">1</span>
                            <span>Pilih spot yang ingin dijelajahi dan klik tombol "Jelajahi AR"</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">2</span>
                            <span>Izinkan akses kamera dan arahkan ke permukaan datar</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5 flex-shrink-0">3</span>
                            <span>Ketuk lingkaran untuk menempatkan objek berukuran besar</span>
                        </li>
                    </ol>
                </div>

                {{-- Requirements Notice --}}
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5"></i>
                        <div class="text-left">
                            <p class="text-sm font-medium text-amber-800">Persyaratan:</p>
                            <p class="text-xs text-amber-700 mt-1">Browser dengan dukungan WebXR, kamera, koneksi stabil, dan pencahayaan cukup</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Actions --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Navigasi</h3>

            <div class="space-y-3">
                @if($situs->materi)
                    <a href="{{ route('guest.elearning.materi', $situs->materi->materi_id) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Materi: {{ $situs->materi->judul }}
                    </a>
                @endif

                <a href="{{ route('guest.elearning') }}"
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda E-Learning
                </a>

                @if($situs->lat && $situs->lng)
                    <a href="https://www.google.com/maps?q={{ $situs->lat }},{{ $situs->lng }}"
                       target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-map-marked-alt mr-2"></i>
                        Lihat di Google Maps
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- AR JavaScript (Three.js WebXR implementation) --}}
    <script>
        function launchSpotAR(museumId, museumName) {
            // Check WebXR support first
            if (!('xr' in navigator)) {
                alert('WebXR tidak didukung di browser ini. Gunakan browser yang mendukung WebXR seperti Chrome atau Edge terbaru.');
                return;
            }

            navigator.xr.isSessionSupported('immersive-ar').then((supported) => {
                if (!supported) {
                    alert('AR tidak didukung di perangkat ini. Pastikan Anda menggunakan perangkat yang mendukung WebXR.');
                    return;
                }

                // Launch AR for specific museum spot
                const confirmation = confirm(`Memulai pengalaman AR untuk spot "${museumName}"?\n\nPastikan Anda berada di tempat yang memiliki pencahayaan cukup dan permukaan datar untuk penempatan objek.`);

                if (confirmation) {
                    // Redirect to AR experience page using Laravel route
                    const arUrl = `{{ url('/situs') }}/{{ $situs->situs_id }}/ar/${museumId}`;
                    window.location.href = arUrl;
                }
            }).catch((error) => {
                console.error('Error checking WebXR support:', error);
                alert('Terjadi kesalahan saat memeriksa dukungan AR. Pastikan browser Anda mendukung WebXR.');
            });
        }

        // Check AR support on page load
        document.addEventListener('DOMContentLoaded', function() {
            if ('xr' in navigator) {
                navigator.xr.isSessionSupported('immersive-ar').then((supported) => {
                    if (!supported) {
                        console.log('AR not supported on this device');
                        // Optionally update UI to show limited support
                        const arButtons = document.querySelectorAll('button[onclick*="launchSpotAR"]');
                        arButtons.forEach(button => {
                            button.classList.add('opacity-50', 'cursor-not-allowed');
                            button.disabled = true;
                            button.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>AR Tidak Didukung';
                        });
                    }
                });
            } else {
                console.log('WebXR not supported');
                // Disable all AR buttons
                const arButtons = document.querySelectorAll('button[onclick*="launchSpotAR"]');
                arButtons.forEach(button => {
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>WebXR Tidak Didukung';
                });
            }
        });

        // Helper function to show loading state
        function showARLoading(button) {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat AR...';
            button.disabled = true;

            return function() {
                button.innerHTML = originalHTML;
                button.disabled = false;
            };
        }
    </script>
</x-elearning-layout>
