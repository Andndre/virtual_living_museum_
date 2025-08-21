<x-elearning-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
            <a href="{{ route('guest.elearning.materi', $ebook->materi_id) }}" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex-1 text-center mx-4">
                <h1 class="text-lg font-bold">{{ $ebook->judul }}</h1>
                <p class="text-sm opacity-90">{{ $ebook->materi->judul }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/30 hover:border-white/50 transition-colors">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture" class="w-full h-full object-cover" />
                @else
                    <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture" class="w-full h-full object-cover" />
                @endif
            </a>
        </div>
    </div>

    {{-- E-book Viewer Section --}}
    <div class="bg-white min-h-screen">
        <div class="container mx-auto px-2 py-3 md:px-4 md:py-6">
            {{-- Flipbook Container --}}
            <div id="flipbook-container" class="w-full bg-gray-100 rounded-xl md:rounded-2xl shadow-lg overflow-hidden relative min-h-[85vh] md:min-h-[80vh]">
                <div class="loading-container flex items-center justify-center h-96">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">Memuat e-book...</p>
                    </div>
                </div>
                
                {{-- Flipbook Element --}}
                <div id="flipbook" class="flipbook hidden mx-auto">
                    <!-- Pages will be dynamically inserted here -->
                </div>
            </div>

            {{-- Navigation Controls --}}
            <div class="flex justify-center items-center space-x-2 md:space-x-4 mt-3 md:mt-6">
                <button id="prev-page" class="px-3 py-2 md:px-4 md:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-300 text-sm md:text-base" disabled>
                    <i class="fas fa-chevron-left mr-1 md:mr-2"></i>
                    <span class="hidden sm:inline">Sebelumnya</span>
                    <span class="sm:hidden">Prev</span>
                </button>
                
                <div class="flex items-center space-x-1 md:space-x-2">
                    <span class="text-xs md:text-sm text-gray-600">Hal.</span>
                    <span id="current-page" class="font-semibold text-sm md:text-base">1</span>
                    <span class="text-xs md:text-sm text-gray-600">dari</span>
                    <span id="total-pages" class="font-semibold text-sm md:text-base">-</span>
                </div>
                
                <button id="next-page" class="px-3 py-2 md:px-4 md:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm md:text-base">
                    <span class="hidden sm:inline">Selanjutnya</span>
                    <span class="sm:hidden">Next</span>
                    <i class="fas fa-chevron-right ml-1 md:ml-2"></i>
                </button>
            </div>

            {{-- Additional Controls --}}
            <div class="flex justify-center items-center space-x-2 md:space-x-4 mt-3 md:mt-4">
                <button id="zoom-out" class="px-2 py-2 md:px-3 md:py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-search-minus text-sm md:text-base"></i>
                </button>
                
                <span id="zoom-level" class="text-xs md:text-sm text-gray-600">100%</span>
                
                <button id="zoom-in" class="px-2 py-2 md:px-3 md:py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-search-plus text-sm md:text-base"></i>
                </button>
                
                <button id="fullscreen" class="px-2 py-2 md:px-3 md:py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-expand text-sm md:text-base"></i>
                </button>
                
                <button id="auto-flip" class="px-2 py-2 md:px-3 md:py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-xs md:text-sm">
                    <i class="fas fa-play mr-1"></i>
                    <span class="hidden sm:inline">Auto Flip</span>
                    <span class="sm:hidden">Auto</span>
                </button>
            </div>
        </div>
    </div>

    {{-- PDF.js & Turn.js Libraries --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/blasten/turn.js@master/turn.min.js"></script>
    <script>
        $(document).ready(function() {
            if (typeof $.fn.turn !== 'undefined') {
                initializePDFViewer();
            } else {
                showError();
            }
        });
    </script>
    
    {{-- Enhanced Flipbook Script --}}
    <script>
        // Set PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let currentPage = 1;
        let totalPages = 0;
        let scale = 1.0;
        let isAutoFlipping = false;
        let autoFlipInterval = null;
        let renderedPages = {};
        let flipbookInitialized = false;

        // PDF URL
        const pdfUrl = '{{ asset("storage/" . $ebook->path_file) }}';

        // Materi URL for redirect after finish
        const materiUrl = @json(route('guest.elearning.materi', $ebook->materi_id));

        // Show finish dialog
        function showFinishDialog() {
            if (document.getElementById('finish-dialog')) return;
            const dialog = document.createElement('div');
            dialog.id = 'finish-dialog';
            dialog.className = 'fixed inset-0 flex items-center justify-center z-50 bg-black/40';
            dialog.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl p-6 max-w-xs w-full text-center animate-fadeIn">
                    <h2 class="text-lg font-bold mb-2">E-Book Selesai Dibaca!</h2>
                    <p class="mb-4 text-gray-700">Anda telah membaca semua halaman e-book ini.<br>Lanjut ke materi atau tetap membaca?</p>
                    <div class="flex justify-center gap-2">
                        <button id="btn-to-materi" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Kembali ke Materi</button>
                        <button id="btn-continue-reading" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Tetap Membaca</button>
                    </div>
                </div>
            `;
            document.body.appendChild(dialog);
            document.getElementById('btn-to-materi').onclick = function() {
                window.location.href = materiUrl;
            };
            document.getElementById('btn-continue-reading').onclick = function() {
                dialog.remove();
            };
        }

        // Main initialization function
        function initializePDFViewer() {
            // Load PDF and initialize flipbook
            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                pdfDoc = pdf;
                totalPages = pdf.numPages;
                document.getElementById('total-pages').textContent = totalPages;
                
                // Pre-render first few pages for smooth experience
                renderMultiplePages().then(() => {
                    if (typeof $.fn.turn !== 'undefined') {
                        initializeFlipbook();
                    } else {
                        initializeSimpleViewer();
                    }
                    hideLoading();
                });
            }).catch(function(error) {
                console.error('Error loading PDF:', error);
                showError();
            });
        }



        // Render multiple pages for flipbook
        async function renderMultiplePages() {
            const promises = [];
            const pagesToPreload = Math.min(2, totalPages); // Preload hanya 2 halaman pertama
            for (let i = 1; i <= pagesToPreload; i++) {
                promises.push(renderPageToCanvas(i));
            }
            await Promise.all(promises);
        }

        // Render a specific page to canvas
        function renderPageToCanvas(pageNum) {
            return new Promise((resolve) => {
                if (renderedPages[pageNum]) {
                    resolve(renderedPages[pageNum]);
                    return;
                }

                pdfDoc.getPage(pageNum).then(function(page) {
                    // Use higher scale for single page display, extra high for mobile
                    const isMobile = window.innerWidth <= 768;
                    const renderScale = isMobile ? Math.max(scale * 2.5, 2.5) : Math.max(scale * 2, 2.0);
                    const viewport = page.getViewport({ scale: renderScale });
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };

                    page.render(renderContext).promise.then(function() {
                        renderedPages[pageNum] = canvas;
                        resolve(canvas);
                    });
                });
            });
        }

        // Initialize Turn.js flipbook
        function initializeFlipbook() {
            if (typeof $.fn.turn === 'undefined') {
                showError();
                return;
            }

            const flipbook = $('#flipbook');
            flipbook.empty();
            for (let i = 1; i <= totalPages; i++) {
                const pageDiv = $(`<div class="page page-${i}"></div>`);
                if (renderedPages[i]) {
                    pageDiv.append(renderedPages[i]);
                } else {
                    pageDiv.html(`
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                                <p class="text-sm text-gray-600">Loading page ${i}...</p>
                            </div>
                        </div>
                    `);
                }
                flipbook.append(pageDiv);
            }
            try {
                flipbook.turn({
                    width: 700,
                    height: 900,
                    autoCenter: true,
                    display: 'single',
                    acceleration: true,
                    gradients: true,
                    elevation: 50,
                    when: {
                        turning: function(event, page, view) {
                            currentPage = page;
                            updatePageInfo();
                            updateNavigationButtons();
                            if (!renderedPages[page]) {
                                loadPageLazy(page);
                            }
                        },
                        turned: function(event, page, view) {
                            const nextPage = page + 1;
                            if (nextPage <= totalPages && !renderedPages[nextPage]) {
                                loadPageLazy(nextPage);
                            }
                            if (page === totalPages) {
                                fetch(`{{ url('/elearning/ebook/' . $ebook->ebook_id . '/read') }}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                }).then(() => {
                                    showFinishDialog();
                                });
                            }
                        }
                    }
                });
                flipbook.removeClass('hidden');
                flipbookInitialized = true;
                updatePageInfo();
                updateNavigationButtons();
            } catch (error) {
                showError();
            }
        }

        // Load page lazily
        function loadPageLazy(pageNum) {
            if (renderedPages[pageNum]) return;
            
            renderPageToCanvas(pageNum).then((canvas) => {
                const pageDiv = $(`.page-${pageNum}`);
                pageDiv.empty().append(canvas);
            });
        }

        function updatePageInfo() {
            document.getElementById('current-page').textContent = currentPage;
        }

        function updateNavigationButtons() {
            document.getElementById('prev-page').disabled = currentPage <= 1;
            document.getElementById('next-page').disabled = currentPage >= totalPages;
        }

        function hideLoading() {
            const loadingContainer = document.querySelector('.loading-container');
            if (loadingContainer) {
                loadingContainer.style.display = 'none';
            }
        }

        function showError() {
            const container = document.getElementById('flipbook-container');
            container.innerHTML = `
                <div class="flex items-center justify-center h-96">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                        <p class="text-gray-600">Gagal memuat e-book</p>
                        <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Coba Lagi
                        </button>
                    </div>
                </div>
            `;
        }

        // Navigation event listeners
        document.getElementById('prev-page').addEventListener('click', function() {
            if (flipbookInitialized && typeof $.fn.turn !== 'undefined') {
                $('#flipbook').turn('previous');
            }
        });

        document.getElementById('next-page').addEventListener('click', function() {
            if (flipbookInitialized && typeof $.fn.turn !== 'undefined') {
                $('#flipbook').turn('next');
            }
        });

        // Zoom controls
        document.getElementById('zoom-in').addEventListener('click', function() {
            scale += 0.25;
            updateZoom();
        });

        document.getElementById('zoom-out').addEventListener('click', function() {
            if (scale > 0.5) {
                scale -= 0.25;
                updateZoom();
            }
        });

        function updateZoom() {
            document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
            if (flipbookInitialized && typeof $.fn.turn !== 'undefined') {
                // Re-render visible pages with new scale
                const currentPages = $('#flipbook').turn('view');
                currentPages.forEach(page => {
                    if (page > 0) {
                        delete renderedPages[page];
                        loadPageLazy(page);
                    }
                });
                // Update flipbook size for single page
                const newWidth = 700 * scale;
                const newHeight = 900 * scale;
                $('#flipbook').turn('size', newWidth, newHeight);
            }
        }

        // Auto flip functionality
        document.getElementById('auto-flip').addEventListener('click', function() {
            const button = this;
            if (isAutoFlipping) {
                clearInterval(autoFlipInterval);
                isAutoFlipping = false;
                button.innerHTML = '<i class="fas fa-play mr-1"></i> Auto Flip';
                button.classList.remove('bg-red-500', 'hover:bg-red-600');
                button.classList.add('bg-green-500', 'hover:bg-green-600');
            } else {
                autoFlipInterval = setInterval(() => {
                    if (currentPage < totalPages) {
                        if (flipbookInitialized && typeof $.fn.turn !== 'undefined') {
                            $('#flipbook').turn('next');
                        } else {
                            renderSimplePage(currentPage + 1);
                        }
                    } else {
                        // Stop auto flip when reaching end
                        button.click();
                    }
                }, 3000); // Flip every 3 seconds
                
                isAutoFlipping = true;
                button.innerHTML = '<i class="fas fa-stop mr-1"></i> Stop Auto';
                button.classList.remove('bg-green-500', 'hover:bg-green-600');
                button.classList.add('bg-red-500', 'hover:bg-red-600');
            }
        });

        // Fullscreen control
        document.getElementById('fullscreen').addEventListener('click', function() {
            const container = document.getElementById('flipbook-container');
            if (container.requestFullscreen) {
                container.requestFullscreen();
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            } else if (container.mozRequestFullScreen) {
                container.mozRequestFullScreen();
            } else if (container.msRequestFullscreen) {
                container.msRequestFullscreen();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                if (flipbookInitialized && typeof $.fn.turn !== 'undefined') {
                    $('#flipbook').turn('previous');
                }
            } else if (e.key === 'ArrowRight') {
                if (flipbookInitialized && typeof $.fn.turn !== 'undefined') {
                    $('#flipbook').turn('next');
                }
            } else if (e.key === 'Escape' && isAutoFlipping) {
                document.getElementById('auto-flip').click();
            }
        });

        // Handle window resize
        $(window).resize(function() {
            if (flipbookInitialized && typeof $.fn.turn !== 'undefined') {
                const container = $('#flipbook-container');
                const containerWidth = container.width();
                const containerHeight = container.height();
                
                // Calculate optimal size for single page display
                const maxWidth = Math.min(containerWidth - 40, 700);
                const maxHeight = Math.min(containerHeight - 40, 900);
                
                // Maintain aspect ratio (A4-like: 0.77)
                const aspectRatio = 700 / 900;
                
                let finalWidth, finalHeight;
                if (maxWidth / maxHeight > aspectRatio) {
                    finalHeight = maxHeight;
                    finalWidth = finalHeight * aspectRatio;
                } else {
                    finalWidth = maxWidth;
                    finalHeight = finalWidth / aspectRatio;
                }
                
                $('#flipbook').turn('size', finalWidth, finalHeight);
            }
        });
    </script>

    <style>
        /* Fade in animation for dialog */
        .animate-fadeIn {
            animation: fadeIn 0.3s ease;
        }

        /* Flipbook Styles for Single Page */
        .flipbook {
            margin: 20px auto;
            width: 100%;
            max-width: 900px;
            aspect-ratio: 210/297;
            min-height: 320px;
            background: white;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .flipbook .page {
            background-color: white;
            background-size: 100% 100%;
            border: 1px solid #ccc;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            width: 100%;
            height: 100%;
            aspect-ratio: 210/297;
            overflow: hidden;
        }

        .flipbook .page canvas {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain;
            max-width: 100%;
            max-height: 100%;
            aspect-ratio: 210/297;
            background: white;
        }

        .flipbook .even {
            background: linear-gradient(135deg, #fff 0%, #f9f9f9 100%);
        }

        .flipbook .odd {
            background: linear-gradient(135deg, #fff 0%, #f9f9f9 100%);
        }

        /* Container improvements for single page */
        #flipbook-container {
            padding: 20px;
            min-height: 85vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Responsive adjustments for single page */
        @media (max-width: 1024px) {
            .flipbook {
                max-width: 100vw;
                min-height: 220px;
            }
            #flipbook-container {
                min-height: 85vh;
            }
        }

        @media (max-width: 768px) {
            .flipbook {
                max-width: 100vw;
                min-height: 180px;
            }
            #flipbook-container {
                min-height: 90vh;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .flipbook {
                max-width: 100vw;
                min-height: 120px;
            }
            #flipbook-container {
                min-height: 95vh;
                padding: 4px;
            }
        }

        /* Fullscreen styles for single page */
        #flipbook-container:fullscreen {
            background: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        #flipbook-container:fullscreen .flipbook {
            margin: 0;
            width: 80vw !important;
            height: 95vh !important;
        }

        /* Mobile fullscreen adjustments */
        @media (max-width: 768px) {
            #flipbook-container:fullscreen .flipbook {
                width: 95vw !important;
                height: 98vh !important;
            }
        }

        /* Loading animation for pages */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page canvas {
            animation: fadeIn 0.4s ease-out;
        }

        /* Turn.js custom styles for single page */
        .turn-page {
            background-color: #fafafa;
            border-radius: 8px;
        }

        .shadow {
            -webkit-transition: -webkit-box-shadow 0.5s;
            -moz-transition: -moz-box-shadow 0.5s;
            -o-transition: box-shadow 0.5s;
            transition: box-shadow 0.5s;
        }

        /* Simple viewer improvements */
        #simple-canvas {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2);
            max-width: 100%;
            height: auto;
        }

        /* Mobile canvas improvements */
        @media (max-width: 480px) {
            #simple-canvas {
                border-radius: 8px;
                border-width: 1px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            }
        }

        /* Page turning effect improvements */
        .flipbook .turn-page-wrapper {
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
</x-elearning-layout>
