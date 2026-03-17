<!DOCTYPE html>

<html>

<head>
    <title>AR</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <script src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.111.0/examples/js/loaders/DRACOLoader.js"></script>
    <script src="/js/gesture-detector.js"></script>
    <script src="/js/gesture-handler.js"></script>

    <style>
        #ar-description {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.4;
            max-height: 150px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        #ar-description h4 {
            margin: 0 0 10px 0;
            color: #fff;
            font-size: 18px;
        }

        #ar-description p {
            margin: 0;
            color: #ccc;
        }

        #ar-object-nav {
            position: absolute;
            left: 20px;
            right: 20px;
            bottom: 188px;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        #ar-object-nav.is-visible {
            display: flex;
        }

        .ar-nav-button {
            width: 46px;
            height: 46px;
            border: 0;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.78);
            color: #fff;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
        }

        .ar-nav-button:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        #ar-object-counter {
            flex: 1;
            text-align: center;
            padding: 10px 12px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.75);
            color: #e5e7eb;
            font-family: Arial, sans-serif;
            font-size: 13px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
        }

        #ar-loading {
            position: absolute;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1100;
            background: rgba(0, 0, 0, 0.55);
            padding: 24px;
        }

        #ar-loading.is-visible {
            display: flex;
        }

        .ar-loading-card {
            width: min(320px, 100%);
            background: rgba(15, 15, 15, 0.92);
            color: #fff;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.35);
            font-family: Arial, sans-serif;
        }

        .ar-loading-spinner {
            width: 44px;
            height: 44px;
            margin: 0 auto 14px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top-color: #fff;
            border-radius: 999px;
            animation: ar-spin 0.8s linear infinite;
        }

        .ar-loading-card h4 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .ar-loading-card p {
            margin: 0;
            color: #d1d5db;
            line-height: 1.5;
        }

        .ar-loading-progress-wrap {
            margin-top: 14px;
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            overflow: hidden;
        }

        .ar-loading-progress-bar {
            height: 100%;
            width: 0%;
            border-radius: 999px;
            background: linear-gradient(90deg, #22d3ee, #3b82f6);
            transition: width 180ms ease;
        }

        .ar-loading-progress-text {
            margin-top: 8px;
            font-size: 12px;
            color: #9ca3af;
        }

        .ar-loading-card.is-error {
            border: 1px solid rgba(239, 68, 68, 0.55);
            box-shadow: 0 10px 35px rgba(127, 29, 29, 0.35);
        }

        .ar-loading-card.is-error .ar-loading-progress-bar {
            background: linear-gradient(90deg, #ef4444, #dc2626);
            width: 100%;
        }

        #ar-debug-toggle {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 1200;
            border: 0;
            border-radius: 999px;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.78);
            color: #f9fafb;
            font-family: Arial, sans-serif;
            font-size: 12px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
        }

        #ar-debug-panel {
            position: absolute;
            top: 56px;
            left: 12px;
            right: 12px;
            z-index: 1200;
            display: none;
            max-height: 36vh;
            overflow-y: auto;
            background: rgba(8, 8, 8, 0.88);
            color: #d1d5db;
            border: 1px solid rgba(148, 163, 184, 0.35);
            border-radius: 10px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono',
                'Courier New', monospace;
            font-size: 11px;
            line-height: 1.45;
            padding: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            white-space: pre-wrap;
        }

        #ar-debug-panel.is-visible {
            display: block;
        }

        .ar-debug-line.warn {
            color: #fbbf24;
        }

        .ar-debug-line.error {
            color: #f87171;
        }

        @keyframes ar-spin {
            to {
                transform: rotate(360deg);
            }
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <a-scene arjs embedded renderer="logarithmicDepthBuffer: true;" vr-mode-ui="enabled: false" gesture-detector
        id="scene">
        @foreach ($arMarkers as $marker)
            @continue($marker->virtualMuseumObjects->isEmpty())
            @php
                $markerObjects = $marker->virtualMuseumObjects
                    ->map(function ($object) {
                        return [
                            'object_id' => $object->object_id,
                            'nama' => $object->nama,
                            'deskripsi' => $object->deskripsi,
                            'model_src' => '/storage/' . $object->path_obj,
                            'scale' => $object->scale_string ?? '1 1 1',
                        ];
                    })
                    ->values();
            @endphp
            <!-- {{ $marker->nama ?: 'Marker #' . $marker->marker_id }} -->
            <a-marker type="pattern" url="/storage/{{ $marker->path_patt }}" raycaster="objects: .clickable"
                emitevents="true" cursor="fuse: false; rayOrigin: mouse;" id="marker{{ $marker->marker_id }}"
                data-marker-name="{{ $marker->nama ?: 'Marker #' . $marker->marker_id }}"
                data-marker-objects='@json($markerObjects)'>
                <a-entity id="marker{{ $marker->marker_id }}-entity" position="0 0 0" scale="1 1 1" class="clickable"
                    gesture-handler>
                </a-entity>
            </a-marker>
        @endforeach

        <a-entity camera></a-entity>
    </a-scene>

    <!-- Description overlay -->
    <div id="ar-description">
        <h4 id="ar-title"></h4>
        <p id="ar-text"></p>
    </div>

    <div id="ar-object-nav" aria-label="Navigasi object marker">
        <button id="ar-prev-object" class="ar-nav-button" type="button" aria-label="Object sebelumnya">&#8249;</button>
        <div id="ar-object-counter">Object 1 dari 1</div>
        <button id="ar-next-object" class="ar-nav-button" type="button" aria-label="Object berikutnya">&#8250;</button>
    </div>

    <div id="ar-loading" role="status" aria-live="polite" aria-hidden="true">
        <div id="ar-loading-card" class="ar-loading-card">
            <div class="ar-loading-spinner"></div>
            <h4 id="ar-loading-title">Memuat objek AR</h4>
            <p id="ar-loading-text">Model sedang dipersiapkan. Mohon arahkan kamera tetap ke marker.</p>
            <div class="ar-loading-progress-wrap" aria-hidden="true">
                <div id="ar-loading-progress-bar" class="ar-loading-progress-bar"></div>
            </div>
            <p id="ar-loading-progress-text" class="ar-loading-progress-text">Menunggu proses...</p>
        </div>
    </div>

    <button id="ar-debug-toggle" type="button" aria-controls="ar-debug-panel" aria-expanded="false">Debug</button>
    <div id="ar-debug-panel" role="log" aria-live="polite" aria-label="AR debug log"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /**
             *
             * @type {HTMLDivElement}
             */
            const descriptionDiv = document.getElementById('ar-description');
            const titleElement = document.getElementById('ar-title');
            const textElement = document.getElementById('ar-text');
            const objectNavElement = document.getElementById('ar-object-nav');
            const objectCounterElement = document.getElementById('ar-object-counter');
            const prevObjectButton = document.getElementById('ar-prev-object');
            const nextObjectButton = document.getElementById('ar-next-object');
            const loadingOverlay = document.getElementById('ar-loading');
            const loadingCard = document.getElementById('ar-loading-card');
            const loadingTitleElement = document.getElementById('ar-loading-title');
            const loadingTextElement = document.getElementById('ar-loading-text');
            const loadingProgressBarElement = document.getElementById('ar-loading-progress-bar');
            const loadingProgressTextElement = document.getElementById('ar-loading-progress-text');
            const debugToggleButton = document.getElementById('ar-debug-toggle');
            const debugPanelElement = document.getElementById('ar-debug-panel');
            let activeMarkerId = null;
            const visibleMarkers = new Map();
            const modelStates = new Map();
            const debugLogLines = [];
            let lastDebugLineText = null;
            let lastDebugLineAt = 0;
            let slowLoadingTimer = null;
            const LOADING_HINT_TIMEOUT_MS = 20000;
            const LONG_LOADING_HINT_TIMEOUT_MS = 120000;
            const MODEL_LOAD_TIMEOUT_MS = 180000;
            const NOISY_REJECTION_EVENTS = new Set(['load']);
            let sharedDracoLoader = null;
            const inspectedGlbSources = new Set();

            function stringifyForDebug(data) {
                if (data === undefined || data === null) {
                    return '';
                }

                if (typeof data === 'string') {
                    return data;
                }

                try {
                    return JSON.stringify(data);
                } catch (error) {
                    return String(data);
                }
            }

            function pushDebugLog(level, message, payload = null) {
                const timestamp = new Date().toLocaleTimeString('id-ID', {
                    hour12: false
                });
                const suffix = payload ? ` | ${stringifyForDebug(payload)}` : '';
                const lineText = `[${timestamp}] [${level.toUpperCase()}] ${message}${suffix}`;

                if (lineText === lastDebugLineText && Date.now() - lastDebugLineAt < 1500 && debugLogLines.length >
                    0) {
                    const lastLine = debugLogLines[debugLogLines.length - 1];
                    lastLine.repeatCount = (lastLine.repeatCount || 1) + 1;
                    lastLine.text = `${lineText} (x${lastLine.repeatCount})`;
                    debugPanelElement.innerHTML = debugLogLines
                        .map(line => `<div class="ar-debug-line ${line.level}">${line.text}</div>`)
                        .join('');
                    debugPanelElement.scrollTop = debugPanelElement.scrollHeight;
                    lastDebugLineAt = Date.now();
                    return;
                }

                lastDebugLineText = lineText;
                lastDebugLineAt = Date.now();

                if (debugLogLines.length >= 120) {
                    debugLogLines.shift();
                }

                debugLogLines.push({
                    level,
                    text: lineText,
                    repeatCount: 1,
                });

                debugPanelElement.innerHTML = debugLogLines
                    .map(line => `<div class="ar-debug-line ${line.level}">${line.text}</div>`)
                    .join('');
                debugPanelElement.scrollTop = debugPanelElement.scrollHeight;

                if (level === 'error') {
                    console.error('[AR]', message, payload || '');
                } else if (level === 'warn') {
                    console.warn('[AR]', message, payload || '');
                } else {
                    console.log('[AR]', message, payload || '');
                }
            }

            function getRejectionReasonInfo(reason) {
                if (reason instanceof Error) {
                    return {
                        type: 'Error',
                        message: reason.message,
                        stack: reason.stack,
                    };
                }

                if (reason instanceof Event) {
                    const target = reason.target;
                    return {
                        type: 'Event',
                        eventType: reason.type,
                        isTrusted: reason.isTrusted,
                        targetTag: target?.tagName || null,
                        targetSrc: target?.src || target?.currentSrc || null,
                    };
                }

                if (typeof reason === 'object' && reason !== null) {
                    return reason;
                }

                return {
                    type: typeof reason,
                    value: String(reason),
                };
            }

            function setDebugPanelVisible(isVisible) {
                debugPanelElement.classList.toggle('is-visible', isVisible);
                debugToggleButton.setAttribute('aria-expanded', isVisible ? 'true' : 'false');
            }

            function setupDracoForAframe() {
                if (!window.THREE || !window.THREE.GLTFLoader) {
                    pushDebugLog('warn', 'THREE.GLTFLoader belum tersedia saat setup Draco.');
                    return;
                }

                if (!window.THREE.DRACOLoader) {
                    pushDebugLog('warn', 'THREE.DRACOLoader tidak ditemukan. Model Draco akan gagal.');
                    return;
                }

                if (window.THREE.GLTFLoader.__arDracoPatched) {
                    pushDebugLog('info', 'Draco loader sudah aktif sebelumnya.');
                    return;
                }

                const OriginalGLTFLoader = window.THREE.GLTFLoader;
                const dracoLoader = new window.THREE.DRACOLoader();

                dracoLoader.setDecoderPath('https://www.gstatic.com/draco/versioned/decoders/1.5.7/');
                dracoLoader.setDecoderConfig({
                    type: 'js'
                });
                sharedDracoLoader = dracoLoader;

                function PatchedGLTFLoader(manager) {
                    const loader = new OriginalGLTFLoader(manager);

                    if (typeof loader.setDRACOLoader === 'function') {
                        loader.setDRACOLoader(dracoLoader);
                    }

                    return loader;
                }

                PatchedGLTFLoader.prototype = OriginalGLTFLoader.prototype;
                PatchedGLTFLoader.__arDracoPatched = true;
                window.THREE.GLTFLoader = PatchedGLTFLoader;

                pushDebugLog('info', 'Draco loader aktif untuk pipeline A-Frame marker.');
            }

            function loadModelWithThreeFallback(entity, modelSrc, scaleValue) {
                return new Promise((resolve, reject) => {
                    if (!window.THREE || !window.THREE.GLTFLoader) {
                        reject(new Error('THREE.GLTFLoader tidak tersedia untuk fallback.'));
                        return;
                    }

                    const loader = new window.THREE.GLTFLoader();

                    if (sharedDracoLoader && typeof loader.setDRACOLoader === 'function') {
                        loader.setDRACOLoader(sharedDracoLoader);
                    }

                    if (typeof loader.setCrossOrigin === 'function') {
                        loader.setCrossOrigin('anonymous');
                    }

                    loader.load(
                        modelSrc,
                        gltf => {
                            const scene = gltf?.scene || (Array.isArray(gltf?.scenes) ? gltf.scenes[0] :
                                null);

                            if (!scene) {
                                reject(new Error('GLTF berhasil dimuat tetapi scene kosong.'));
                                return;
                            }

                            entity.removeAttribute('gltf-model');
                            if (entity.getObject3D('mesh')) {
                                entity.removeObject3D('mesh');
                            }

                            entity.setObject3D('mesh', scene);
                            entity.setAttribute('scale', scaleValue || '1 1 1');
                            resolve();
                        },
                        undefined,
                        error => {
                            reject(error instanceof Error ? error : new Error(String(error)));
                        }
                    );
                });
            }

            debugToggleButton.addEventListener('click', () => {
                const nextVisible = !debugPanelElement.classList.contains('is-visible');
                setDebugPanelVisible(nextVisible);
            });

            const forceDebugVisible = new URLSearchParams(window.location.search).get('debug') === '1';
            setDebugPanelVisible(forceDebugVisible);
            pushDebugLog('info', 'Debug panel siap. Tambahkan ?debug=1 agar otomatis terbuka.');
            setupDracoForAframe();

            window.addEventListener('error', event => {
                pushDebugLog('error', 'Runtime error', {
                    message: event.message,
                    source: event.filename,
                    line: event.lineno,
                    column: event.colno,
                });
            });

            window.addEventListener('unhandledrejection', event => {
                event.preventDefault();
                const reason = event.reason;

                if (reason instanceof Event && NOISY_REJECTION_EVENTS.has(reason.type)) {
                    pushDebugLog('warn', 'Promise rejection event load diabaikan (noise dari library AR).');
                    return;
                }

                pushDebugLog('error', 'Unhandled promise rejection', {
                    reason: getRejectionReasonInfo(reason),
                });
            });

            const sceneElement = document.getElementById('scene');
            sceneElement?.addEventListener('renderstart', () => {
                const rendererInfo = sceneElement.renderer?.capabilities || null;
                pushDebugLog('info', 'Scene renderstart', rendererInfo ? {
                    maxTextureSize: rendererInfo.maxTextureSize,
                    maxCubemapSize: rendererInfo.maxCubemapSize,
                    precision: rendererInfo.precision,
                } : null);
            });

            sceneElement?.addEventListener('loaded', () => {
                const canvas = sceneElement.canvas;
                if (!canvas) {
                    return;
                }

                canvas.addEventListener('webglcontextlost', event => {
                    event.preventDefault();
                    pushDebugLog('error',
                        'WebGL context hilang. Kemungkinan memori GPU tidak cukup.');
                    showLoadingError('Model AR',
                        'WebGL context hilang. Kurangi ukuran tekstur/polygon model lalu coba lagi.'
                    );
                });

                canvas.addEventListener('webglcontextrestored', () => {
                    pushDebugLog('warn', 'WebGL context dipulihkan.');
                });
            });

            async function fetchModelMeta(modelSrc) {
                try {
                    const response = await fetch(modelSrc, {
                        method: 'HEAD',
                        cache: 'no-store'
                    });

                    if (!response.ok) {
                        return null;
                    }

                    const contentLength = response.headers.get('content-length');
                    const contentType = response.headers.get('content-type') || '';
                    const bytes = contentLength ? Number(contentLength) : NaN;
                    const sizeMb = Number.isFinite(bytes) ? (bytes / (1024 * 1024)).toFixed(2) : null;

                    return {
                        bytes: Number.isFinite(bytes) ? bytes : null,
                        sizeText: sizeMb ? `${sizeMb} MB` : 'unknown',
                        contentType,
                    };
                } catch (error) {
                    pushDebugLog('warn', 'Gagal mengambil metadata model', {
                        modelSrc,
                        message: error.message,
                    });
                    return null;
                }
            }

            function extractModelExtension(modelSrc) {
                const cleanPath = (modelSrc || '').split('?')[0].toLowerCase();

                if (cleanPath.endsWith('.glb')) {
                    return 'glb';
                }

                if (cleanPath.endsWith('.gltf')) {
                    return 'gltf';
                }

                if (cleanPath.endsWith('.obj')) {
                    return 'obj';
                }

                return 'unknown';
            }

            function getExpectedMimeList(modelExt) {
                if (modelExt === 'glb') {
                    return ['model/gltf-binary', 'application/octet-stream'];
                }

                if (modelExt === 'gltf') {
                    return ['model/gltf+json', 'application/json'];
                }

                return [];
            }

            function isMimeLikelyMismatch(contentType, modelExt) {
                const lowered = (contentType || '').toLowerCase();
                if (!lowered) {
                    return false;
                }

                if (lowered.includes('text/plain') || lowered.includes('text/html')) {
                    return true;
                }

                const expected = getExpectedMimeList(modelExt);
                if (expected.length === 0) {
                    return false;
                }

                return !expected.some(type => lowered.includes(type));
            }

            async function probeModelAccessibility(modelSrc) {
                try {
                    const modelExt = extractModelExtension(modelSrc);
                    const response = await fetch(modelSrc, {
                        method: 'GET',
                        headers: {
                            Range: 'bytes=0-0'
                        },
                        cache: 'no-store'
                    });

                    const detail = {
                        modelSrc,
                        status: response.status,
                        statusText: response.statusText,
                        contentType: response.headers.get('content-type') || null,
                        contentLength: response.headers.get('content-length') || null,
                        acceptRanges: response.headers.get('accept-ranges') || null,
                        modelExt,
                    };

                    if (!response.ok && response.status !== 206) {
                        pushDebugLog('error', 'Probe akses model gagal (indikasi server/path)', detail);
                        return;
                    }

                    pushDebugLog('info', 'Probe akses model berhasil', detail);

                    if (isMimeLikelyMismatch(detail.contentType, modelExt)) {
                        const expectedMimes = getExpectedMimeList(modelExt).join(' / ') || 'MIME model 3D';
                        pushDebugLog('warn',
                            `Content-Type model tidak ideal untuk .${modelExt}. Disarankan: ${expectedMimes}.`
                        );
                    }
                } catch (error) {
                    pushDebugLog('error', 'Probe akses model error', {
                        modelSrc,
                        message: error.message,
                    });
                }
            }

            async function inspectModelSignature(modelSrc) {
                try {
                    const modelExt = extractModelExtension(modelSrc);
                    const response = await fetch(modelSrc, {
                        method: 'GET',
                        headers: {
                            Range: 'bytes=0-31'
                        },
                        cache: 'no-store'
                    });

                    if (!response.ok && response.status !== 206) {
                        pushDebugLog('warn', 'Tidak bisa inspeksi signature model', {
                            modelSrc,
                            status: response.status,
                        });
                        return;
                    }

                    const buffer = await response.arrayBuffer();
                    const bytes = new Uint8Array(buffer);
                    const ascii = Array.from(bytes)
                        .map(b => (b >= 32 && b <= 126 ? String.fromCharCode(b) : '.'))
                        .join('');
                    const header = String.fromCharCode(...bytes.slice(0, 4));

                    pushDebugLog('info', 'Signature awal model', {
                        modelSrc,
                        modelExt,
                        header,
                        asciiPreview: ascii.slice(0, 16),
                    });

                    if (modelExt === 'glb' && header !== 'glTF') {
                        pushDebugLog('error',
                            'Header file bukan glTF. Kemungkinan file korup/terpotong/terganti response teks oleh server.'
                        );
                    }

                    if (modelExt === 'gltf' && !ascii.trim().startsWith('{')) {
                        pushDebugLog('warn',
                            'Preview .gltf tidak terlihat seperti JSON. Periksa apakah file benar dan tidak terdistorsi server.'
                        );
                    }
                } catch (error) {
                    pushDebugLog('warn', 'Gagal inspeksi signature model', {
                        modelSrc,
                        message: error.message,
                    });
                }
            }

            async function inspectGlbStructure(modelSrc) {
                if (extractModelExtension(modelSrc) !== 'glb' || inspectedGlbSources.has(modelSrc)) {
                    return;
                }

                inspectedGlbSources.add(modelSrc);

                try {
                    const response = await fetch(modelSrc, {
                        method: 'GET',
                        cache: 'no-store'
                    });

                    if (!response.ok) {
                        pushDebugLog('warn', 'Gagal inspeksi struktur GLB (HTTP)', {
                            modelSrc,
                            status: response.status,
                        });
                        return;
                    }

                    const buffer = await response.arrayBuffer();
                    const view = new DataView(buffer);

                    if (buffer.byteLength < 20) {
                        pushDebugLog('warn', 'GLB terlalu kecil untuk inspeksi struktur.', {
                            modelSrc,
                            byteLength: buffer.byteLength,
                        });
                        return;
                    }

                    const magic = view.getUint32(0, true);
                    const version = view.getUint32(4, true);
                    const length = view.getUint32(8, true);
                    const jsonChunkLength = view.getUint32(12, true);
                    const jsonChunkType = view.getUint32(16, true);

                    if (magic !== 0x46546c67 || jsonChunkType !== 0x4e4f534a) {
                        pushDebugLog('warn', 'Header GLB tidak sesuai spesifikasi.', {
                            modelSrc,
                            magic,
                            jsonChunkType,
                        });
                        return;
                    }

                    const jsonBytes = new Uint8Array(buffer, 20, jsonChunkLength);
                    const jsonText = new TextDecoder('utf-8').decode(jsonBytes).trim();
                    const gltf = JSON.parse(jsonText);

                    const images = Array.isArray(gltf.images) ? gltf.images : [];
                    const textures = Array.isArray(gltf.textures) ? gltf.textures : [];
                    const extensionsUsed = Array.isArray(gltf.extensionsUsed) ? gltf.extensionsUsed : [];
                    const extensionsRequired = Array.isArray(gltf.extensionsRequired) ? gltf
                        .extensionsRequired : [];

                    const invalidTextureSources = textures
                        .map((texture, index) => ({
                            index,
                            source: texture?.source,
                        }))
                        .filter(item => typeof item.source === 'number' && !images[item.source]);

                    pushDebugLog('info', 'Struktur GLB terbaca', {
                        modelSrc,
                        glbVersion: version,
                        declaredLength: length,
                        actualLength: buffer.byteLength,
                        assetVersion: gltf?.asset?.version || null,
                        generator: gltf?.asset?.generator || null,
                        imagesCount: images.length,
                        texturesCount: textures.length,
                        extensionsUsed,
                        extensionsRequired,
                    });

                    if (invalidTextureSources.length > 0) {
                        pushDebugLog('error', 'Ada texture.source yang menunjuk image tidak ada.', {
                            modelSrc,
                            invalidTextureSources,
                        });
                    }

                    const knownRiskExtensions = extensionsRequired.filter(ext => ['EXT_texture_webp',
                        'KHR_texture_basisu', 'EXT_meshopt_compression'
                    ].includes(ext));

                    if (knownRiskExtensions.length > 0) {
                        pushDebugLog('warn',
                            'Model memakai extension yang berisiko tidak kompatibel dengan GLTFLoader lama.', {
                                modelSrc,
                                knownRiskExtensions,
                            });
                    }
                } catch (error) {
                    pushDebugLog('warn', 'Gagal parsing struktur GLB', {
                        modelSrc,
                        message: error.message,
                    });
                }
            }

            function parseMarkerObjects(marker) {
                const rawObjects = marker.getAttribute('data-marker-objects') || '[]';

                try {
                    const parsed = JSON.parse(rawObjects);
                    return Array.isArray(parsed) ? parsed : [];
                } catch (error) {
                    console.error('Gagal membaca data object marker:', error);
                    return [];
                }
            }

            function clearSlowLoadingTimer() {
                if (slowLoadingTimer) {
                    clearTimeout(slowLoadingTimer);
                    slowLoadingTimer = null;
                }
            }

            function setProgress(percentage, text) {
                const safePercentage = Math.max(0, Math.min(100, percentage));
                loadingProgressBarElement.style.width = safePercentage + '%';

                if (text) {
                    loadingProgressTextElement.textContent = text;
                }
            }

            function showLoading(objectName) {
                clearSlowLoadingTimer();
                loadingCard.classList.remove('is-error');
                loadingTitleElement.textContent = 'Memuat ' + objectName;
                loadingTextElement.textContent =
                    'Model belum tersedia di perangkat. Mohon tunggu sampai model selesai dimuat.';
                setProgress(0, 'Menyiapkan proses unduh...');
                loadingOverlay.classList.add('is-visible');
                loadingOverlay.setAttribute('aria-hidden', 'false');
                pushDebugLog('info', 'Mulai loading object', {
                    objectName
                });

                slowLoadingTimer = setTimeout(() => {
                    loadingTextElement.textContent =
                        'Proses masih berlangsung. Ini biasanya terjadi jika model besar atau koneksi lambat.';
                }, 10000);
            }

            function hideLoading() {
                clearSlowLoadingTimer();
                loadingOverlay.classList.remove('is-visible');
                loadingOverlay.setAttribute('aria-hidden', 'true');
            }

            function showLoadingError(objectName, detailMessage = null) {
                clearSlowLoadingTimer();
                loadingCard.classList.add('is-error');
                loadingTitleElement.textContent = 'Gagal memuat ' + objectName;
                loadingTextElement.textContent = detailMessage ?
                    'Detail: ' + detailMessage :
                    'Periksa koneksi lalu arahkan ulang kamera ke marker untuk mencoba lagi.';
                setProgress(100, 'Terjadi error saat memuat model.');
                loadingOverlay.classList.add('is-visible');
                loadingOverlay.setAttribute('aria-hidden', 'false');
                pushDebugLog('error', 'Loading gagal', {
                    objectName,
                    detailMessage,
                });
            }

            function loadObjectForMarker(marker, objectData) {
                const entity = marker.querySelector('a-entity');
                const objectName = objectData.nama || 'Object AR';
                const modelSrc = objectData.model_src;
                const modelExt = extractModelExtension(modelSrc);
                const modelKey = marker.id + ':' + objectData.object_id;
                const existingState = modelStates.get(modelKey);

                pushDebugLog('info', 'Format model terdeteksi', {
                    objectName,
                    modelExt,
                    modelSrc,
                });

                if (modelExt === 'obj') {
                    const message =
                        'Format .obj belum didukung di AR marker ini. Gunakan .glb (disarankan) atau .gltf.';
                    pushDebugLog('error', 'Format model tidak didukung', {
                        modelSrc
                    });
                    showLoadingError(objectName, message);
                    return Promise.reject(new Error(message));
                }

                if (existingState?.loaded) {
                    if (existingState.loadedMode === 'three' && entity.getObject3D('mesh')) {
                        entity.setAttribute('scale', objectData.scale || '1 1 1');
                        pushDebugLog('info', 'Gunakan model cache (three object3D)', {
                            objectName,
                            modelSrc,
                        });
                        hideLoading();
                        return Promise.resolve();
                    }

                    if (existingState.loadedMode !== 'three') {
                        entity.setAttribute('gltf-model', existingState.modelSrc || modelSrc);
                        entity.setAttribute('scale', objectData.scale || '1 1 1');
                        pushDebugLog('info', 'Gunakan model cache (aframe gltf-model)', {
                            objectName,
                            modelSrc,
                        });
                        hideLoading();
                        return Promise.resolve();
                    }

                    pushDebugLog('warn', 'Cache three object3D tidak tersedia di entity, reload ulang.');
                }

                if (existingState?.loadingPromise) {
                    showLoading(objectName);
                    return existingState.loadingPromise;
                }

                showLoading(objectName);
                setProgress(20, 'Mengunduh dan memproses model...');
                probeModelAccessibility(modelSrc);
                inspectModelSignature(modelSrc);
                inspectGlbStructure(modelSrc);

                fetchModelMeta(modelSrc)
                    .then(meta => {
                        if (!meta) {
                            return;
                        }

                        pushDebugLog('info', 'Metadata model', {
                            objectName,
                            modelSrc,
                            modelExt,
                            contentType: meta.contentType,
                            size: meta.sizeText,
                        });

                        if (isMimeLikelyMismatch(meta.contentType, modelExt)) {
                            const expectedMimes = getExpectedMimeList(modelExt).join(' / ') || 'MIME model 3D';
                            pushDebugLog('warn',
                                `HEAD metadata menunjukkan Content-Type tidak cocok untuk .${modelExt}. Disarankan: ${expectedMimes}.`
                            );
                        }

                        if (meta.sizeText !== 'unknown') {
                            loadingProgressTextElement.textContent =
                                `Ukuran model ${meta.sizeText}. Sedang diproses...`;
                        }

                        if (meta.bytes && meta.bytes > 12 * 1024 * 1024) {
                            pushDebugLog('warn',
                                'Model cukup besar untuk mobile marker AR (>12MB). Pertimbangkan kompresi DRACO/tekstur.'
                            );
                        }
                    })
                    .catch(() => {});

                const loadingPromise = new Promise((resolve, reject) => {
                    let finished = false;
                    let attemptedThreeFallback = false;
                    let loadingHintTimer = null;
                    let longLoadingHintTimer = null;
                    let loadingTimeoutTimer = null;

                    const clearModelTimers = function() {
                        if (loadingHintTimer) {
                            clearTimeout(loadingHintTimer);
                            loadingHintTimer = null;
                        }

                        if (longLoadingHintTimer) {
                            clearTimeout(longLoadingHintTimer);
                            longLoadingHintTimer = null;
                        }

                        if (loadingTimeoutTimer) {
                            clearTimeout(loadingTimeoutTimer);
                            loadingTimeoutTimer = null;
                        }
                    };

                    const cleanupListeners = function() {
                        entity.removeEventListener('model-loaded', handleLoaded);
                        entity.removeEventListener('model-error', handleError);
                    };

                    const failLoading = function(message) {
                        if (finished) {
                            return;
                        }

                        finished = true;
                        clearModelTimers();
                        cleanupListeners();
                        entity.removeAttribute('gltf-model');
                        if (entity.getObject3D('mesh')) {
                            entity.removeObject3D('mesh');
                        }

                        modelStates.set(modelKey, {
                            loaded: false,
                            loadingPromise: null,
                            modelSrc,
                            loadedMode: null,
                        });

                        pushDebugLog('error', 'Gagal load model', {
                            objectName,
                            markerId: marker.id,
                            modelSrc,
                            message,
                        });
                        showLoadingError(objectName, message);
                        reject(new Error(message));
                    };

                    const handleLoaded = function() {
                        if (finished) {
                            return;
                        }

                        finished = true;
                        clearModelTimers();
                        cleanupListeners();

                        setProgress(100, 'Model siap ditampilkan.');
                        modelStates.set(modelKey, {
                            loaded: true,
                            loadingPromise: null,
                            modelSrc,
                            loadedMode: 'aframe',
                        });
                        pushDebugLog('info', 'Model loaded', {
                            objectName,
                            markerId: marker.id,
                            modelSrc,
                        });

                        setTimeout(() => {
                            hideLoading();
                            resolve();
                        }, 180);
                    };

                    const handleError = function(event) {
                        if (finished) {
                            return;
                        }

                        const detail = event?.detail || {};
                        const detailMessage = detail.src ?
                            `Tidak bisa memuat sumber model: ${detail.src}` :
                            (detail.message || 'Format atau isi file model tidak valid.');

                        pushDebugLog('error', 'Event model-error diterima', {
                            objectName,
                            markerId: marker.id,
                            modelSrc,
                            detail,
                        });

                        if (!attemptedThreeFallback && (modelExt === 'glb' || modelExt === 'gltf')) {
                            attemptedThreeFallback = true;
                            setProgress(76, 'A-Frame gagal, mencoba fallback loader Three.js...');
                            pushDebugLog('warn', 'Mencoba fallback loader Three.js untuk model ini.', {
                                objectName,
                                markerId: marker.id,
                                modelSrc,
                            });

                            loadModelWithThreeFallback(entity, modelSrc, objectData.scale)
                                .then(() => {
                                    if (finished) {
                                        return;
                                    }

                                    finished = true;
                                    clearModelTimers();
                                    cleanupListeners();

                                    setProgress(100, 'Model siap ditampilkan (fallback Three.js).');
                                    modelStates.set(modelKey, {
                                        loaded: true,
                                        loadingPromise: null,
                                        modelSrc,
                                        loadedMode: 'three',
                                    });
                                    pushDebugLog('info',
                                        'Fallback Three.js berhasil memuat model.', {
                                            objectName,
                                            markerId: marker.id,
                                            modelSrc,
                                        });

                                    setTimeout(() => {
                                        hideLoading();
                                        resolve();
                                    }, 180);
                                })
                                .catch(fallbackError => {
                                    pushDebugLog('error', 'Fallback Three.js gagal', {
                                        objectName,
                                        markerId: marker.id,
                                        modelSrc,
                                        message: fallbackError?.message || String(
                                            fallbackError),
                                    });
                                    failLoading(detailMessage);
                                });

                            return;
                        }

                        failLoading(detailMessage);
                    };

                    entity.addEventListener('model-loaded', handleLoaded, {
                        once: true
                    });
                    entity.addEventListener('model-error', handleError, {
                        once: true
                    });

                    loadingHintTimer = setTimeout(() => {
                        if (finished) {
                            return;
                        }

                        setProgress(70,
                            'Model masih diproses. Tetap arahkan kamera ke marker.');
                        loadingTextElement.textContent =
                            'Model bertekstur/kompleks bisa butuh waktu lebih lama di perangkat mobile.';
                    }, LOADING_HINT_TIMEOUT_MS);

                    longLoadingHintTimer = setTimeout(() => {
                        if (finished) {
                            return;
                        }

                        setProgress(88,
                            'Masih memproses model besar. Jika perlu, sederhanakan polygon/tekstur.'
                        );
                    }, LONG_LOADING_HINT_TIMEOUT_MS);

                    loadingTimeoutTimer = setTimeout(() => {
                        if (finished) {
                            return;
                        }

                        failLoading(
                            'Waktu memuat model terlalu lama. Kemungkinan model terlalu berat atau perangkat kehabisan memori GPU.'
                        );
                    }, MODEL_LOAD_TIMEOUT_MS);

                    if (entity.getObject3D('mesh')) {
                        entity.removeObject3D('mesh');
                    }
                    entity.setAttribute('gltf-model', modelSrc);
                    entity.setAttribute('scale', objectData.scale || '1 1 1');
                });

                modelStates.set(modelKey, {
                    loaded: false,
                    loadingPromise,
                    modelSrc,
                    loadedMode: null,
                });

                return loadingPromise;
            }

            function setActiveMarker(markerId) {
                if (!visibleMarkers.has(markerId)) {
                    activeMarkerId = null;
                    return;
                }

                activeMarkerId = markerId;
                visibleMarkers.get(markerId).timestamp = Date.now();
            }

            function getCurrentMarkerState() {
                if (activeMarkerId && visibleMarkers.has(activeMarkerId)) {
                    return visibleMarkers.get(activeMarkerId);
                }

                if (visibleMarkers.size === 0) {
                    return null;
                }

                const mostRecent = [...visibleMarkers.values()].reduce((latest, current) =>
                    current.timestamp > latest.timestamp ? current : latest
                );
                activeMarkerId = mostRecent.marker.id;

                return mostRecent;
            }

            function updateDescription() {
                const markerState = getCurrentMarkerState();

                if (!markerState || markerState.objects.length === 0) {
                    descriptionDiv.style.display = 'none';
                    objectNavElement.classList.remove('is-visible');
                    return;
                }

                const index = markerState.index || 0;
                const objectData = markerState.objects[index];

                titleElement.textContent = objectData.nama || markerState.marker.getAttribute('data-marker-name');
                textElement.textContent = objectData.deskripsi || 'Tidak ada deskripsi untuk object ini.';
                descriptionDiv.style.display = 'block';

                if (markerState.objects.length > 1) {
                    objectCounterElement.textContent =
                        `Object ${index + 1} dari ${markerState.objects.length}`;
                    prevObjectButton.disabled = false;
                    nextObjectButton.disabled = false;
                    objectNavElement.classList.add('is-visible');
                } else {
                    objectNavElement.classList.remove('is-visible');
                }
            }

            function showObjectForMarker(marker, nextIndex) {
                const markerState = visibleMarkers.get(marker.id);
                if (!markerState || markerState.objects.length === 0) {
                    return;
                }

                const totalObjects = markerState.objects.length;
                const normalizedIndex = ((nextIndex % totalObjects) + totalObjects) % totalObjects;
                const objectData = markerState.objects[normalizedIndex];
                pushDebugLog('info', 'Ganti object marker', {
                    markerId: marker.id,
                    targetIndex: normalizedIndex,
                    totalObjects,
                    objectName: objectData.nama,
                });

                loadObjectForMarker(marker, objectData)
                    .then(() => {
                        markerState.index = normalizedIndex;
                        setActiveMarker(marker.id);
                        updateDescription();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            prevObjectButton.addEventListener('click', () => {
                const markerState = getCurrentMarkerState();
                if (!markerState || markerState.objects.length <= 1) {
                    return;
                }

                showObjectForMarker(markerState.marker, markerState.index - 1);
            });

            nextObjectButton.addEventListener('click', () => {
                const markerState = getCurrentMarkerState();
                if (!markerState || markerState.objects.length <= 1) {
                    return;
                }

                showObjectForMarker(markerState.marker, markerState.index + 1);
            });

            // Get all markers
            const markers = document.querySelectorAll('a-marker');

            markers.forEach(marker => {
                // When marker becomes visible
                marker.addEventListener('markerFound', function() {
                    const markerObjects = parseMarkerObjects(this);
                    if (markerObjects.length === 0) {
                        pushDebugLog('warn', 'Marker ditemukan tapi tanpa object', {
                            markerId: this.id,
                            markerName: this.getAttribute('data-marker-name'),
                        });
                        return;
                    }

                    pushDebugLog('info', 'Marker terdeteksi', {
                        markerId: this.id,
                        markerName: this.getAttribute('data-marker-name'),
                        totalObjects: markerObjects.length,
                    });

                    const previousState = visibleMarkers.get(this.id);
                    visibleMarkers.set(this.id, {
                        marker: this,
                        objects: markerObjects,
                        index: previousState?.index || 0,
                        timestamp: Date.now(),
                    });

                    showObjectForMarker(this, visibleMarkers.get(this.id).index);
                });

                // When marker becomes invisible
                marker.addEventListener('markerLost', function() {
                    visibleMarkers.delete(this.id);
                    pushDebugLog('warn', 'Marker hilang', {
                        markerId: this.id,
                        markerName: this.getAttribute('data-marker-name'),
                    });

                    if (activeMarkerId === this.id) {
                        activeMarkerId = null;
                    }

                    if (visibleMarkers.size === 0) {
                        hideLoading();
                    }

                    updateDescription();
                });
            });
        });
    </script>
</body>

</html>
