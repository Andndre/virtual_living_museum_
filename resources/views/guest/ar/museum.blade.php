<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AR Museum - {{ $museum->nama }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script type="importmap">
        {
          "imports": {
            "three": "https://cdn.jsdelivr.net/npm/three@v0.153.0/build/three.module.js",
            "three/jsm/": "https://cdn.jsdelivr.net/npm/three@v0.153.0/examples/jsm/"
          }
        }
    </script>
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes pulse-reticle {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0.7;
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }
        
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        
        .pulse-reticle {
            animation: pulse-reticle 1.5s infinite;
        }
        
        #object-selector {
            transition: bottom 0.3s ease;
        }
        
        .show-object-selector {
            bottom: 0 !important;
        }
    </style>
</head>
<<body class="m-0 p-0 bg-black text-white font-sans overflow-hidden">
    {{-- Loading Screen --}}
    <div id="loading-screen" class="fixed inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 flex flex-col justify-center items-center z-50">
        <div class="loading-spinner w-12 h-12 border-4 border-white border-opacity-30 border-t-white rounded-full mb-5"></div>
        <h2 class="text-2xl font-bold">Memuat Pengalaman AR</h2>
        <p class="text-lg">{{ $museum->nama }}</p>
        <p class="text-sm opacity-80">Pastikan Anda berada di tempat dengan pencahayaan cukup</p>
    </div>

    {{-- AR Container --}}
    <div id="ar-container" class="relative w-screen h-screen hidden">
        <canvas id="ar-canvas" class="w-full h-full"></canvas>
        
        {{-- AR UI Overlays --}}
        <div id="ar-ui" class="absolute inset-0 pointer-events-none z-20">
            {{-- Back Button --}}
            <button id="back-button" class="absolute top-5 left-5 bg-black bg-opacity-70 border-2 border-white text-white px-4 py-3 rounded-full text-base cursor-pointer transition-all duration-300 hover:bg-white hover:bg-opacity-20 hover:scale-105 pointer-events-auto">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>

            {{-- Status Text --}}
            <div id="status-text" class="absolute bottom-24 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-80 py-3 px-5 rounded-full text-center backdrop-blur-md pointer-events-auto">
                <p id="status-message" class="m-0">Memuat AR...</p>
            </div>
            
            {{-- Object Toggle Button --}}
            @if($museum->virtualMuseumObjects->count() > 0)
            <button id="object-toggle-button" class="absolute bottom-5 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-80 border-2 border-green-500 text-white py-3 px-6 rounded-full text-base cursor-pointer transition-all duration-300 backdrop-blur-md hover:bg-green-500 hover:bg-opacity-30 hover:scale-105 pointer-events-auto">
                <i class="fas fa-list mr-2"></i>
                Objek Peninggalan ({{ $museum->virtualMuseumObjects->count() }})
            </button>
            @endif
            
            {{-- Reticle for ground detection --}}
            <div id="reticle" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-5 h-5 border-2 border-green-500 rounded-full bg-green-500 bg-opacity-30 hidden z-10 pulse-reticle"></div>
        </div>
    </div>

    {{-- Error Message Container --}}
    <div id="error-container" class="hidden">
        <div class="bg-red-500 bg-opacity-90 text-white p-5 rounded-lg m-5 text-center">
            <h3 class="text-lg font-bold mb-2">AR Tidak Didukung</h3>
            <p id="error-text" class="mb-3">Browser atau perangkat Anda tidak mendukung WebXR.</p>
            <button onclick="goBack()" class="bg-white text-red-500 border-none py-2 px-5 rounded cursor-pointer">
                Kembali ke Halaman Sebelumnya
            </button>
        </div>
    </div>

    {{-- Object Selector Panel --}}
    @if($museum->virtualMuseumObjects->count() > 0)
    <div id="object-selector" class="fixed -bottom-full left-0 right-0 bg-white bg-opacity-95 rounded-t-3xl p-5 backdrop-blur-2xl max-h-96 overflow-y-auto z-50 text-gray-800">
        <div class="flex justify-between items-center mb-4 pb-3 border-b-2 border-gray-200">
            <h3 class="m-0 text-gray-800 text-xl font-bold">Objek Peninggalan</h3>
            <button class="bg-red-500 text-white border-none rounded-full w-8 h-8 cursor-pointer flex items-center justify-center text-sm" onclick="closeObjectSelector()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="object-list">
            @foreach($museum->virtualMuseumObjects as $object)
            <div class="flex items-start p-4 my-3 border-2 border-gray-200 rounded-2xl bg-white shadow-lg transition-all duration-300 hover:border-green-500 hover:shadow-green-200 hover:shadow-lg hover:-translate-y-1">
                @if($object->thumbnail_path)
                    <img src="{{ asset('storage/' . $object->thumbnail_path) }}" alt="{{ $object->nama_objek }}" class="w-20 h-20 rounded-lg mr-4 object-cover border-2 border-gray-300">
                @else
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg mr-4 flex items-center justify-center border-2 border-gray-300">
                        <i class="fas fa-image text-white text-2xl"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <h4 class="m-0 mb-2 text-base text-gray-800 font-bold">{{ $object->nama_objek }}</h4>
                    @if($object->periode)
                        <div class="mb-2 text-xs text-gray-600 bg-gray-100 py-1 px-2 rounded-lg inline-block">{{ $object->periode }}</div>
                    @endif
                    @if($object->deskripsi)
                        <p class="m-0 text-sm text-gray-600 leading-normal">{{ $object->deskripsi }}</p>
                    @else
                        <p class="m-0 text-sm text-gray-400 italic">Deskripsi tidak tersedia</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Three.js WebXR Implementation --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    {{-- Museum data for JavaScript --}}
    <script>
        window.museumData = {
            museum_id: {{ $museum->museum_id }},
            museum_name: '{{ $museum->nama }}',
            situs_name: '{{ $situs->nama }}',
            object_count: {{ $museum->virtualMuseumObjects->count() }}
        };
    </script>

    {{-- AR Museum Implementation --}}
    <script type="module" src="{{ asset('js/ar-museum.js') }}"></script>
</body>
</html>
