<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}"/>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>AR</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
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

    <script src="https://launchar.app/sdk/v1?key=5aBe43oIyUoBC3PyhermEi3oqqswm07z&redirect=true"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap"
          rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('css')

    <style>
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
            background: #2563eb !important; /* bg-primary (Tailwind blue-600) */
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
            background: #1d4ed8 !important; /* biru lebih gelap saat hover */
            border-width: 3px !important;
        }
    </style>
</head>

<body>
<script>
    var popupVisible = false;
</script>
<div class="px-6 py-6 bg-primary text-white rounded-b-3xl pointer-events-auto">
    <div class="flex items-center space-x-4">
        <a href="{{ route('guest.home') }}" class="p-2 hover:bg-white/10 rounded-full transition-colors">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-lg font-bold">Virtual Living Museum AR</h1>
            <p class="text-sm opacity-90">Eksplorasi Objek Peninggalan</p>
        </div>
    </div>
</div>
<div id="overlay" class="absolute inset-0 z-[999999] flex flex-col justify-center items-center pointer-events-none">
    <div id="instructions"
         class="z-[100000] w-full text-center hidden absolute top-0 p-4 bg-primary text-white rounded-t-xl">Tekan
        lingkaran untuk memunculkan ruangan
    </div>
    {{-- <audio id="audio-portal">
        @if (session()->has('locale') && session('locale') == 'id')
            <source src="{{ asset('assets/music/indonesia-wayang-kamasan-gallery.mp3') }}" type="audio/mpeg">
        @else
            <source src="{{ asset('assets/music/english-wayang-kamasan-gallery.mp3') }}" type="audio/mpeg">
        @endif
    </audio> --}}
    <div id="tracking-prompt" class="absolute left-1/2 bottom-[44] hidden animate-pulse">
        <img src="{{ asset('images/icons/hand.png') }}" class="w-24" style="animation: circle 4s linear infinite;" alt=""/>
    </div>
    <div id="toaster-container" class="fixed bottom-0 right-0 m-4 z-[99999]"></div>
    <div id="bottom-sheet"
         class="fixed bottom-0 left-0 right-0 bg-white shadow-lg rounded-t-2xl transform translate-y-full transition-transform duration-300 h-16 z-[10000000] pointer-events-auto">
        <div class="p-4 border-b">
            <h3 class="text-lg font-bold text-primary pointer-events-auto">Objek Peninggalan</h3>
        </div>
        <div id="bottom-sheet-content"
             class="hidden p-4 overflow-y-scroll h-[80vh] text-gray-800 text-center pointer-events-auto">
            <ul id="lukisan-list" class="py-2 flex flex-col gap-6">
                @foreach ($museum->virtualMuseumObjects as $i => $object)
                    <li>
                        <img class="w-60 max-w-full mx-auto rounded-xl shadow"
                             src="{{ asset('/storage/' . $object->gambar_real) }}" alt="">
                        <p class="mt-2">{{ $object->deskripsi }}</p>
                    </li>
                @endforeach
                <li>
                    <div class="pt-8"></div>
                </li>
            </ul>
            <button id="close-bottom-sheet" class="absolute top-2 right-2 text-gray-600 text-2xl">&times;</button>
        </div>
    </div>
</div>
<div>
    <button id="expand-bottom-sheet"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-primary text-white font-bold px-6 py-3 rounded-full border-2 border-white shadow-lg z-[90000]"
            style="display: none;">
        Lihat Objek Peninggalan
    </button>
    <script>
        document.getElementById('expand-bottom-sheet').addEventListener('click', function () {
            document.getElementById('bottom-sheet').style.transform = 'translateY(0)';
            document.getElementById('bottom-sheet').style.height = '80vh';
            document.getElementById('bottom-sheet-content').style.display = 'block';
        });
        document.getElementById('close-bottom-sheet').addEventListener('click', function () {
            document.getElementById('bottom-sheet').style.transform = 'translateY(100%)';
            document.getElementById('bottom-sheet').style.height = '16px';
            document.getElementById('bottom-sheet-content').style.display = 'none';
        });
    </script>
</div>
<div id="app">
    <div
        class="flex flex-col items-center justify-center max-w-5xl min-w-[320px] min-h-[80vh] p-8 gap-4 mx-auto text-center absolute inset-0 z-[15] pointer-events-none">
        <div class="flex justify-center mb-2">
            <x-application-logo class="w-12 h-12"/>
        </div>
        <h2 class="text-2xl font-bold text-primary mb-2 text-center">Virtual Living Museum</h2>
        <div id="ar-not-supported" class="w-full bg-white rounded-xl shadow p-6 mt-4">
            <p class="text-red-600 font-semibold">Teknologi WebXR tidak didukung di perangkat Anda.</p>
            <p class="text-gray-600">Untuk dokumentasi selengkapnya, kunjungi <a
                    href="https://launch.variant3d.com/docs" class="underline">https://launch.variant3d.com</a>.</p>
            <div class="flex flex-col justify-center items-center mt-4">
                <div id="qr-code"
                     class="p-4 bg-white mx-auto text-black flex flex-col items-center gap-3 rounded-lg shadow">
                    <span class="sr-only">Loading...</span>
                    <p>Memuat QR</p>
                </div>
            </div>
        </div>
        <div id="loading-container" class="w-4/5 bg-gray-300 h-2 rounded-full max-w-xl mx-auto my-8">
            <div id="loading-bar" class="h-full bg-orange-400 rounded-full transition-all duration-200"
                 style="width:0%"></div>
        </div>
    </div>
    <div id="ar-button-container"></div>

    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script>
        var museum = @json($museum);
        console.log(museum);
    </script>

    {{-- AR Museum Implementation --}}
    <script type="module" src="{{ asset('js/ar-museum.js') }}"></script>
</div>
</body>

</html>
