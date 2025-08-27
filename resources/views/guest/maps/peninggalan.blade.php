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
    <div id="objects-container" class="p-4 grid grid-cols-2 gap-4 pb-24">
        @foreach($objects as $object)
            <div 
                class="object-card bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 hover:-translate-y-1 border border-gray-100"
                data-situs-id="{{ $object->situs_id }}"
                data-name="{{ $object->nama }}"
                data-description="{{ $object->deskripsi }}">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img
                        src="{{ asset('storage/' . $object->gambar_real) }}" 
                        alt="{{ $object->nama }}" 
                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                    
                    @if($object->is_unlocked)
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Terbuka
                            </span>
                        </div>
                    @else
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                Terkunci
                            </span>
                        </div>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="font-medium text-sm text-gray-800 truncate">{{ $object->nama }}</h3>
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
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity duration-300" id="modal-backdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative w-full max-w-md max-h-[90vh] flex flex-col pointer-events-auto">
                <div class="bg-white rounded-2xl flex flex-col shadow-2xl transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
                <!-- Image Section (Fixed Height) -->
                <div class="relative flex-shrink-0">
                    <div class="aspect-[4/3] w-full bg-gray-100">
                        <img id="modal-image" src="" alt="" class="w-full h-full object-cover">
                    </div>
                    <button id="close-modal" class="absolute top-4 right-4 bg-white/90 text-gray-800 rounded-full p-2 shadow-lg hover:bg-white hover:scale-110 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Content Section (Scrollable) -->
                <div class="flex-1 overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-start mb-4">
                            <div class="flex-1 min-w-0">
                                <h2 id="modal-title" class="text-2xl font-bold text-gray-900 mb-1 break-words"></h2>
                                <div class="flex items-center text-sm text-blue-600 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span id="modal-location" class="truncate"></span>
                                </div>
                            </div>
                            <div id="status-badge" class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full whitespace-nowrap ml-2">
                                Terbuka
                            </div>
                        </div>
                        <div class="prose prose-sm text-gray-600">
                            <p id="modal-description" class="leading-relaxed"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Button (Fixed at bottom) -->
                <div class="border-t border-gray-100 p-4 bg-gray-50 flex-shrink-0">
                    <a id="modal-link" href="#" class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium py-3 px-4 rounded-xl hover:shadow-lg hover:shadow-blue-100 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        Kunjungi Situs
                    </a>
                    <div id="locked-message" class="hidden mt-2 text-center text-sm text-gray-600">
                        Selesaikan materi <span id="situs-name" class="font-medium"></span> untuk membuka peninggalan ini
                    </div>
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
                const isUnlocked = card.querySelector('.bg-green-100') !== null;

                // Update modal content
                modalImage.src = img.src;
                modalImage.alt = name;
                modalTitle.textContent = name;
                modalLocation.textContent = situsName;
                modalDescription.textContent = description;

                // Update status badge and action section
                const statusBadge = document.getElementById('status-badge');
                const lockedMessage = document.getElementById('locked-message');
                const situsNameElement = document.getElementById('situs-name');
                
                if (isUnlocked) {
                    statusBadge.textContent = 'Terbuka';
                    statusBadge.className = 'bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full whitespace-nowrap ml-2';
                    modalLink.href = "{{ route('guest.situs.detail', ['situs_id' => ':situsId']) }}".replace(':situsId', situsId);
                    modalLink.classList.remove('hidden');
                    lockedMessage.classList.add('hidden');
                } else {
                    statusBadge.textContent = 'Terkunci';
                    statusBadge.className = 'bg-gray-200 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full whitespace-nowrap ml-2';
                    modalLink.classList.add('hidden');
                    lockedMessage.classList.remove('hidden');
                    situsNameElement.textContent = situsName;
                }

                // Show modal
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
                
                // Trigger animation
                setTimeout(() => {
                    const modalContent = document.getElementById('modal-content');
                    if (modalContent) {
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }
                }, 10);
            });
        });

        // Close modal function
        function closeModal() {
            const modalContent = document.getElementById('modal-content');
            if (modalContent) {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Re-enable scrolling
            }, 200);
        }

        // Close modal when clicking close button
        closeModalBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside modal content
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target === modalBackdrop) {
                closeModal();
            }
        });

        // Add active state styling for filter buttons
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                .filter-btn {
                    background-color: #f8fafc;
                    color: #4b5563;
                    border: 1px solid #e2e8f0;
                    transition: all 0.2s;
                    font-weight: 500;
                    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                }

                .filter-btn:hover {
                    background-color: #f1f5f9;
                    transform: translateY(-1px);
                }

                .filter-btn.active {
                    background: linear-gradient(135deg, #4f46e5, #6366f1);
                    color: white;
                    border-color: #4f46e5;
                    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1), 0 2px 4px -1px rgba(79, 70, 229, 0.06);
                }

                /* Custom scrollbar for modal */
                ::-webkit-scrollbar {
                    width: 6px;
                    height: 6px;
                }
                
                ::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 10px;
                }
                
                ::-webkit-scrollbar-thumb {
                    background: #c7d2fe;
                    border-radius: 10px;
                }
                
                ::-webkit-scrollbar-thumb:hover {
                    background: #a5b4fc;
                }
            </style>
        `);

        // Add animation when modal opens
        document.querySelectorAll('.object-card').forEach(card => {
            card.addEventListener('click', () => {
                setTimeout(() => {
                    const modal = document.getElementById('modal-content');
                    if (modal) {
                        modal.classList.remove('scale-95', 'opacity-0');
                        modal.classList.add('scale-100', 'opacity-100');
                    }
                }, 10);
            });
        });

        // Close modal animation
        document.getElementById('close-modal')?.addEventListener('click', () => {
            const modal = document.getElementById('modal-content');
            if (modal) {
                modal.classList.remove('scale-100', 'opacity-100');
                modal.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                document.getElementById('object-modal').classList.add('hidden');
            }, 200);
        });

        document.getElementById('modal-backdrop')?.addEventListener('click', () => {
            const modal = document.getElementById('modal-content');
            if (modal) {
                modal.classList.remove('scale-100', 'opacity-100');
                modal.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                document.getElementById('object-modal').classList.add('hidden');
            }, 200);
        });
    </script>
</x-guest-layout>
