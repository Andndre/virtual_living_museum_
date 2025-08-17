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
        <div class="container mx-auto px-4 py-6">
            {{-- PDF Viewer Container --}}
            <div id="flipbook-container" class="w-full bg-gray-100 rounded-2xl shadow-lg overflow-hidden">
                <div id="flipbook" class="flipbook-viewport">
                    <div class="loading-container flex items-center justify-center h-96">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-600">Memuat e-book...</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Controls --}}
            <div class="flex justify-center items-center space-x-4 mt-6">
                <button id="prev-page" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-300" disabled>
                    <i class="fas fa-chevron-left mr-2"></i>
                    Sebelumnya
                </button>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Halaman</span>
                    <span id="current-page" class="font-semibold">1</span>
                    <span class="text-sm text-gray-600">dari</span>
                    <span id="total-pages" class="font-semibold">-</span>
                </div>
                
                <button id="next-page" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Selanjutnya
                    <i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>

            {{-- Additional Controls --}}
            <div class="flex justify-center items-center space-x-4 mt-4">
                <button id="zoom-out" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-search-minus"></i>
                </button>
                
                <span id="zoom-level" class="text-sm text-gray-600">100%</span>
                
                <button id="zoom-in" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-search-plus"></i>
                </button>
                
                <button id="fullscreen" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- PDF.js Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    {{-- Flipbook Script --}}
    <script>
        // Set PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let currentPage = 1;
        let totalPages = 0;
        let scale = 1.0;
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // PDF URL
        const pdfUrl = '{{ asset("storage/" . $ebook->path_file) }}';

        // Load PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            pdfDoc = pdf;
            totalPages = pdf.numPages;
            document.getElementById('total-pages').textContent = totalPages;
            renderPage(currentPage);
            hideLoading();
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
            showError();
        });

        function renderPage(pageNum) {
            if (!pdfDoc) return;

            pdfDoc.getPage(pageNum).then(function(page) {
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                page.render(renderContext).promise.then(function() {
                    // Update flipbook container
                    const flipbook = document.getElementById('flipbook');
                    flipbook.innerHTML = '';
                    flipbook.appendChild(canvas);
                    
                    // Update page number
                    document.getElementById('current-page').textContent = pageNum;
                    
                    // Update navigation buttons
                    document.getElementById('prev-page').disabled = pageNum <= 1;
                    document.getElementById('next-page').disabled = pageNum >= totalPages;
                });
            });
        }

        function hideLoading() {
            const loadingContainer = document.querySelector('.loading-container');
            if (loadingContainer) {
                loadingContainer.style.display = 'none';
            }
        }

        function showError() {
            const flipbook = document.getElementById('flipbook');
            flipbook.innerHTML = `
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
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        document.getElementById('next-page').addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });

        // Zoom controls
        document.getElementById('zoom-in').addEventListener('click', function() {
            scale += 0.25;
            document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
            renderPage(currentPage);
        });

        document.getElementById('zoom-out').addEventListener('click', function() {
            if (scale > 0.5) {
                scale -= 0.25;
                document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
                renderPage(currentPage);
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
            if (e.key === 'ArrowLeft' && currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            } else if (e.key === 'ArrowRight' && currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });
    </script>

    <style>
        .flipbook-viewport {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 600px;
            padding: 20px;
            background: #f8f9fa;
        }

        .flipbook-viewport canvas {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: white;
            max-width: 100%;
            height: auto;
        }

        #flipbook-container:-webkit-full-screen .flipbook-viewport {
            min-height: 100vh;
        }

        #flipbook-container:-moz-full-screen .flipbook-viewport {
            min-height: 100vh;
        }

        #flipbook-container:fullscreen .flipbook-viewport {
            min-height: 100vh;
        }
    </style>
</x-elearning-layout>
