<x-guest-layout>
    <div class="bg-primary text-white p-4">
        <div class="flex items-center mb-4">
            <button class="back-button mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </button>
            <h1 class="text-xl font-bold">Peninggalan Sejarah</h1>
        </div>

        <!-- Search Bar -->
        <div class="relative">
            <div class="bg-white/20 rounded-full flex items-center p-2">
                <div class="text-white mr-2 ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input id="search-input" type="text" placeholder="Cari peninggalan sejarah..." class="w-full bg-transparent border-none focus:outline-none text-white placeholder-white/70">
                <button id="search-clear" class="text-white/70 hover:text-white hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white border-b overflow-x-auto">
        <div class="flex p-2 min-w-max">
            <button id="filter-all" class="filter-btn active px-4 py-2 text-sm font-medium rounded-full mr-2 whitespace-nowrap">
                Semua
            </button>
            @foreach($situs as $s)
                <button data-situs-id="{{ $s->situs_id }}" class="filter-btn px-4 py-2 text-sm font-medium rounded-full mr-2 whitespace-nowrap">
                    {{ $s->nama }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Object Grid -->
    <div id="objects-container" class="p-4 grid grid-cols-2 gap-4 pb-20">
        @foreach($objects as $object)
            <div
                class="object-card border rounded-lg overflow-hidden shadow-sm bg-white"
                data-situs-id="{{ $object->situs_id }}"
                data-name="{{ $object->nama }}"
                data-description="{{ $object->deskripsi }}">
                <div class="aspect-square relative overflow-hidden">
                    <img
                        src="{{ asset('storage/' . $object->gambar_real) }}"
                        alt="{{ $object->nama }}"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                    @if(in_array($object->situs_id, $unlockedSitusIds))
                        <div class="absolute top-2 right-2 bg-green-500 text-white text-xs py-1 px-2 rounded-full">
                            Terbuka
                        </div>
                    @else
                        <div class="absolute top-2 right-2 bg-gray-500 text-white text-xs py-1 px-2 rounded-full">
                            Terkunci
                        </div>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="font-bold text-gray-800 truncate">{{ $object->nama }}</h3>
                    <p class="text-xs text-gray-500 truncate">{{ $object->situsPeninggalan->nama }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="no-results" class="hidden p-8 text-center">
        <div class="text-gray-400 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto w-12 h-12">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">Tidak ditemukan</h3>
        <p class="text-sm text-gray-500 mt-1">Tidak ada peninggalan yang sesuai dengan pencarian Anda</p>
    </div>

    <!-- Object Detail Modal -->
    <div id="object-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70" id="modal-backdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-md max-h-[80vh] overflow-hidden">
                <div class="relative">
                    <img id="modal-image" src="" alt="" class="w-full aspect-square object-cover">
                    <button id="close-modal" class="absolute top-4 right-4 bg-black/50 text-white rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto" style="max-height: 40vh;">
                    <h2 id="modal-title" class="text-xl font-bold text-gray-900 mb-1"></h2>
                    <p id="modal-location" class="text-sm text-gray-500 mb-4"></p>
                    <p id="modal-description" class="text-gray-700 text-sm"></p>
                </div>
                <div class="border-t p-4">
                    <a id="modal-link" href="#" class="block w-full text-center bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition">Kunjungi Situs</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const searchInput = document.getElementById('search-input');
        const searchClear = document.getElementById('search-clear');
        const objectsContainer = document.getElementById('objects-container');
        const objectCards = document.querySelectorAll('.object-card');
        const noResults = document.getElementById('no-results');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const modal = document.getElementById('object-modal');
        const modalBackdrop = document.getElementById('modal-backdrop');
        const closeModalBtn = document.getElementById('close-modal');
        const modalImage = document.getElementById('modal-image');
        const modalTitle = document.getElementById('modal-title');
        const modalLocation = document.getElementById('modal-location');
        const modalDescription = document.getElementById('modal-description');
        const modalLink = document.getElementById('modal-link');

        // Current filter state
        let currentSitusFilter = 'all';
        let currentSearchTerm = '';

        // Filter functions
        function filterObjects() {
            let visibleCount = 0;

            objectCards.forEach(card => {
                const situsId = card.dataset.situsId;
                const objectName = card.dataset.name.toLowerCase();
                const objectDescription = card.dataset.description.toLowerCase();

                // Check if it passes both filters
                const passesSearchFilter = !currentSearchTerm ||
                    objectName.includes(currentSearchTerm) ||
                    objectDescription.includes(currentSearchTerm);

                const passesSitusFilter = currentSitusFilter === 'all' || situsId === currentSitusFilter;

                if (passesSearchFilter && passesSitusFilter) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Search functionality
        searchInput.addEventListener('input', e => {
            currentSearchTerm = e.target.value.trim().toLowerCase();

            if (currentSearchTerm) {
                searchClear.classList.remove('hidden');
            } else {
                searchClear.classList.add('hidden');
            }

            filterObjects();
        });

        // Clear search
        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            currentSearchTerm = '';
            searchClear.classList.add('hidden');
            filterObjects();
        });

        // Filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active class
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // Update filter
                currentSitusFilter = button.dataset.situsId || 'all';
                filterObjects();
            });
        });

        // Object card click
        objectCards.forEach(card => {
            card.addEventListener('click', () => {
                const img = card.querySelector('img');
                const situsId = card.dataset.situsId;
                const name = card.dataset.name;
                const description = card.dataset.description;
                const situsName = card.querySelector('p').textContent;

                modalImage.src = img.src;
                modalTitle.textContent = name;
                modalLocation.textContent = situsName;
                modalDescription.textContent = description;

                // Check if site is unlocked
                const isUnlocked = card.querySelector('.bg-green-500') !== null;

                if (isUnlocked) {
                    modalLink.href = "{{ route('guest.situs.detail', ['situs_id' => ':situsId']) }}".replace(':situsId', situsId);
                    modalLink.classList.remove('hidden');
                } else {
                    modalLink.classList.add('hidden');
                }

                modal.classList.remove('hidden');
            });
        });

        // Close modal
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        modalBackdrop.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Add active state styling for filter buttons
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                .filter-btn {
                    background-color: #f3f4f6;
                    color: #374151;
                    transition: all 0.2s;
                }

                .filter-btn:hover {
                    background-color: #e5e7eb;
                }

                .filter-btn.active {
                    background-color: #4f46e5;
                    color: white;
                }
            </style>
        `);
    </script>
</x-guest-layout>
