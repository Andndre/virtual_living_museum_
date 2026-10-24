<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AR Marker Scanner - {{ config('app.name', 'Ganesha AR') }}</title>

    <!-- Tailwind CSS & Font Awesome -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Application Styles & Scripts (Tailwind CSS via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google model-viewer & HTML5 QR Code Scanner -->
    <script>
        self.ModelViewerElement = self.ModelViewerElement || {};
        self.ModelViewerElement.meshoptDecoderLocation = 'https://cdn.jsdelivr.net/npm/meshoptimizer@0.21.0/meshopt_decoder.js';
        self.ModelViewerElement.dracoDecoderLocation = 'https://cdn.jsdelivr.net/npm/three@0.153.0/examples/jsm/libs/draco/';
    </script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@google/model-viewer@3.5.0/dist/model-viewer.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <!-- SweetAlert2 for polished notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #0b0f19;
            font-family: 'Figtree', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        .hidden {
            display: none !important;
        }

        #reader {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
        }

        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        /* Viewfinder Scanner Laser Animation */
        .scan-laser {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #2D8BEB, transparent);
            box-shadow: 0 0 14px #2D8BEB;
            animation: scanMove 2.2s infinite ease-in-out;
        }

        @keyframes scanMove {
            0% { top: 4%; opacity: 0.2; }
            50% { top: 92%; opacity: 1; }
            100% { top: 4%; opacity: 0.2; }
        }

        model-viewer {
            width: 100%;
            height: 100%;
            background-color: #121826;
            outline: none;
        }

        /* Hide native model-viewer default progress bar */
        model-viewer::part(default-progress-bar) {
            display: none;
        }

        /* Hide scrollbars */
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="relative w-full h-full text-gray-100 select-none">

    <!-- ========================================== -->
    <!-- 1. SCANNER VIEW (Active by default)        -->
    <!-- ========================================== -->
    <div id="scanner-view" class="absolute inset-0 flex flex-col justify-between z-20">
        <!-- Scanner Top Bar -->
        <div class="relative z-30 flex items-center justify-between px-4 pt-12 pb-4 bg-gradient-to-b from-black/80 via-black/40 to-transparent">
            @php
                $backUrl = url('/');
                if (auth()->check()) {
                    $backUrl = auth()->user()->role === 'admin' ? route('admin.dashboard') : route('guest.ar-marker');
                }
            @endphp
            <a href="{{ $backUrl }}" id="btn-back"
               class="flex items-center justify-center w-11 h-11 rounded-full bg-white/20 backdrop-blur-md text-white transition active:scale-95 hover:bg-white/30 shadow-lg">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>

            <div class="flex flex-col items-center">
                <span class="text-xs font-semibold tracking-wider uppercase text-blue-400">AR Scanner</span>
                <span class="text-base font-bold text-white">Pindai QR Marker</span>
            </div>

            <!-- Torch / Flash Toggle -->
            <button id="btn-torch" type="button"
                    class="hidden flex items-center justify-center w-11 h-11 rounded-full bg-white/20 backdrop-blur-md text-white transition active:scale-95 hover:bg-white/30 shadow-lg">
                <i class="fas fa-bolt text-lg"></i>
            </button>
            <div id="btn-torch-placeholder" class="w-11 h-11"></div>
        </div>

        <!-- Camera Container -->
        <div class="relative flex-1 w-full overflow-hidden flex items-center justify-center">
            <div id="reader"></div>

            <!-- Viewfinder Target Frame -->
            <div class="absolute pointer-events-none z-10 w-64 h-64 sm:w-72 sm:h-72 border-2 border-white/40 rounded-3xl overflow-hidden shadow-[0_0_0_9999px_rgba(0,0,0,0.55)]">
                <!-- Laser Line -->
                <div class="scan-laser"></div>

                <!-- Corner Highlights -->
                <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-blue-500 rounded-tl-xl"></div>
                <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-blue-500 rounded-tr-xl"></div>
                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-blue-500 rounded-bl-xl"></div>
                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-blue-500 rounded-br-xl"></div>
            </div>
        </div>

        <!-- Scanner Bottom Bar -->
        <div class="relative z-30 px-6 pb-12 pt-4 bg-gradient-to-t from-black/85 via-black/50 to-transparent flex flex-col items-center gap-3">
            <div id="status-badge" class="px-5 py-2.5 rounded-full bg-black/60 backdrop-blur-md border border-white/10 text-xs sm:text-sm font-medium text-center text-gray-200 shadow-md">
                <i class="fas fa-camera text-blue-400 mr-2"></i>Arahkan kamera ke QR Code Marker AR
            </div>

            <!-- Manual Marker Picker Dropdown (Fallback if Camera can't scan or for testing) -->
            <div class="w-full max-w-xs mt-1">
                <button id="btn-toggle-manual" type="button" class="w-full text-xs text-gray-400 hover:text-white underline text-center py-1">
                    Atau pilih objek secara manual &darr;
                </button>
                <div id="manual-picker-container" class="hidden mt-2 bg-gray-900/90 backdrop-blur-lg border border-gray-700 rounded-2xl p-3 shadow-2xl">
                    <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Objek AR:</label>
                    <select id="select-manual-object" class="w-full bg-gray-800 border border-gray-600 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Objek --</option>
                        @foreach ($arMarkers as $marker)
                            @foreach ($marker->virtualMuseumObjects as $obj)
                                <option value="{{ $obj->object_id }}">{{ $obj->nama }} (Marker #{{ $marker->marker_id }})</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. 3D MODEL VIEW (Initially Hidden)        -->
    <!-- ========================================== -->
    <div id="model-view" class="absolute inset-0 z-30 hidden bg-[#0b0f19]">

        <!-- In-App Browser Warning (iOS Safari recommendation for Quick Look) -->
        <div id="iab-warning" class="hidden absolute inset-0 z-50 flex-col items-center justify-center bg-black/90 p-6 text-center text-white backdrop-blur-md">
            <div class="mb-4 rounded-full bg-yellow-500/20 p-4 text-yellow-400">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-yellow-400 mb-2">Buka di Browser Safari</h3>
            <p class="text-sm text-gray-300 max-w-sm mb-6 leading-relaxed">
                Fitur Apple AR Quick Look membutuhkan browser sistem Safari. Silakan ketuk tombol menu di pojok dan pilih <strong>"Buka di Safari"</strong>.
            </p>
            <button type="button" onclick="document.getElementById('iab-warning').classList.add('hidden'); document.getElementById('iab-warning').classList.remove('flex');"
                    class="px-6 py-2.5 rounded-full border border-gray-600 text-sm font-medium hover:bg-gray-800 text-gray-300">
                Lanjutkan di Web Saja
            </button>
        </div>

        <!-- Google <model-viewer> -->
        <model-viewer id="ar-model-viewer"
                      src=""
                      alt="3D AR Model"
                      camera-controls
                      auto-rotate
                      ar
                      ar-scale="auto"
                      ar-placement="floor"
                      bounds="tight"
                      ar-modes="webxr scene-viewer quick-look"
                      quick-look-browsers="safari chrome"
                      environment-image="neutral"
                      exposure="1"
                      shadow-intensity="1">

            <!-- Multi-Object Thumbnail Switcher (Visible only when marker has >= 2 objects) -->
            <div id="multi-object-switcher" class="hidden absolute bottom-44 left-0 right-0 z-40 flex flex-col items-center pointer-events-none px-4">
                <div class="pointer-events-auto flex items-center max-w-full overflow-x-auto py-1.5 px-2.5 rounded-2xl bg-black/65 backdrop-blur-xl border border-white/15 shadow-2xl scrollbar-none">
                    <div id="switcher-items-container" class="flex items-center gap-2.5"></div>
                </div>
            </div>

            <!-- Custom AR Launch Button -->
            <button slot="ar-button" id="custom-ar-button" type="button"
                    class="absolute bottom-28 left-1/2 -translate-x-1/2 flex items-center gap-2 px-6 py-3 rounded-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-2xl transition active:scale-95 border border-blue-400/30 whitespace-nowrap">
                <i class="fas fa-cube text-base"></i>
                <span id="ar-button-text">Tampilkan di Ruang Nyata (AR)</span>
            </button>
        </model-viewer>

        <!-- Audio Player -->
        <audio id="ar-audio" preload="none" class="hidden"></audio>

        <!-- Top Navigation inside 3D View -->
        <div class="absolute top-0 left-0 right-0 z-40 flex items-center justify-between px-4 pt-12 pb-4 bg-gradient-to-b from-black/80 via-black/40 to-transparent pointer-events-none">
            <button id="btn-back-to-scanner" type="button"
                    class="pointer-events-auto flex items-center justify-center w-11 h-11 rounded-full bg-black/60 backdrop-blur-md text-white border border-white/20 transition active:scale-95 hover:bg-black/80 shadow-lg">
                <i class="fas fa-arrow-left text-lg"></i>
            </button>

            <!-- Scan Again Button -->
            <button id="btn-scan-again" type="button"
                    class="pointer-events-auto flex items-center gap-2 px-4 py-2.5 rounded-full bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-lg active:scale-95 transition">
                <i class="fas fa-qrcode"></i>
                <span>Pindai Ulang</span>
            </button>
        </div>

        <!-- Bottom Sheet (Title & Description) -->
        <div class="pointer-events-none absolute inset-0 z-30 flex flex-col justify-end overflow-hidden">
            <!-- Sheet Container -->
            <div id="desc-bottom-sheet"
                 class="pointer-events-auto relative z-10 flex h-[65vh] w-full translate-y-[calc(100%-86px)] flex-col rounded-t-3xl bg-white text-gray-900 shadow-[0_-8px_30px_rgba(0,0,0,0.3)] transition-transform duration-300 ease-in-out">

                <!-- Header / Handle (Always Visible) -->
                <div id="sheet-header" class="flex flex-col items-center px-5 pt-3 pb-3 cursor-pointer select-none">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full mb-2"></div>
                    <div class="w-full flex items-center justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <h2 id="model-title" class="text-base sm:text-lg font-bold text-gray-900 truncate">Memuat...</h2>
                            <p id="model-marker-badge" class="text-xs text-blue-600 font-semibold"></p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <!-- Audio Narasi Button in Sheet Header -->
                            <button id="btn-audio-toggle" type="button" aria-label="Toggle Audio Narasi"
                                    class="hidden flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-semibold border border-blue-200 transition active:scale-95 shadow-sm">
                                <i id="audio-icon-play" class="fas fa-volume-up text-xs"></i>
                                <i id="audio-icon-pause" class="fas fa-pause text-xs hidden"></i>
                                <span>Audio</span>
                            </button>
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i id="sheet-arrow" class="fas fa-chevron-up text-xs transition-transform duration-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expanded Content (Scrollable) -->
                <div class="flex-1 overflow-y-auto px-6 pb-12 pt-2 border-t border-gray-100">
                    <p id="model-desc-full" class="text-sm leading-relaxed text-gray-700 whitespace-pre-line"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 3. LOADING OVERLAY                         -->
    <!-- ========================================== -->
    <div id="loading-overlay" class="hidden absolute inset-0 z-50 flex flex-col items-center justify-center bg-black/85 backdrop-blur-md text-white px-6">
        <div class="relative w-16 h-16 mb-4 flex items-center justify-center">
            <div class="w-16 h-16 border-4 border-blue-500/20 border-t-blue-500 rounded-full animate-spin"></div>
            <i class="fas fa-cube text-blue-400 text-xl absolute"></i>
        </div>
        <p id="loading-text" class="text-base font-semibold tracking-wide text-center">Memuat Data Objek AR...</p>
        <div id="loading-progress-container" class="w-56 h-2 bg-white/10 rounded-full overflow-hidden mt-3 hidden">
            <div id="loading-progress-bar" class="h-full bg-blue-500 rounded-full transition-all duration-150" style="width: 0%"></div>
        </div>
        <p id="loading-subtext" class="text-xs text-gray-400 mt-2 font-mono hidden">0%</p>
    </div>

    <!-- ========================================== -->
    <!-- JAVASCRIPT LOGIC                           -->
    <!-- ========================================== -->
    <script>
        (function() {
            let html5QrCode = null;
            let isScanning = false;
            let currentTorchState = false;
            let isSheetExpanded = false;
            let currentMarkerObjects = [];
            let currentActiveObjectId = null;

            const initialMarker = "{{ $initialMarker ?? '' }}";
            const initialObject = "{{ $initialObject ?? '' }}";

            // Device Detection
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) ||
                          (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            const isInAppBrowser = isIOS && !window.navigator.standalone && !navigator.userAgent.match(/Safari/i);

            document.addEventListener('DOMContentLoaded', function() {
                initUI();

                if (initialObject) {
                    fetchModel({ id: initialObject, marker: initialMarker });
                } else if (initialMarker) {
                    fetchModel({ marker: initialMarker });
                } else {
                    startScanner();
                }
            });

            function initUI() {
                // In-App browser check on iOS
                if (isInAppBrowser) {
                    const iab = document.getElementById('iab-warning');
                    if (iab) {
                        iab.classList.remove('hidden');
                        iab.classList.add('flex');
                    }
                }

                // Bottom sheet toggle
                const sheetHeader = document.getElementById('sheet-header');
                const sheet = document.getElementById('desc-bottom-sheet');
                const arrow = document.getElementById('sheet-arrow');

                if (sheetHeader) {
                    sheetHeader.addEventListener('click', function(e) {
                        if (e.target.closest('#btn-audio-toggle')) return;
                        isSheetExpanded = !isSheetExpanded;
                        if (isSheetExpanded) {
                            sheet.style.transform = 'translateY(0)';
                            arrow.style.transform = 'rotate(180deg)';
                        } else {
                            sheet.style.transform = 'translateY(calc(100% - 86px))';
                            arrow.style.transform = 'rotate(0deg)';
                        }
                    });
                }

                // Scan Again Button
                const btnScanAgain = document.getElementById('btn-scan-again');
                const btnBackToScanner = document.getElementById('btn-back-to-scanner');
                if (btnScanAgain) btnScanAgain.addEventListener('click', showScanner);
                if (btnBackToScanner) btnBackToScanner.addEventListener('click', showScanner);

                // Manual Object Selector Toggle
                const btnToggleManual = document.getElementById('btn-toggle-manual');
                const manualContainer = document.getElementById('manual-picker-container');
                const selectManual = document.getElementById('select-manual-object');

                if (btnToggleManual && manualContainer) {
                    btnToggleManual.addEventListener('click', function() {
                        manualContainer.classList.toggle('hidden');
                    });
                }

                if (selectManual) {
                    selectManual.addEventListener('change', function() {
                        if (this.value) {
                            fetchModel({ id: this.value });
                        }
                    });
                }

                // Torch Toggle
                const btnTorch = document.getElementById('btn-torch');
                if (btnTorch) {
                    btnTorch.addEventListener('click', function() {
                        if (html5QrCode) {
                            currentTorchState = !currentTorchState;
                            html5QrCode.applyVideoConstraints({
                                advanced: [{ torch: currentTorchState }]
                            }).catch(err => console.warn('Torch unsupported:', err));
                        }
                    });
                }

                // Audio Narasi Toggle
                const btnAudio = document.getElementById('btn-audio-toggle');
                const audioPlayer = document.getElementById('ar-audio');
                const iconPlay = document.getElementById('audio-icon-play');
                const iconPause = document.getElementById('audio-icon-pause');

                if (btnAudio && audioPlayer) {
                    btnAudio.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (audioPlayer.paused) {
                            audioPlayer.play();
                            iconPlay.classList.add('hidden');
                            iconPause.classList.remove('hidden');
                        } else {
                            audioPlayer.pause();
                            iconPlay.classList.remove('hidden');
                            iconPause.classList.add('hidden');
                        }
                    });

                    audioPlayer.addEventListener('ended', function() {
                        iconPlay.classList.remove('hidden');
                        iconPause.classList.add('hidden');
                    });
                }

                // Model Viewer Load & Progress Events
                const viewer = document.getElementById('ar-model-viewer');
                if (viewer) {
                    viewer.addEventListener('progress', function(event) {
                        if (event.detail && typeof event.detail.totalProgress === 'number') {
                            updateLoadingProgress(event.detail.totalProgress);
                        }
                    });

                    viewer.addEventListener('load', function() {
                        hideLoading();
                    });

                    viewer.addEventListener('error', function(err) {
                        console.error('Error loading 3D model:', err);
                        hideLoading();
                    });
                }
            }

            // Start QR Scanner via html5-qrcode
            function startScanner() {
                const readerElement = document.getElementById('reader');
                if (!readerElement) return;

                html5QrCode = new Html5Qrcode("reader");

                const config = {
                    fps: 15,
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        return { width: Math.floor(minEdge * 0.72), height: Math.floor(minEdge * 0.72) };
                    },
                    aspectRatio: 1.0
                };

                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    onQrCodeSuccess,
                    onQrCodeFailure
                ).then(() => {
                    isScanning = true;
                    // Check torch capability
                    const btnTorch = document.getElementById('btn-torch');
                    const placeholder = document.getElementById('btn-torch-placeholder');
                    try {
                        const track = html5QrCode.getRunningTrackCameraCapabilities();
                        if (track && track.torchFeature().isSupported()) {
                            btnTorch.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        }
                    } catch (e) {}
                }).catch(err => {
                    console.error("Camera access error:", err);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Akses Kamera Dibutuhkan',
                        text: 'Izinkan akses kamera pada browser Anda untuk memindai QR Marker AR.',
                        confirmButtonColor: '#2D8BEB',
                        confirmButtonText: 'Coba Lagi'
                    }).then(() => {
                        location.reload();
                    });
                });
            }

            function stopScanner() {
                if (html5QrCode && isScanning) {
                    return html5QrCode.stop().then(() => {
                        isScanning = false;
                    }).catch(err => console.warn(err));
                }
                return Promise.resolve();
            }

            function onQrCodeSuccess(decodedText) {
                console.log("QR Terdeteksi:", decodedText);
                stopScanner();
                fetchModel(decodedText);
            }

            function onQrCodeFailure(error) {
                // Silently loop scanning
            }

            // Fetch Model Data from API
            function fetchModel(target) {
                showLoading('Memvalidasi QR Marker...', false);

                const params = new URLSearchParams();
                if (typeof target === 'object' && target !== null) {
                    if (target.id) params.append('id', target.id);
                    if (target.marker) params.append('marker', target.marker);
                } else {
                    const text = String(target || '').trim();
                    const objMatch = text.match(/[?&]object=(\d+)/i);
                    if (objMatch) {
                        params.append('id', objMatch[1]);
                    }
                    params.append('marker', text);
                }

                fetch('/api/ar/model?' + params.toString(), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Objek 3D tidak ditemukan untuk QR ini.');
                    return res.json();
                })
                .then(data => {
                    if (data.success && data.model_url) {
                        showLoading('Mengunduh Model 3D...', true);
                        displayModel(data);
                    } else {
                        hideLoading();
                        throw new Error(data.error || 'Model tidak tersedia.');
                    }
                })
                .catch(err => {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Objek Tidak Ditemukan',
                        text: err.message,
                        confirmButtonColor: '#2D8BEB',
                        confirmButtonText: 'Pindai Ulang'
                    }).then(() => {
                        showScanner();
                    });
                });
            }

            // Render 3D Model in Google <model-viewer>
            function displayModel(data) {
                const scannerView = document.getElementById('scanner-view');
                const modelView = document.getElementById('model-view');

                if (scannerView) scannerView.classList.add('hidden');
                if (modelView) modelView.classList.remove('hidden');

                currentMarkerObjects = (data.marker_objects && data.marker_objects.length > 0)
                    ? data.marker_objects
                    : [data];

                loadObjectIntoViewer(data);
                renderObjectSwitcher(currentMarkerObjects, data.id);
            }

            function loadObjectIntoViewer(data) {
                currentActiveObjectId = data.id;
                const viewer = document.getElementById('ar-model-viewer');

                // Set 3D sources
                const currentSrc = viewer.getAttribute('src');
                if (currentSrc === data.model_url) {
                    hideLoading();
                } else {
                    viewer.setAttribute('src', data.model_url);
                }

                const arButton = document.getElementById('custom-ar-button');
                const arButtonText = document.getElementById('ar-button-text');

                if (data.usdz_url) {
                    // Absolute URL required for iOS Quick Look
                    const absoluteUsdz = new URL(data.usdz_url, window.location.href).href;
                    viewer.setAttribute('ios-src', absoluteUsdz);
                    if (arButton) arButton.classList.remove('hidden');
                    if (arButtonText) arButtonText.innerText = isIOS ? 'Lihat di Apple AR (Quick Look)' : 'Tampilkan di Ruang Nyata (AR)';
                } else {
                    viewer.removeAttribute('ios-src');
                    if (isIOS) {
                        // On iOS without USDZ, native Quick Look cannot run
                        if (arButtonText) arButtonText.innerText = 'Preview 3D (USDZ Tidak Tersedia)';
                    } else {
                        if (arButton) arButton.classList.remove('hidden');
                        if (arButtonText) arButtonText.innerText = 'Tampilkan di Ruang Nyata (AR)';
                    }
                }

                // Update text details
                const titleEl = document.getElementById('model-title');
                const badgeEl = document.getElementById('model-marker-badge');
                const descEl = document.getElementById('model-desc-full');

                if (titleEl) titleEl.innerText = data.name || 'Objek AR';

                const totalObj = currentMarkerObjects.length;
                const currentIndex = currentMarkerObjects.findIndex(o => o.id === data.id);
                let badgeText = data.marker_id ? 'Marker #' + data.marker_id : '';
                if (totalObj > 1 && currentIndex !== -1) {
                    badgeText += (badgeText ? ' • ' : '') + `Objek ${currentIndex + 1} dari ${totalObj}`;
                }
                if (badgeEl) badgeEl.innerText = badgeText;
                if (descEl) descEl.innerText = data.description || 'Tidak ada deskripsi detail untuk objek ini.';

                // Reset Bottom Sheet to collapsed state
                const sheet = document.getElementById('desc-bottom-sheet');
                const arrow = document.getElementById('sheet-arrow');
                if (sheet) sheet.style.transform = 'translateY(calc(100% - 86px))';
                if (arrow) arrow.style.transform = 'rotate(0deg)';
                isSheetExpanded = false;

                // Setup Audio Narasi (Stop previous, prepare new)
                const btnAudio = document.getElementById('btn-audio-toggle');
                const audioPlayer = document.getElementById('ar-audio');
                const iconPlay = document.getElementById('audio-icon-play');
                const iconPause = document.getElementById('audio-icon-pause');

                if (btnAudio && audioPlayer) {
                    audioPlayer.pause();
                    audioPlayer.currentTime = 0;
                    if (data.audio_url) {
                        audioPlayer.src = data.audio_url;
                        btnAudio.classList.remove('hidden');
                        iconPlay.classList.remove('hidden');
                        iconPause.classList.add('hidden');
                    } else {
                        audioPlayer.src = '';
                        btnAudio.classList.add('hidden');
                    }
                }

                // Update URL parameter dynamically via replaceState
                if (window.history && window.history.replaceState) {
                    const newUrl = new URL(window.location.href);
                    if (data.id) newUrl.searchParams.set('object', data.id);
                    if (data.marker_id) newUrl.searchParams.set('marker', data.marker_id);
                    window.history.replaceState(null, '', newUrl.toString());
                }
            }

            function renderObjectSwitcher(objectsList, activeId) {
                const switcher = document.getElementById('multi-object-switcher');
                const container = document.getElementById('switcher-items-container');
                if (!switcher || !container) return;

                if (!objectsList || objectsList.length <= 1) {
                    switcher.classList.add('hidden');
                    container.innerHTML = '';
                    return;
                }

                switcher.classList.remove('hidden');
                container.innerHTML = '';

                objectsList.forEach((obj, idx) => {
                    const isActive = (obj.id === activeId);
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.setAttribute('aria-label', escapeHtml(obj.name));
                    btn.className = `group relative shrink-0 rounded-2xl p-0.5 transition-all duration-200 select-none ${
                        isActive
                            ? 'scale-105'
                            : 'opacity-65 hover:opacity-100 scale-95'
                    }`;

                    const visualBox = obj.gambar_real
                        ? `<img src="${obj.gambar_real}" alt="${escapeHtml(obj.name)}" class="w-12 h-12 rounded-xl object-cover">`
                        : `<div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gray-800 text-blue-400"><i class="fas fa-cube text-sm"></i></div>`;

                    btn.innerHTML = `
                        <div class="relative w-12 h-12 rounded-xl overflow-hidden transition-all duration-200 ${
                            isActive
                                ? 'ring-2 ring-blue-500 ring-offset-2 ring-offset-[#0b0f19] shadow-lg shadow-blue-500/50'
                                : 'border border-white/20'
                        }">
                            ${visualBox}
                            <span class="absolute bottom-0 right-0 px-1.5 py-0.5 rounded-tl-md text-[10px] font-bold leading-none ${
                                isActive ? 'bg-blue-600 text-white' : 'bg-black/80 text-gray-200'
                            }">${idx + 1}</span>
                        </div>
                    `;

                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (obj.id !== currentActiveObjectId) {
                            showLoading('Mengunduh Objek 3D...', true);
                            loadObjectIntoViewer(obj);
                            renderObjectSwitcher(currentMarkerObjects, obj.id);
                        }
                    });

                    container.appendChild(btn);
                });
            }

            function escapeHtml(text) {
                if (!text) return '';
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }

            function showScanner() {
                const scannerView = document.getElementById('scanner-view');
                const modelView = document.getElementById('model-view');
                const viewer = document.getElementById('ar-model-viewer');
                const audioPlayer = document.getElementById('ar-audio');
                const switcher = document.getElementById('multi-object-switcher');

                hideLoading();
                if (audioPlayer) audioPlayer.pause();
                if (viewer) {
                    viewer.removeAttribute('src');
                    viewer.removeAttribute('ios-src');
                }
                currentActiveObjectId = null;
                if (switcher) switcher.classList.add('hidden');

                if (modelView) modelView.classList.add('hidden');
                if (scannerView) scannerView.classList.remove('hidden');

                startScanner();
            }

            let loadingTimeout = null;

            function showLoading(text, showProgress = false) {
                const overlay = document.getElementById('loading-overlay');
                const loadingText = document.getElementById('loading-text');
                const progressContainer = document.getElementById('loading-progress-container');
                const progressBar = document.getElementById('loading-progress-bar');
                const subtext = document.getElementById('loading-subtext');

                if (loadingTimeout) clearTimeout(loadingTimeout);
                // Safety fallback: auto hide after 25s if network or model fails silently
                loadingTimeout = setTimeout(() => {
                    hideLoading();
                }, 25000);

                if (loadingText) loadingText.innerText = text || 'Memuat Objek AR...';
                if (progressContainer) {
                    if (showProgress) {
                        progressContainer.classList.remove('hidden');
                        if (progressBar) progressBar.style.width = '0%';
                        if (subtext) {
                            subtext.innerText = '0%';
                            subtext.classList.remove('hidden');
                        }
                    } else {
                        progressContainer.classList.add('hidden');
                        if (subtext) subtext.classList.add('hidden');
                    }
                }
                if (overlay) overlay.classList.remove('hidden');
            }

            function updateLoadingProgress(progressRatio) {
                const progressBar = document.getElementById('loading-progress-bar');
                const subtext = document.getElementById('loading-subtext');
                const progressContainer = document.getElementById('loading-progress-container');
                const percent = Math.min(100, Math.max(0, Math.round(progressRatio * 100)));

                if (progressContainer) progressContainer.classList.remove('hidden');
                if (progressBar) progressBar.style.width = percent + '%';
                if (subtext) {
                    subtext.innerText = percent + '%';
                    subtext.classList.remove('hidden');
                }
            }

            function hideLoading() {
                if (loadingTimeout) {
                    clearTimeout(loadingTimeout);
                    loadingTimeout = null;
                }
                const overlay = document.getElementById('loading-overlay');
                if (overlay) overlay.classList.add('hidden');
            }
        })();
    </script>
</body>

</html>
