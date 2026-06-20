<!-- Loading Screen -->
<div id="loading-overlay">
    <div class="spinner"></div>
    <h2 class="text-xl font-bold">Memuat Tur Virtual...</h2>
    <p class="mt-2 text-sm text-gray-400" id="loading-text">Menyiapkan panorama</p>
</div>

<!-- UI Overlay -->
<div id="ui-layer">
    <div id="header-bar">
        <a href="{{ route('guest.situs.detail', $situs->situs_id) }}" class="btn-circle interactive"
            title="Kembali ke Detail Situs">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="tour-title-box">
            <h1 class="m-0 text-sm font-bold">{{ $situs->nama }}</h1>
            <p class="m-0 text-xs opacity-75" id="current-scene-name">Memuat...</p>
        </div>

        <button class="btn-circle interactive" id="btn-fullscreen" title="Layar Penuh">
            <i class="fas fa-expand"></i>
        </button>
    </div>

    <!-- Bottom Controls -->
    <div class="interactive flex justify-center">
        <div class="flex gap-4 rounded-full border border-white/20 bg-black/50 px-6 py-2 text-white backdrop-blur-md">
            <button id="btn-gyro" class="p-2 transition-colors hover:text-cyan-400" title="Sensor Gyroscope (Mobile)">
                <i class="fas fa-mobile-screen"></i>
            </button>
            <button id="btn-vr-custom" class="p-2 transition-colors hover:text-cyan-400" title="Mode VR (Google Cardboard)">
                <i class="fas fa-vr-cardboard"></i>
            </button>
            <div id="divider-controls" class="my-2 w-px bg-white/20"></div>
            <button class="cursor-help p-2 transition-colors hover:text-cyan-400"
                title="Geser untuk melihat sekeliling. Klik ikon untuk berinteraksi.">
                <i class="fas fa-info-circle"></i>
            </button>
        </div>
    </div>
</div>

<!-- Info Modal -->
<div id="info-modal" class="interactive">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="m-0 text-lg font-bold text-gray-900" id="modal-title">Info</h3>
            <button id="btn-close-modal" class="p-1 text-gray-400 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="modal-body text-gray-700">
            <img id="modal-image" src="" alt="" class="modal-img">
            <div id="modal-content" class="prose prose-sm max-w-none"></div>
        </div>
    </div>
</div>

