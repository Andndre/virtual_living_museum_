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
                background-image: linear-gradient(to top, rgba(255,255,255,1) 60%, rgba(255,255,255,0) 100%);
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

    <!-- Search Bar -->
    <div class="fixed top-4 left-0 right-0 z-[1000] pointer-events-none">
        <div class="w-full max-w-md mx-auto px-4 pointer-events-auto">
            <div class="bg-white rounded-full shadow-lg flex items-center p-2">
                <div class="text-gray-400 mr-2 ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input id="search-input" type="text" placeholder="Cari situs peninggalan..." class="w-full bg-transparent border-none focus:outline-none text-gray-700">
                <button id="search-clear" class="text-gray-400 hover:text-gray-600 hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="relative w-full">
                <div id="search-results" class="absolute top-2 left-0 right-0 max-h-60 overflow-y-auto bg-white rounded-lg shadow-lg mt-1 hidden z-50">
                    <ul id="search-results-list" class="divide-y divide-gray-100"></ul>
                </div>
            </div>
        </div>
    </div>

    <div id="map" style="height: 100vh;"></div>

    <div class="fixed bottom-0 left-0 right-0 z-[1000] pointer-events-none">
        <div id="bottom-overlay" class="w-full lg:max-w-xl mx-auto p-4 pointer-events-auto">
            <div class="p-4">
                <h2 id="overlay-title" class="text-xl font-bold mb-1"></h2>
                <p id="overlay-address" class="text-gray-600 text-sm"></p>
                <p id="overlay-description" class="text-gray-500 text-sm mt-2 truncate"></p>
                <a id="overlay-link" href="#" class="block w-full text-center bg-blue-600 text-white font-bold py-3 px-4 rounded-lg mt-4 hover:bg-blue-700 transition">Kunjungi</a>
                <p id="locked-message" class="hidden text-orange-600 text-sm mt-4 text-center">Situs ini belum dapat dikunjungi. Selesaikan materi sebelumnya untuk membukanya.</p>
                <button id="close-overlay" class="block w-full text-center bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg mt-2 hover:bg-gray-300 transition">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        var map = L.map('map', {
            zoomControl: false // Disable default zoom control
        }).setView([-8.409518, 115.188919], 10);
        var activeMarker = null;
        
        // Add zoom control to bottom right
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

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

        // Store all markers for search functionality
        var allMarkers = [];
        var situsNames = [];

        @foreach ($allSitus as $s)
            var marker = L.marker([{{ $s->lat }}, {{ $s->lng }}], {icon: situsIcon}).addTo(map);
            
            // Store marker information for search
            marker.situsInfo = {
                id: {{ $s->situs_id }},
                nama: '{{ $s->nama }}',
                alamat: '{{ $s->alamat }}',
                deskripsi: '{{ Illuminate\Support\Str::limit($s->deskripsi, 100) }}',
                unlocked: {{ in_array($s->situs_id, $unlockedSitusIds) ? 'true' : 'false' }},
                url: '{{ route("guest.situs.detail", ["situs_id" => $s->situs_id]) }}'
            };
            
            allMarkers.push(marker);
            situsNames.push('{{ strtolower($s->nama) }}');
            
            // Debug log - first marker only
            @if ($loop->first)
            console.log('Marker added:', marker.situsInfo);
            @endif
            marker.on('click', function(e) {
                L.DomEvent.stopPropagation(e);
                focusOnMarker(this);
            });
        @endforeach

        function hideOverlay() {
            overlay.classList.remove('visible');
            if (activeMarker) {
                activeMarker.setIcon(situsIcon);
                activeMarker = null;
            }
            overlayLink.style.display = 'none';
            lockedMessage.style.display = 'none';
        }

        closeOverlayBtn.addEventListener('click', hideOverlay);
        map.on('click', hideOverlay);

        // Function to focus and zoom to a marker
        function focusOnMarker(marker) {
            // Hide any previously active marker
            if (activeMarker) {
                activeMarker.setIcon(situsIcon);
            }
            
            // Set the clicked marker as active
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
            
            // Zoom to marker
            map.setView([marker._latlng.lat, marker._latlng.lng], 14);
            
            // Update overlay content
            overlayTitle.textContent = marker.situsInfo.nama;
            overlayAddress.textContent = marker.situsInfo.alamat;
            overlayDescription.textContent = marker.situsInfo.deskripsi;
            
            // Check if this site is unlocked
            if (marker.situsInfo.unlocked) {
                overlayLink.href = marker.situsInfo.url;
                overlayLink.style.display = 'block';
                lockedMessage.style.display = 'none';
            } else {
                overlayLink.style.display = 'none';
                lockedMessage.style.display = 'block';
            }
            
            overlay.classList.add('visible');
        }

        // Search functionality
        searchInput.addEventListener('input', function(e) {
            const searchText = e.target.value.trim().toLowerCase();
            console.log('Searching for:', searchText); // Debug log
            
            // Show/hide clear button
            if (searchText.length > 0) {
                searchClear.classList.remove('hidden');
            } else {
                searchClear.classList.add('hidden');
            }
            
            // Filter markers based on search
            let visibleCount = 0;
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
                    visibleCount++;
                    
                    // Add to matching markers for the dropdown
                    if (searchText.length > 0) {
                        matchingMarkers.push(marker);
                    }
                } else {
                    // Hide this marker
                    if (map.hasLayer(marker)) {
                        map.removeLayer(marker);
                        // If this was the active marker, hide the overlay
                        if (marker === activeMarker) {
                            hideOverlay();
                        }
                    }
                }
            });
            
            // Update search results count
            if (searchText.length > 0) {
                // Show dropdown list of results
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