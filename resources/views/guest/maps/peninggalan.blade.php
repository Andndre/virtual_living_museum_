<x-guest-layout>
    <div class="bg-primary p-4 text-white">
        <div class="mb-4 flex items-center">
            <button class="back-button mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </button>
            <h1 class="text-xl font-bold">{{ __('maps.virtual_living_museum') }}</h1>
        </div>

        <!-- Search Bar -->
        <form id="search-form" action="{{ route('guest.maps.peninggalan') }}" method="GET" class="relative">
            <div class="flex items-center rounded-full bg-white/20 p-2">
                <div class="ml-2 mr-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input id="search-input" name="q" type="search" inputmode="search" autocomplete="off"
                    value="{{ $searchQuery ?? '' }}" placeholder="{{ __('maps.search_placeholder_peninggalan') }}"
                    class="w-full border-none bg-transparent text-white placeholder-white/70 focus:outline-none focus:ring-0"
                    style="touch-action: manipulation;" aria-label="{{ __('maps.search_placeholder_peninggalan') }}">
                @if (isset($searchQuery) && !empty($searchQuery))
                    <a href="{{ route('guest.maps.peninggalan') }}" class="mr-2 text-white/70 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Filter Tabs -->
    <div class="overflow-x-auto border-b bg-white" role="tablist" aria-label="Filter peninggalan">
        <div class="flex min-w-max p-2">
            <button id="filter-all"
                class="filter-btn active mr-2 cursor-pointer whitespace-nowrap rounded-full px-4 py-3 text-sm font-medium"
                role="tab" aria-selected="true">
                {{ __('maps.all') }}
            </button>
            @foreach ($situs as $s)
                <button data-situs-id="{{ $s->situs_id }}"
                    class="filter-btn mr-2 cursor-pointer whitespace-nowrap rounded-full px-4 py-3 text-sm font-medium"
                    role="tab" aria-selected="false">
                    {{ $s->nama }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Search Results -->
    <div id="search-results" class="p-4 pb-24">
        @if (isset($searchQuery) && !empty($searchQuery))
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800">@lang('maps.search_results_for', ['query' => $searchQuery])</h2>
                <p class="text-sm text-gray-500">{{ $objects->count() }}@lang('maps.results_found')</p>
            </div>
        @endif

        @php
            $groupedResults = $objects->groupBy('type');
            $sections = [
                'situs' => [
                    'title' => __('maps.historical_sites'),
                    'icon' => 'M12 21v-8.25M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM3.75 12h.008v.008H3.75V12z',
                    'color' => 'blue',
                ],
                'museum' => [
                    'title' => __('maps.virtual_museum'),
                    'icon' =>
                        'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    'color' => 'purple',
                ],
                'object' => [
                    'title' => __('maps.heritage_objects'),
                    'icon' =>
                        'M2.25 12c0-2.8 2.2-5 5-5h9.5c2.8 0 5 2.2 5 5v6c0 2.8-2.2 5-5 5h-9.5c-2.8 0-5-2.2-5-5v-6z',
                    'color' => 'green',
                ],
            ];
        @endphp

        @foreach ($sections as $type => $section)
            @if (isset($groupedResults[$type]) && $groupedResults[$type]->isNotEmpty())
                <div class="mb-8">
                    <div class="mb-4 flex items-center">
                        <div
                            class="bg-{{ $section['color'] }}-100 text-{{ $section['color'] }}-700 mr-3 rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $section['icon'] }}" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $section['title'] }}</h3>
                        <span class="ml-2 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                            {{ $groupedResults[$type]->count() }} @lang('maps.item')
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($groupedResults[$type] as $item)
                            @if ($type === 'situs')
                                <!-- Situs Card -->
                                <div
                                    class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                                    <a href="{{ route('guest.situs.detail', $item->situs_id) }}" class="block">
                                        <div class="relative aspect-[4/3] overflow-hidden">
                                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->nama }}"
                                                class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent">
                                            </div>
                                            <div class="absolute bottom-0 left-0 p-4 text-white">
                                                <h3 class="text-lg font-bold">{{ $item->nama }}</h3>
                                                <p class="line-clamp-2 text-sm text-white/90">{{ $item->deskripsi }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @elseif($type === 'museum')
                                <!-- Museum Card -->
                                <div
                                    class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                                    <a href="#" class="block">
                                        <div class="relative aspect-[4/3] overflow-hidden">
                                            <div
                                                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-purple-50 to-blue-50">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-12 w-12 text-purple-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent">
                                            </div>
                                            <div class="absolute bottom-0 left-0 p-4 text-white">
                                                <h3 class="text-lg font-bold">{{ $item->nama }}</h3>
                                                <p class="text-sm text-white/90">{{ $item->situsPeninggalan->nama }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @else
                                <!-- Object Card -->
                                <div class="object-card cursor-pointer overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md"
                                    data-situs-id="{{ $item->situs_id }}" data-name="{{ $item->nama }}"
                                    data-description="{{ $item->deskripsi }}" role="button" tabindex="0"
                                    aria-label="Lihat detail {{ $item->nama }}">
                                    <div class="relative aspect-[4/3] overflow-hidden">
                                        <img src="{{ asset('storage/' . $item->gambar_real) }}"
                                            alt="{{ $item->nama }}"
                                            class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">

                                        @if ($item->is_unlocked)
                                            <div class="absolute right-2 top-2">
                                                <span
                                                    class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    @lang('maps.unlocked')
                                                </span>
                                            </div>
                                        @else
                                            <div class="absolute right-2 top-2">
                                                <span
                                                    class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    @lang('maps.locked')
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <h3 class="truncate text-sm font-medium text-gray-800">{{ $item->nama }}
                                        </h3>
                                        <p class="truncate text-xs text-gray-500">{{ $item->situsPeninggalan->nama }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if ($objects->isEmpty() && isset($searchQuery))
            <div class="col-span-2 py-12 text-center">
                <div class="mb-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="mx-auto h-16 w-16">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900">@lang('maps.no_results_found')</h3>
                <p class="mt-2 text-gray-500">@lang('maps.try_different_keywords')</p>
                <a href="{{ route('guest.maps.peninggalan') }}"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    @lang('maps.show_all')
                </a>
            </div>
        @endif
    </div>

    <!-- No Results Message -->
    <div id="no-results" class="hidden p-8 text-center">
        <div class="mb-3 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="mx-auto h-12 w-12">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">@lang('maps.not_found')</h3>
        <p class="mt-1 text-sm text-gray-500">@lang('maps.no_heritage_matches_search')</p>
    </div>

    <!-- Object Detail Modal -->
    <div id="object-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true"
        aria-labelledby="modal-title" aria-describedby="modal-description">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity duration-300"
            id="modal-backdrop"></div>
        <div class="pointer-events-none absolute inset-0 flex items-center justify-center p-4">
            <div class="pointer-events-auto relative flex max-h-[90vh] w-full max-w-md flex-col">
                <div class="flex scale-95 transform flex-col rounded-2xl bg-white opacity-0 shadow-2xl transition-all duration-300"
                    id="modal-content">
                    <!-- Image Section (Fixed Height) -->
                    <div class="relative flex-shrink-0">
                        <div class="aspect-[4/3] w-full bg-gray-100">
                            <img id="modal-image" src="" alt="" class="h-full w-full object-cover">
                        </div>
                        <button id="close-modal"
                            class="absolute right-4 top-4 rounded-full bg-white/90 p-2 text-gray-800 shadow-lg transition-all duration-200 hover:scale-110 hover:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            aria-label="{{ __('maps.close_modal') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content Section (Scrollable) -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="p-6">
                            <div class="mb-4 flex items-start">
                                <div class="min-w-0 flex-1">
                                    <h2 id="modal-title" class="mb-1 break-words text-2xl font-bold text-gray-900">
                                    </h2>
                                    <div class="mt-1 flex items-center text-sm text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4 flex-shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span id="modal-location" class="truncate"></span>
                                    </div>
                                </div>
                                <div id="status-badge"
                                    class="ml-2 whitespace-nowrap rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800"
                                    data-unlocked="{{ __('maps.unlocked') }}" data-locked="{{ __('maps.locked') }}">
                                    @lang('maps.unlocked')
                                </div>
                            </div>
                            <div class="prose prose-sm text-gray-600">
                                <p id="modal-description" class="leading-relaxed"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button (Fixed at bottom) -->
                    <div class="flex-shrink-0 border-t border-gray-100 bg-gray-50 p-4">
                        <a id="modal-link" href="#"
                            class="block flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3 text-center font-medium text-white transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            @lang('maps.visit_site')
                        </a>
                        <div id="locked-message" class="mt-2 hidden text-center text-sm text-gray-600">
                            @lang('maps.complete_material') <span id="situs-name" class="font-medium"></span> @lang('maps.to_unlock_heritage')
                        </div>
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

        // Object card click and keyboard support
        function openObjectModal(card) {
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
                statusBadge.textContent = statusBadge.dataset.unlocked;
                statusBadge.className =
                    'ml-2 whitespace-nowrap rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800';
                modalLink.href = "{{ route('guest.situs.detail', ['situs_id' => ':situsId']) }}"
                    .replace(':situsId', situsId);
                modalLink.classList.remove('hidden');
                lockedMessage.classList.add('hidden');
            } else {
                statusBadge.textContent = statusBadge.dataset.locked;
                statusBadge.className =
                    'ml-2 whitespace-nowrap rounded-full bg-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-800';
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
        }

        // Add click and keyboard listeners to object cards
        objectCards.forEach(card => {
            card.addEventListener('click', () => openObjectModal(card));
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openObjectModal(card);
                }
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

                .filter-btn:focus-visible {
                    outline: 2px solid #4f46e5;
                    outline-offset: 2px;
                }

                .filter-btn.active {
                    background: linear-gradient(135deg, #4f46e5, #6366f1);
                    color: white;
                    border-color: #4f46e5;
                    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1), 0 2px 4px -1px rgba(79, 70, 229, 0.06);
                }

                /* Reduced motion */
                @media (prefers-reduced-motion: reduce) {
                    .filter-btn,
                    .filter-btn:hover {
                        transition: none;
                        transform: none;
                    }
                    .modal-content,
                    #modal-content {
                        transition: opacity 0.01ms !important;
                        transform: none !important;
                    }
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
