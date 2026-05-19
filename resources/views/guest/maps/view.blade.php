<x-guest-layout>
    @push('head')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <style>
            @media (prefers-reduced-motion: reduce) {

                *,
                *::before,
                *::after {
                    animation-duration: 0.01ms !important;
                    transition-duration: 0.01ms !important;
                }
            }

            body {
                overflow: hidden;
            }

            #map-container {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
            }

            #search-bar {
                position: fixed;
                top: env(safe-area-inset-top, 0);
                left: 0;
                right: 0;
                padding: 10px;
                padding-top: calc(env(safe-area-inset-top, 0) + 10px);
                z-index: 1000;
            }

            #search-bar button,
            #search-bar a,
            #search-bar input {
                cursor-pointer: pointer;
            }

            #bottom-sheet {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                transform: translateY(100%);
                transition: transform 250ms ease-out;
                visibility: hidden;
                padding-bottom: env(safe-area-inset-bottom, 0);
            }

            #bottom-sheet.visible {
                transform: translateY(0);
                visibility: visible;
            }

            .selected-marker-icon .spinning-circle-wrapper {
                position: absolute;
                width: 100%;
                height: 100%;
                border: 3px dashed #d71818;
                border-radius: 50%;
                animation: spin 10s linear infinite;
            }

            @media (prefers-reduced-motion: reduce) {
                .selected-marker-icon .spinning-circle-wrapper {
                    animation: none;
                    border-style: solid;
                }
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
                bottom: -25px;
                left: 50%;
                transform: translateX(-50%);
                white-space: nowrap;
                color: #333;
                font-weight: bold;
                font-size: 14px;
                background-color: rgba(255, 255, 255, 0.95);
                padding: 6px 10px;
                border-radius: 6px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            .leaflet-bottom.leaflet-left,
            .leaflet-bottom.leaflet-right {
                bottom: 30px;
            }

            .leaflet-touch .leaflet-control-zoom a {
                width: 44px;
                height: 44px;
                line-height: 44px;
                font-size: 18px;
            }

            .leaflet-marker-icon {
                filter: drop-shadow(0px 2px 4px rgba(0, 0, 0, 0.25));
                transition: transform 150ms ease-out;
            }

            .leaflet-marker-icon:hover {
                transform: scale(1.1);
            }

            /* Focus states for accessibility */
            button:focus-visible,
            a:focus-visible,
            input:focus-visible {
                outline: 2px solid #2563eb;
                outline-offset: 2px;
            }

            /* Action button styles */
            .action-btn {
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 12px 16px;
                border-radius: 12px;
                font-weight: 600;
                transition: all 200ms ease-out;
            }

            .action-btn-primary {
                background-color: #2563eb;
                color: white;
            }

            .action-btn-primary:hover {
                background-color: #1d4ed8;
            }

            .action-btn-primary:active {
                transform: scale(0.98);
            }

            .action-btn-secondary {
                background-color: #e5e7eb;
                color: #374151;
            }

            .action-btn-secondary:hover {
                background-color: #d1d5db;
            }

            .action-btn-secondary:active {
                transform: scale(0.98);
            }

            #locked-message {
                display: flex !important;
                align-items: center;
                gap: 0.5rem;
            }
        </style>
    @endpush

    <!-- Map Container -->
    <div id="map-container">
        <div id="map" style="height: 100%; width: 100%;"></div>
    </div>

    <!-- Search Bar -->
    <div id="search-bar">
        <div class="mx-auto flex max-w-md items-center gap-2">
            <!-- Back Button -->
            <button
                class="back-button flex h-[48px] w-[48px] flex-shrink-0 items-center justify-center rounded-full bg-white shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="h-6 w-6 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </button>

            <!-- Search Input -->
            <div class="flex h-[48px] flex-grow items-center rounded-full bg-white shadow-lg">
                <div class="mx-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input id="search-input" type="search" inputmode="search" autocomplete="off"
                    placeholder="Cari situs peninggalan..."
                    class="h-full w-full border-none bg-transparent text-gray-700 focus:border-none focus:outline-none focus:ring-0"
                    style="-webkit-appearance: none; touch-action: manipulation;" aria-label="Cari situs peninggalan">
                <button id="search-clear" class="mx-3 hidden text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Search Results Dropdown -->
        <div class="mx-auto mt-1 max-w-md px-4">
            <div id="search-results" class="z-50 hidden max-h-60 overflow-y-auto rounded-lg bg-white shadow-lg">
                <ul id="search-results-list" class="divide-y divide-gray-100"></ul>
            </div>
        </div>
    </div>

    <!-- Bottom Sheet -->
    <div id="bottom-sheet" role="dialog" aria-labelledby="overlay-title" aria-describedby="overlay-description">
        <div class="mx-auto max-h-[85vh] w-full overflow-hidden rounded-t-2xl bg-white shadow-xl lg:max-w-xl">
            <!-- Drag Handle -->
            <div class="flex justify-center pb-2 pt-3">
                <div class="h-1.5 w-12 rounded-full bg-gray-300"></div>
            </div>
            <div class="px-5 pb-5">
                <h2 id="overlay-title" class="mb-1 text-xl font-bold text-gray-900"></h2>
                <p id="overlay-address" class="text-sm text-gray-600"></p>
                <p id="overlay-description" class="mt-3 line-clamp-2 text-sm text-gray-500"></p>
                <a id="overlay-link" href="#" class="action-btn action-btn-primary mt-4 w-full" role="button"
                    style="display: none;">
                    Kunjungi
                </a>
                <p id="locked-message"
                    class="locked-message mt-4 items-center justify-center gap-2 text-center text-sm text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Situs ini belum dapat dikunjungi. Selesaikan materi sebelumnya untuk membukanya.
                </p>
                <button id="close-overlay" class="action-btn action-btn-secondary mt-3 w-full" role="button">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        // Check if device is mobile
        const isMobileDevice = window.innerWidth < 768;

        // Initialize map
        var map = L.map('map', {
            zoomControl: false,
            tap: true
        }).setView([-8.409518, 115.188919], isMobileDevice ? 8 : 10);

        var activeMarker = null;

        // Add zoom control
        L.control.zoom({
            position: 'bottomright',
            zoomInTitle: 'Perbesar',
            zoomOutTitle: 'Perkecil'
        }).addTo(map);

        // Custom control for "Lihat Peninggalan" button
        L.Control.PeninggalanButton = L.Control.extend({
            onAdd: function() {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                const link = L.DomUtil.create('a', '', container);
                link.href = '{{ route('guest.maps.peninggalan') }}';
                link.title = 'Lihat Daftar Peninggalan';
                link.innerHTML =
                    '<div class="bg-white p-2 rounded-md shadow-md font-medium" style="width: auto; white-space: nowrap;">Lihat Peninggalan</div>';

                L.DomEvent.on(link, 'click', function(e) {
                    L.DomEvent.stopPropagation(e);
                });

                return container;
            },
            onRemove: function() {}
        });

        L.control.peninggalanButton = function(opts) {
            return new L.Control.PeninggalanButton(opts);
        }

        L.control.peninggalanButton({
            position: 'bottomleft'
        }).addTo(map);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Get UI elements
        const bottomSheet = document.getElementById('bottom-sheet');
        const closeOverlayBtn = document.getElementById('close-overlay');
        const overlayTitle = document.getElementById('overlay-title');
        const overlayAddress = document.getElementById('overlay-address');
        const overlayDescription = document.getElementById('overlay-description');
        const overlayLink = document.getElementById('overlay-link');
        const lockedMessage = document.getElementById('locked-message');
        const searchInput = document.getElementById('search-input');
        const searchClear = document.getElementById('search-clear');
        const searchResults = document.getElementById('search-results');
        const searchResultsList = document.getElementById('search-results-list');

        // User Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                L.circle([lat, lon], {
                    radius: 200,
                    color: '#1d4ed8',
                    fillColor: '#60a5fa',
                    fillOpacity: 0.3
                }).addTo(map);
                L.circleMarker([lat, lon], {
                    radius: 8,
                    fillColor: "#1d4ed8",
                    color: "#fff",
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 1
                }).addTo(map).bindPopup('Lokasi Anda');
                map.setView([lat, lon], 13);
            });
        }

        // Situs markers
        var situsIcon = L.icon({
            iconUrl: '{{ asset('images/icons/location.png') }}',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });

        // Store all markers
        var allMarkers = [];
        var situsNames = [];

        @foreach ($allSitus as $s)
            var marker = L.marker([{{ $s->lat }}, {{ $s->lng }}], {
                icon: situsIcon
            }).addTo(map);

            // Store marker info
            marker.situsInfo = {
                id: {{ $s->situs_id }},
                nama: '{{ $s->nama }}',
                alamat: '{{ $s->alamat }}',
                deskripsi: '{{ Illuminate\Support\Str::limit($s->deskripsi, 100) }}',
                unlocked: {{ in_array($s->situs_id, $unlockedSitusIds) ? 'true' : 'false' }},
                url: '{{ route('guest.situs.detail', ['situs_id' => $s->situs_id]) }}'
            };

            allMarkers.push(marker);
            situsNames.push('{{ strtolower($s->nama) }}');

            marker.on('click', function(e) {
                L.DomEvent.stopPropagation(e);
                focusOnMarker(this);
            });
        @endforeach

        // Hide bottom sheet
        function hideBottomSheet() {
            bottomSheet.classList.remove('visible');

            if (activeMarker) {
                activeMarker.setIcon(situsIcon);
                activeMarker = null;
            }

            overlayLink.style.display = 'none';
            lockedMessage.style.display = 'none';

            // Reset map position
            if (isMobileDevice) {
                map.invalidateSize();
            }
        }

        closeOverlayBtn.addEventListener('click', hideBottomSheet);
        map.on('click', hideBottomSheet);

        // Focus on marker
        function focusOnMarker(marker) {
            // Reset previous active marker
            if (activeMarker) {
                activeMarker.setIcon(situsIcon);
            }

            // Set new active marker
            var selectedIcon = L.divIcon({
                className: 'selected-marker-icon',
                html: `
                    <div class="spinning-circle-wrapper"></div>
                    <img src="{{ asset('images/icons/location.png') }}">
                    <div class="marker-title">${marker.situsInfo.nama.length > 20 ? marker.situsInfo.nama.substring(0, 20) + '...' : marker.situsInfo.nama}</div>
                `,
                iconSize: [50, 50],
                iconAnchor: [25, 40]
            });

            marker.setIcon(selectedIcon);
            activeMarker = marker;

            // Position map
            const targetZoom = 14;

            if (isMobileDevice) {
                map.setView(marker._latlng, targetZoom);

                setTimeout(() => {
                    map.panBy([0, -window.innerHeight * 0.2]);
                }, 100);
            } else {
                map.setView(marker._latlng, targetZoom);
            }

            // Update bottom sheet content
            overlayTitle.textContent = marker.situsInfo.nama;
            overlayAddress.textContent = marker.situsInfo.alamat;
            overlayDescription.textContent = marker.situsInfo.deskripsi;

            // Check if unlocked
            if (marker.situsInfo.unlocked) {
                overlayLink.href = marker.situsInfo.url;
                overlayLink.style.display = 'flex';
                lockedMessage.style.display = 'none';
            } else {
                overlayLink.style.display = 'none';
                lockedMessage.style.display = 'flex';
            }

            // Show bottom sheet
            bottomSheet.classList.add('visible');
        }

        // Search functionality
        searchInput.addEventListener('input', function(e) {
            const searchText = e.target.value.trim().toLowerCase();

            // Show/hide clear button
            if (searchText.length > 0) {
                searchClear.classList.remove('hidden');
            } else {
                searchClear.classList.add('hidden');
            }

            // Filter markers
            let matchingMarkers = [];

            allMarkers.forEach(function(marker) {
                const situsName = marker.situsInfo.nama.toLowerCase();
                const situsAddress = marker.situsInfo.alamat.toLowerCase();

                if (searchText.length === 0 ||
                    situsName.includes(searchText) ||
                    situsAddress.includes(searchText)) {
                    // Show this marker
                    if (!map.hasLayer(marker)) {
                        map.addLayer(marker);
                    }

                    // Add to matching markers
                    if (searchText.length > 0) {
                        matchingMarkers.push(marker);
                    }
                } else {
                    // Hide this marker
                    if (map.hasLayer(marker)) {
                        map.removeLayer(marker);
                        if (marker === activeMarker) {
                            hideBottomSheet();
                        }
                    }
                }
            });

            // Update search results
            if (searchText.length > 0) {
                searchResultsList.innerHTML = '';

                if (matchingMarkers.length > 0) {
                    matchingMarkers.forEach(function(marker) {
                        const li = document.createElement('li');
                        li.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer';

                        const nameSpan = document.createElement('div');
                        nameSpan.className = 'font-medium text-gray-900';
                        nameSpan.textContent = marker.situsInfo.nama;

                        const addressSpan = document.createElement('div');
                        addressSpan.className = 'text-sm text-gray-500';
                        addressSpan.textContent = marker.situsInfo.alamat;

                        li.appendChild(nameSpan);
                        li.appendChild(addressSpan);

                        li.addEventListener('click', function() {
                            focusOnMarker(marker);
                            searchInput.value = marker.situsInfo.nama;
                            searchResults.classList.add('hidden');
                        });

                        searchResultsList.appendChild(li);
                    });

                    searchResults.classList.remove('hidden');
                } else {
                    searchResults.classList.add('hidden');
                }
            } else {
                searchResults.classList.add('hidden');
            }
        });

        // Clear search
        searchClear.addEventListener('click', function() {
            searchInput.value = '';
            searchClear.classList.add('hidden');
            searchResults.classList.add('hidden');

            // Show all markers
            allMarkers.forEach(function(marker) {
                if (!map.hasLayer(marker)) {
                    map.addLayer(marker);
                }
            });
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    </script>
</x-guest-layout>
