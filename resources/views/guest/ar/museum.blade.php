<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>AR</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">

    <script type="importmap">
        {
          "imports": {
            "three": "https://cdn.jsdelivr.net/npm/three@v0.153.0/build/three.module.js",
            "three/jsm/": "https://cdn.jsdelivr.net/npm/three@v0.153.0/examples/jsm/"
          }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>

    <script src="https://launchar.app/sdk/v1?key=RDgojZ30201nvtK5SuFFTIUNDDLpxiVj&redirect=true"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('css')

    <style>
        /* Ensure canvas is full screen */
        canvas {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 1 !important;
        }

        /* Make sure body and html don't have padding/margin that affects canvas */
        body,
        html {
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            /* Prevent touch actions that could trigger refresh */
            touch-action: none !important;
            -webkit-touch-callout: none !important;
            -webkit-user-select: none !important;
            -khtml-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        /* Specific for iOS Safari/WebView */
        @supports (-webkit-touch-callout: none) {
            * {
                -webkit-touch-callout: none !important;
                -webkit-tap-highlight-color: transparent !important;
            }
        }

        .toaster {
            background-color: black;
            color: white;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 0.25rem;
            position: relative;
            animation: slide-in 0.2s forwards, slide-out 0.2s 2.5s forwards;
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slide-out {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>
    <style>
        #ar-button-container #ARButton {
            position: absolute !important;
            left: 50% !important;
            bottom: 20px !important;
            transform: translateX(-50%) !important;
            width: 200px !important;
            padding: 16px 8px !important;
            border: 2px solid #fff !important;
            border-radius: 9999px !important;
            background: #2563eb !important;
            /* bg-primary (Tailwind blue-600) */
            color: #fff !important;
            font: 600 15px 'Inter', 'sans-serif' !important;
            text-align: center !important;
            opacity: 0.95 !important;
            outline: none !important;
            z-index: 10000001 !important;
            cursor: pointer !important;
            box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.12) !important;
            transition: opacity 0.2s, background 0.2s, border 0.2s !important;
        }

        #ar-button-container #ARButton:hover {
            opacity: 1 !important;
            background: #1d4ed8 !important;
            /* biru lebih gelap saat hover */
            border-width: 3px !important;
        }


        @keyframes circle {
            from {
                transform: translateX(-50%) rotate(0deg) translateX(50px) rotate(0deg);
            }

            to {
                transform: translateX(-50%) rotate(360deg) translateX(50px) rotate(-360deg);
            }
        }

        @keyframes elongate {
            from {
                transform: translateX(100px);
            }

            to {
                transform: translateX(-100px);
            }
        }

        #tracking-prompt {
            position: absolute;
            left: 50%;
            bottom: 175px;
            animation: elongate 2s infinite ease-in-out alternate;
            display: none;
        }

        #tracking-prompt>img {
            animation: circle 4s linear infinite;
        }
    </style>
</head>

