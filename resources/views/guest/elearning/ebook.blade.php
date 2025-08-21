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
            <div id="flipbook-container" class="w-full bg-gray-100 rounded-xl md:rounded-2xl shadow-lg overflow-hidden flex flex-col items-center justify-center min-h-[85vh] md:min-h-[80vh] relative">
                <!-- Loading Spinner -->
                <div class="loading-container absolute inset-0 flex items-center justify-center z-40 bg-white/80">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">Memuat e-book...</p>
                    </div>
                </div>
                {{-- Flipbook Element --}}
                <div class="w-full flex justify-center items-center">
                    <div id="flipbook" class="flipbook hidden relative">
                        <!-- Overlay hanya menutupi flipbook -->
                        <div id="flipbook-start-overlay" class="absolute left-0 top-0 w-full h-full z-50 flex flex-col items-center justify-center bg-black/60 text-white text-center cursor-pointer select-none" style="backdrop-filter: blur(2px);">
                            <div>
                                <div class="text-2xl font-bold mb-2">Klik untuk mulai membaca</div>
                                <div class="mb-4 text-base">E-book akan tampil fullscreen</div>
                                <button class="px-6 py-3 bg-blue-600 rounded-lg font-semibold text-white text-lg hover:bg-blue-700 transition">Mulai Membaca</button>
                            </div>
                        </div>
                        <!-- PageFlip container -->
                        <div id="pageflip-viewer" class="w-full h-full"></div>
                        <!-- Fullscreen controls (bottom, small, horizontal) -->
                        <div id="fullscreen-controls" class="hidden absolute left-1/2 bottom-4 -translate-x-1/2 z-50 flex items-center gap-3">
                            <button id="fullscreen-prev" class="bg-black/40 hover:bg-black/70 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl focus:outline-none transition" style="backdrop-filter: blur(2px);">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div id="fullscreen-page-indicator" class="pointer-events-none select-none text-black text-base font-semibold bg-white/80 rounded-lg px-4 py-1 shadow"></div>
                            <button id="fullscreen-next" class="bg-black/40 hover:bg-black/70 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl focus:outline-none transition" style="backdrop-filter: blur(2px);">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
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
    <!-- JANGAN ADA JQUERY LAIN DI LAYOUT/HEADER -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js"></script>
    @vite(['resources/js/ebook.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.initEbookPageFlip({
                pdfUrl: @json(asset('storage/' . $ebook->path_file)),
                totalPages: {{ $ebook->jumlah_halaman ?? 'null' }},
                materiUrl: @json(route('guest.elearning.materi', $ebook->materi_id)),
                ebookId: {{ $ebook->ebook_id }},
                csrfToken: @json(csrf_token())
            });
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

        .flipbook .page canvas,
        .flipbook .page img {
            display: block;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            margin: 0 auto;
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
