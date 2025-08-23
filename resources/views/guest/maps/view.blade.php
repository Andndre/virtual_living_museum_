<x-guest-layout>
    @push('head')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <style>
            #bottom-overlay {
                transition: transform 0.3s ease-in-out;
                transform: translateY(100%);
                background-image: linear-gradient(to top, rgba(255,255,255,1) 70%, rgba(255,255,255,0) 100%);
            }

            #bottom-overlay.visible {
                transform: translateY(0);
            }

            .selected-marker-icon .spinning-circle-wrapper {
                position: absolute;
                width: 100%;
                height: 100%;
                border: 3px dashed #d71818;
                border-radius: 50%;
                animation: spin 10s linear infinite;
            }

            .selected-marker-icon img {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 60%;
                height: 60%;
            }

            .marker-title {
                position: absolute;
                bottom: -25px; /* Position below the icon */
                left: 50%;
                transform: translateX(-50%);
                white-space: nowrap;
                color: #333;
                font-weight: bold;
                font-size: 14px;
                background-color: rgba(255, 255, 255, 0.85);
                padding: 4px 8px;
                border-radius: 5px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            }

            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        </style>
    @endpush

    <div id="map" style="height: 100vh;"></div>

    <div class="fixed bottom-0 left-0 right-0 z-[1000] pointer-events-none">
        <div id="bottom-overlay" class="w-full lg:max-w-xl mx-auto p-4 pointer-events-auto">
            <div class="bg-white p-4 rounded-xl shadow-2xl">
                <h2 id="overlay-title" class="text-xl font-bold mb-1"></h2>
                <p id="overlay-address" class="text-gray-600 text-sm"></p>
                <p id="overlay-description" class="text-gray-500 text-sm mt-2 truncate"></p>
                <a id="overlay-link" href="#" class="block w-full text-center bg-blue-600 text-white font-bold py-3 px-4 rounded-lg mt-4 hover:bg-blue-700 transition">Kunjungi</a>
                <button id="close-overlay" class="block w-full text-center bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg mt-2 hover:bg-gray-300 transition">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([-8.409518, 115.188919], 10);
        var activeMarker = null;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // UI Elements
        const overlay = document.getElementById('bottom-overlay');
        const closeOverlayBtn = document.getElementById('close-overlay');
        const overlayTitle = document.getElementById('overlay-title');
        const overlayAddress = document.getElementById('overlay-address');
        const overlayDescription = document.getElementById('overlay-description');
        const overlayLink = document.getElementById('overlay-link');

        // User Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                L.circle([lat, lon], { radius: 200, color: '#1d4ed8', fillColor: '#60a5fa', fillOpacity: 0.3 }).addTo(map);
                L.circleMarker([lat, lon], { radius: 8, fillColor: "#1d4ed8", color: "#fff", weight: 2, opacity: 1, fillOpacity: 1 }).addTo(map).bindPopup('Lokasi Anda');
                map.setView([lat, lon], 13);
            });
        }

        // Situs Peninggalan Markers
        var situsIcon = L.icon({
            iconUrl: '{{ asset('images/icons/location.png') }}',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });

        @foreach ($situs as $s)
            var marker = L.marker([{{ $s->lat }}, {{ $s->lng }}], {icon: situsIcon}).addTo(map);
            marker.on('click', function(e) {
                L.DomEvent.stopPropagation(e);

                if (activeMarker) {
                    activeMarker.setIcon(situsIcon);
                }

                var selectedIcon = L.divIcon({
                    className: 'selected-marker-icon',
                    html: `
                        <div class="spinning-circle-wrapper"></div>
                        <img src="{{ asset('images/icons/location.png') }}">
                        <div class="marker-title">{{ Illuminate\Support\Str::limit($s->nama, 20) }}</div>
                    `,
                    iconSize: [50, 50],
                    iconAnchor: [25, 40]
                });

                this.setIcon(selectedIcon);
                activeMarker = this;

                // Update overlay content
                overlayTitle.textContent = '{{ $s->nama }}';
                overlayAddress.textContent = '{{ $s->alamat }}';
                overlayDescription.textContent = '{{ Illuminate\Support\Str::limit($s->deskripsi, 100) }}';
                overlayLink.href = '{{ route("guest.situs.detail", ["situs_id" => $s->situs_id]) }}';
                
                overlay.classList.add('visible');
            });
        @endforeach

        function hideOverlay() {
            overlay.classList.remove('visible');
            if (activeMarker) {
                activeMarker.setIcon(situsIcon);
                activeMarker = null;
            }
        }

        closeOverlayBtn.addEventListener('click', hideOverlay);
        map.on('click', hideOverlay);

    </script>
</x-guest-layout>