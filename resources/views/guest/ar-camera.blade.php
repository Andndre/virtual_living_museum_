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
        @foreach ($arObjects as $object)
            <!-- {{ $object->nama }} marker -->
            <a-marker type="pattern" url="/storage/{{ $object->path_patt }}" raycaster="objects: .clickable"
                emitevents="true" cursor="fuse: false; rayOrigin: mouse;" id="marker{{ $object->object_id }}"
                data-object-name="{{ $object->nama }}" data-object-description="{{ $object->deskripsi }}"
                data-model-src="/storage/{{ $object->path_obj }}">
                <a-entity id="{{ $object->object_id }}-entity" position="0 0 0" scale="{{ $object->scale_string }}"
                    class="clickable" gesture-handler>
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
            const loadingOverlay = document.getElementById('ar-loading');
            const loadingCard = document.getElementById('ar-loading-card');
            const loadingTitleElement = document.getElementById('ar-loading-title');
            const loadingTextElement = document.getElementById('ar-loading-text');
            const loadingProgressBarElement = document.getElementById('ar-loading-progress-bar');
            const loadingProgressTextElement = document.getElementById('ar-loading-progress-text');
            let lastVisibleObject = null;
            let visibleObjects = new Set();
            const modelStates = new Map();
            let slowLoadingTimer = null;

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

            function loadModelForMarker(marker) {
                const entity = marker.querySelector('a-entity');
                const objectName = marker.getAttribute('data-object-name');
                const modelSrc = marker.getAttribute('data-model-src');
                const markerId = marker.id;
                const isGlb = /\.glb($|\?)/i.test(modelSrc);
                const existingState = modelStates.get(markerId);

                if (existingState?.loaded) {
                    hideLoading();
                    return Promise.resolve();
                }

                if (existingState?.loadingPromise) {
                    showLoading(objectName);
                    return existingState.loadingPromise;
                }

                showLoading(objectName);

                const loadingPromise = new Promise(async (resolve, reject) => {
                    const handleLoaded = function() {
                        entity.removeEventListener('model-loaded', handleLoaded);
                        entity.removeEventListener('model-error', handleError);

                        setProgress(100, 'Model siap ditampilkan.');
                        modelStates.set(markerId, {
                            loaded: true,
                            loadingPromise: null,
                            objectUrl: modelStates.get(markerId)?.objectUrl || null,
                        });

                        setTimeout(() => {
                            hideLoading();
                            resolve();
                        }, 180);
                    };

                    const handleError = function(event) {
                        entity.removeEventListener('model-loaded', handleLoaded);
                        entity.removeEventListener('model-error', handleError);

                        const currentState = modelStates.get(markerId);
                        if (currentState?.objectUrl) {
                            URL.revokeObjectURL(currentState.objectUrl);
                        }

                        modelStates.set(markerId, {
                            loaded: false,
                            loadingPromise: null,
                            objectUrl: null,
                        });

                        entity.removeAttribute('gltf-model');
                        const detailMessage = event?.detail?.src ?
                            `Tidak bisa memuat sumber model: ${event.detail.src}` :
                            'Format atau isi file model tidak valid.';
                        showLoadingError(objectName, detailMessage);
                        reject(new Error('Gagal memuat model untuk ' + objectName));
                    };

                    entity.addEventListener('model-loaded', handleLoaded, {
                        once: true
                    });
                    entity.addEventListener('model-error', handleError, {
                        once: true
                    });

                    try {
                        if (isGlb) {
                            setProgress(4, 'Memulai unduhan model...');
                            const objectUrl = await downloadGlbWithProgress(modelSrc);

                            modelStates.set(markerId, {
                                loaded: false,
                                loadingPromise,
                                objectUrl,
                            });

                            entity.setAttribute('gltf-model', objectUrl);
                        } else {
                            setProgress(18, 'Memuat model. Menunggu resource pendukung...');
                            loadingProgressTextElement.textContent =
                                'Format .gltf terdeteksi, progress byte tidak selalu tersedia.';
                            entity.setAttribute('gltf-model', modelSrc);
                        }
                    } catch (downloadError) {
                        entity.removeEventListener('model-loaded', handleLoaded);
                        entity.removeEventListener('model-error', handleError);

                        modelStates.set(markerId, {
                            loaded: false,
                            loadingPromise: null,
                            objectUrl: null,
                        });

                        showLoadingError(objectName, downloadError.message || 'Gagal mengunduh model.');
                        reject(downloadError);
                    }
                });

                modelStates.set(markerId, {
                    loaded: false,
                    loadingPromise,
                    objectUrl: null,
                });

                return loadingPromise;
            }

            // Get all markers
            const markers = document.querySelectorAll('a-marker');

            markers.forEach(marker => {
                // When marker becomes visible
                marker.addEventListener('markerFound', function() {
                    const objectName = this.getAttribute('data-object-name');
                    const objectDescription = this.getAttribute('data-object-description');

                    console.log('Marker found:', objectName);

                    loadModelForMarker(this)
                        .then(() => {
                            visibleObjects.add({
                                name: objectName,
                                description: objectDescription,
                                timestamp: Date.now()
                            });

                            lastVisibleObject = {
                                name: objectName,
                                description: objectDescription
                            };

                            updateDescription();
                        })
                        .catch(error => {
                            console.error(error);
                        });
                });

                // When marker becomes invisible
                marker.addEventListener('markerLost', function() {
                    const objectName = this.getAttribute('data-object-name');

                    console.log('Marker lost:', objectName);

                    // Remove from visible objects
                    visibleObjects = new Set([...visibleObjects].filter(obj => obj.name !==
                        objectName));

                    // If this was the last visible object, find the next most recent
                    if (lastVisibleObject && lastVisibleObject.name === objectName) {
                        if (visibleObjects.size > 0) {
                            // Get the most recent visible object
                            const mostRecent = [...visibleObjects].reduce((latest, current) =>
                                current.timestamp > latest.timestamp ? current : latest
                            );
                            lastVisibleObject = {
                                name: mostRecent.name,
                                description: mostRecent.description
                            };
                        } else {
                            lastVisibleObject = null;
                        }
                    }

                    const state = modelStates.get(this.id);
                    if (state && !state.loaded) {
                        hideLoading();
                    }

                    updateDescription();
                });
            });

            function updateDescription() {
                if (lastVisibleObject && lastVisibleObject.description) {
                    titleElement.textContent = lastVisibleObject.name;
                    textElement.textContent = lastVisibleObject.description;
                    descriptionDiv.style.display = 'block';
                } else {
                    descriptionDiv.style.display = 'none';
                }
            }
        });
    </script>
</body>

</html>