<body>
    <script>
        var popupVisible = false;
        var arToken = '{{ $arToken }}';
    </script>
    <div class="pointer-events-auto z-[10000000] rounded-b-3xl bg-primary px-6 py-6 text-white shadow-md">
        <div class="flex items-center space-x-4">
            <a href="{{ route('guest.situs.detail', $situs->situs_id) }}" class="rounded-full p-2 transition-colors hover:bg-white/10" title="{{ __('ar.back_to_situs') }}">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex-1">
                <h1 class="text-lg font-bold">{{ config('app.name') }} AR</h1>
                <p class="text-sm opacity-90">{{ $museum->nama ?? __('ar.museum_explore') }}</p>
            </div>
        </div>
    </div>
    <div id="overlay"
        class="pointer-events-none absolute inset-0 z-[999999]">
        <div id="instructions"
            class="invisible pointer-events-none absolute top-4 inset-x-4 mx-auto max-w-sm rounded-xl bg-black/70 backdrop-blur-md px-4 py-3 text-center text-sm font-medium text-white shadow-lg">
            {{ __('ar.floor_instruction') }}
        </div>
        <div id="tracking-prompt" class="invisible pointer-events-none absolute bottom-28 left-1/2 -translate-x-1/2 flex flex-col items-center">
            <img src="{{ asset('images/icons/hand.png') }}" class="w-20" style="animation: circle 4s linear infinite;"
                alt="" />
            <p class="mt-2 text-xs font-semibold text-white/90 bg-black/40 px-3 py-1 rounded-full">{{ __('ar.move_phone_slowly') }}</p>
        </div>
        <div id="toaster-container" class="fixed bottom-0 right-0 z-[99999] m-4"></div>

        <!-- Tombol Buka Objek Peninggalan (Didalam DOM Overlay WebXR) -->
        <button id="expand-bottom-sheet"
            type="button"
            class="pointer-events-auto fixed bottom-6 left-1/2 z-[10000000] -translate-x-1/2 hidden items-center gap-2 rounded-full border-2 border-white bg-primary px-6 py-3 font-bold text-white shadow-2xl active:scale-95 transition-all">
            <i class="fas fa-landmark"></i>
            <span>{{ __('ar.view_heritage_objects') }}</span>
        </button>

        <!-- Bottom Sheet Objek Peninggalan -->
        <div id="bottom-sheet"
            class="pointer-events-auto fixed inset-x-0 bottom-0 z-[10000001] max-h-[85vh] translate-y-full transform rounded-t-3xl bg-white shadow-2xl transition-transform duration-300 flex flex-col">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="h-2 w-2 rounded-full bg-primary"></div>
                    <h3 class="text-base font-bold text-gray-900">{{ __('ar.objects_in_site') }} ({{ $museum->virtualMuseumObjects->count() }})</h3>
                </div>
                <button id="close-bottom-sheet" type="button" class="rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div id="bottom-sheet-content"
                class="overflow-y-auto p-6 space-y-6">
                @forelse ($museum->virtualMuseumObjects as $i => $object)
                    <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-4 shadow-sm text-center">
                        @if ($object->gambar_real)
                            <img class="mx-auto w-64 max-w-full rounded-xl shadow-md object-cover max-h-56"
                                src="{{ asset('/storage/' . $object->gambar_real) }}" alt="{{ $object->nama }}">
                        @endif
                        <h4 class="mt-3 font-bold text-gray-900 text-base">{{ $object->nama }}</h4>
                        @if ($object->deskripsi)
                            <p class="mt-1 text-sm text-gray-600 leading-relaxed text-left">{{ $object->deskripsi }}</p>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-2 text-gray-300"></i>
                        <p class="text-sm">{{ __('ar.no_objects_in_spot') }}</p>
                    </div>
                @endforelse
                <div class="pb-6"></div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const expandBtn = document.getElementById('expand-bottom-sheet');
            const closeBtn = document.getElementById('close-bottom-sheet');
            const bottomSheet = document.getElementById('bottom-sheet');

            if (expandBtn && bottomSheet) {
                expandBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    bottomSheet.classList.remove('translate-y-full');
                    bottomSheet.classList.add('translate-y-0');
                    expandBtn.classList.add('opacity-0', 'pointer-events-none');
                });
            }

            if (closeBtn && bottomSheet) {
                closeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    bottomSheet.classList.add('translate-y-full');
                    bottomSheet.classList.remove('translate-y-0');
                    if (expandBtn) {
                        expandBtn.classList.remove('opacity-0', 'pointer-events-none');
                    }
                });
            }
        });
    </script>
    <div id="app" class="relative z-[15] mx-auto flex min-h-[calc(100vh-100px)] w-full max-w-xl flex-col items-center justify-start px-4 py-8 text-center overflow-y-auto">
        <div class="mb-2 flex justify-center">
            <x-application-logo class="h-12 w-12" />
        </div>
        <h2 class="text-xl md:text-2xl font-bold text-primary">{{ config('app.name') }}</h2>
        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $museum->nama }}</p>
        <p class="text-xs text-gray-500">{{ $situs->nama }}</p>

        <!-- Pesan WebXR Tidak Didukung & QR Code -->
        <div id="ar-not-supported" class="hidden mt-6 w-full rounded-2xl bg-white p-5 md:p-6 shadow-xl border border-gray-100 text-center pointer-events-auto">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <i class="fas fa-vr-cardboard text-xl"></i>
            </div>
            <h3 class="text-base md:text-lg font-bold text-gray-900">{{ __('ar.webxr_not_supported_title') }}</h3>
            <p class="mt-2 text-xs md:text-sm text-gray-600 leading-relaxed">{{ __('ar.webxr_not_supported_desc') }}</p>

            <div class="mt-5 flex flex-col items-center justify-center rounded-xl bg-gray-50 border border-gray-200/80 p-4">
                <p class="mb-3 text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                    <i class="fas fa-qrcode text-primary"></i>
                    <span>{{ __('ar.webxr_scan_qr') }}</span>
                </p>
                <div id="qr-code" class="flex items-center justify-center min-h-[160px] min-w-[160px] bg-white p-2.5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex flex-col items-center gap-2 text-gray-400 py-6">
                        <i class="fas fa-circle-notch fa-spin text-2xl text-primary"></i>
                        <span class="text-xs">{{ __('ar.loading_marker') }}</span>
                    </div>
                </div>
                <p class="mt-3 text-[11px] text-gray-500 max-w-xs">{{ __('ar.webxr_scan_qr_desc') }}</p>
            </div>

            <div class="mt-4 flex w-full flex-col gap-2">
                <div class="flex items-center gap-2">
                    <input id="ar-share-url" type="text" readonly value="{{ url()->full() }}"
                        class="w-full flex-1 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-600 select-all focus:outline-none" />
                    <button id="btn-copy-ar-link" type="button"
                        class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-primary px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-primary-hover active:scale-95">
                        <i class="fas fa-copy"></i>
                        <span id="copy-btn-label">{{ __('ar.copy_link') }}</span>
                    </button>
                </div>
                <a href="{{ route('guest.situs.detail', $situs->situs_id) }}"
                    class="mt-2 inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('ar.back_to_situs') }}</span>
                </a>
            </div>
        </div>

        <!-- Loading Model Bar (Hanya saat WebXR didukung dan sedang load 3D model) -->
        <div id="loading-container" class="w-full max-w-md my-8">
            <div class="flex justify-between items-center mb-1.5 text-xs text-gray-600 font-medium">
                <span>{{ __('ar.loading_model') }}</span>
                <span id="loading-percentage">0%</span>
            </div>
            <div class="h-2.5 w-full rounded-full bg-gray-200 overflow-hidden shadow-inner">
                <div id="loading-bar" class="h-full rounded-full bg-primary transition-all duration-200" style="width: 0%"></div>
            </div>
        </div>
    </div>
    <div id="ar-button-container"></div>

    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script>
        var museum = @json($museum);
        console.log(museum);
    </script>

    <script src="{{ asset('assets/js/ar-museum.js') }}?v={{ filemtime(public_path('assets/js/ar-museum.js')) }}" type="module"></script>
    </div>
</body>

</html>
