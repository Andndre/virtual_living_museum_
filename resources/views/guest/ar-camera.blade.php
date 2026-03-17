<!DOCTYPE html>

<html>

<head>
    <title>AR</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <script src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>
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
            let activeMarkerId = null;
            const visibleMarkers = new Map();
            const modelStates = new Map();
            let slowLoadingTimer = null;
            const COMPATIBILITY_RETRY_TIMEOUT_MS = 12000;
            const FINAL_MODEL_TIMEOUT_MS = 45000;

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
            }

            async function downloadGlbWithProgress(modelSrc) {
                const response = await fetch(modelSrc);

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ' saat mengunduh model');
                }

                const totalBytes = Number(response.headers.get('content-length') || 0);

                if (!response.body || !Number.isFinite(totalBytes) || totalBytes <= 0) {
                    const blobWithoutStream = await response.blob();
                    setProgress(100, 'Unduhan selesai. Menyusun model...');
                    return URL.createObjectURL(blobWithoutStream);
                }

                const reader = response.body.getReader();
                const chunks = [];
                let receivedBytes = 0;

                while (true) {
                    const {
                        done,
                        value
                    } = await reader.read();

                    if (done) {
                        break;
                    }

                    chunks.push(value);
                    receivedBytes += value.length;

                    const percentage = (receivedBytes / totalBytes) * 100;
                    const downloadedMb = (receivedBytes / 1024 / 1024).toFixed(2);
                    const totalMb = (totalBytes / 1024 / 1024).toFixed(2);

                    setProgress(percentage, `Mengunduh ${downloadedMb} MB / ${totalMb} MB`);
                }

                const blob = new Blob(chunks);
                setProgress(100, 'Unduhan selesai. Menyusun model...');
                return URL.createObjectURL(blob);
            }

            function loadObjectForMarker(marker, objectData) {
                const entity = marker.querySelector('a-entity');
                const objectName = objectData.nama || 'Object AR';
                const modelSrc = objectData.model_src;
                const modelKey = marker.id + ':' + objectData.object_id;
                const isGlb = /\.glb($|\?)/i.test(modelSrc);
                const existingState = modelStates.get(modelKey);

                if (existingState?.loaded) {
                    entity.setAttribute('gltf-model', existingState.modelSrc || modelSrc);
                    entity.setAttribute('scale', objectData.scale || '1 1 1');
                    hideLoading();
                    return Promise.resolve();
                }

                if (existingState?.loadingPromise) {
                    showLoading(objectName);
                    return existingState.loadingPromise;
                }

                showLoading(objectName);

                const loadingPromise = new Promise(async (resolve, reject) => {
                    let finished = false;
                    let compatibilityRetryTimer = null;
                    let finalModelTimeout = null;

                    const clearModelTimers = function() {
                        if (compatibilityRetryTimer) {
                            clearTimeout(compatibilityRetryTimer);
                            compatibilityRetryTimer = null;
                        }

                        if (finalModelTimeout) {
                            clearTimeout(finalModelTimeout);
                            finalModelTimeout = null;
                        }
                    };

                    const cleanupListeners = function() {
                        entity.removeEventListener('model-loaded', handleLoaded);
                        entity.removeEventListener('model-error', handleError);
                    };

                    const cleanupObjectUrl = function() {
                        const currentState = modelStates.get(modelKey);
                        if (currentState?.objectUrl) {
                            URL.revokeObjectURL(currentState.objectUrl);
                        }

                        modelStates.set(modelKey, {
                            loaded: false,
                            loadingPromise,
                            objectUrl: null,
                            modelSrc,
                        });
                    };

                    const failLoading = function(message) {
                        if (finished) {
                            return;
                        }

                        finished = true;
                        clearModelTimers();
                        cleanupListeners();
                        cleanupObjectUrl();
                        entity.removeAttribute('gltf-model');

                        showLoadingError(objectName, message);
                        reject(new Error(message));
                    };

                    const switchToCompatibilityMode = function(reason) {
                        if (!isGlb || finished) {
                            return false;
                        }

                        const state = modelStates.get(modelKey);
                        if (!state?.objectUrl) {
                            return false;
                        }

                        cleanupObjectUrl();
                        entity.removeAttribute('gltf-model');

                        setProgress(96,
                            'Mode kompatibilitas aktif. Mencoba memuat dari URL langsung...');
                        loadingTextElement.textContent = reason ||
                            'Parsing model terlalu lama. Mencoba mode alternatif...';
                        entity.setAttribute('gltf-model', modelSrc);

                        return true;
                    };

                    const handleLoaded = function() {
                        if (finished) {
                            return;
                        }

                        finished = true;
                        clearModelTimers();
                        cleanupListeners();

                        const currentState = modelStates.get(modelKey);
                        if (currentState?.objectUrl) {
                            URL.revokeObjectURL(currentState.objectUrl);
                        }

                        setProgress(100, 'Model siap ditampilkan.');
                        modelStates.set(modelKey, {
                            loaded: true,
                            loadingPromise: null,
                            objectUrl: null,
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

                        const detailMessage = event?.detail?.src ?
                            `Tidak bisa memuat sumber model: ${event.detail.src}` :
                            'Format atau isi file model tidak valid.';

                        const switched = switchToCompatibilityMode(
                            'Percobaan pertama gagal dimuat. Beralih ke mode kompatibilitas...');
                        if (!switched) {
                            failLoading(detailMessage);
                        }
                    };

                    entity.addEventListener('model-loaded', handleLoaded, {
                        once: true
                    });
                    entity.addEventListener('model-error', handleError, {
                        once: true
                    });

                    finalModelTimeout = setTimeout(() => {
                        if (finished) {
                            return;
                        }

                        const switched = switchToCompatibilityMode(
                            'Menyusun model terlalu lama. Mencoba mode kompatibilitas...');
                        if (!switched) {
                            failLoading(
                                'Waktu memuat model habis. Coba ulangi dengan model yang lebih ringan.'
                            );
                        }
                    }, FINAL_MODEL_TIMEOUT_MS);

                    try {
                        if (isGlb) {
                            setProgress(4, 'Memulai unduhan model...');
                            const objectUrl = await downloadGlbWithProgress(modelSrc);

                            modelStates.set(modelKey, {
                                loaded: false,
                                loadingPromise,
                                objectUrl,
                                modelSrc,
                            });

                            entity.setAttribute('gltf-model', objectUrl);
                            entity.setAttribute('scale', objectData.scale || '1 1 1');

                            compatibilityRetryTimer = setTimeout(() => {
                                if (finished) {
                                    return;
                                }

                                switchToCompatibilityMode(
                                    'Parsing model dari cache lokal lama. Mencoba mode kompatibilitas...'
                                );
                            }, COMPATIBILITY_RETRY_TIMEOUT_MS);
                        } else {
                            setProgress(18, 'Memuat model. Menunggu resource pendukung...');
                            loadingProgressTextElement.textContent =
                                'Format .gltf terdeteksi, progress byte tidak selalu tersedia.';
                            entity.setAttribute('gltf-model', modelSrc);
                            entity.setAttribute('scale', objectData.scale || '1 1 1');
                        }
                    } catch (downloadError) {
                        failLoading(downloadError.message || 'Gagal mengunduh model.');
                    }
                });

                modelStates.set(modelKey, {
                    loaded: false,
                    loadingPromise,
                    objectUrl: null,
                    modelSrc,
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
                        return;
                    }

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
