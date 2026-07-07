<script>
    const tourData = @json($situs->toViewerJson());

    const State = {
        currentSceneId: null,
        isGyroEnabled: false,
        isVR: false,
        vrPages: [],
        vrCurrentPage: 0
    };

    const DOM = {
        scene: document.getElementById('panorama-scene'),
        sky: document.getElementById('panorama-sky'),
        hotspotsContainer: document.getElementById('hotspots-container'),
        assets: document.getElementById('scene-assets'),
        loadingOverlay: document.getElementById('loading-overlay'),
        loadingText: document.getElementById('loading-text'),
        currentSceneName: document.getElementById('current-scene-name'),
        camera: document.getElementById('camera'),
        cursor: document.getElementById('cursor'),
        modal: document.getElementById('info-modal'),
        modalTitle: document.getElementById('modal-title'),
        modalContent: document.getElementById('modal-content'),
        modalImage: document.getElementById('modal-image'),
        btnGyro: document.getElementById('btn-gyro'),
        blurOverlay: document.getElementById('blur-overlay'),
        vrFade: document.getElementById('vr-fade'),
        vrInfoContainer: document.getElementById('vr-info-container'),
        vrInfoPanel: document.getElementById('vr-info-panel'),
        vrInfoTitle: document.getElementById('vr-info-title'),
        vrInfoBody: document.getElementById('vr-info-body'),
        vrInfoPage: document.getElementById('vr-info-page'),
        vrInfoPrev: document.getElementById('vr-info-prev'),
        vrInfoNext: document.getElementById('vr-info-next'),
        vrInfoClose: document.getElementById('vr-info-close'),
        vrInfoDismiss: document.getElementById('vr-info-dismiss'),
    };

    function init() {
        if (!tourData.scenes || tourData.scenes.length === 0) {
            DOM.loadingText.textContent = "Tidak ada adegan (scene) yang tersedia.";
            return;
        }

        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        if (isMobile) {
            State.isGyroEnabled = true;
            DOM.camera.setAttribute('look-controls', 'magicWindowTrackingEnabled: true');
            DOM.btnGyro.classList.add('text-cyan-400');
        } else {
            DOM.btnGyro.style.display = 'none';
            const btnVr = document.getElementById('btn-vr-custom');
            const divider = document.getElementById('divider-controls');
            if (navigator.xr && navigator.xr.isSessionSupported) {
                navigator.xr.isSessionSupported('immersive-vr').then(supported => {
                    if (!supported) {
                        btnVr.style.display = 'none';
                        if (divider) divider.style.display = 'none';
                    }
                });
            } else {
                btnVr.style.display = 'none';
                if (divider) divider.style.display = 'none';
            }
        }

        if (DOM.scene.hasLoaded) {
            loadInitialScene();
        } else {
            DOM.scene.addEventListener('loaded', loadInitialScene);
        }

        setupVRListeners();
        setupEventListeners();
    }

    // Mouse-driven cursor/modal don't work inside an immersive WebXR session, so we
    // swap to a gaze cursor and an in-world info panel while VR is active.
    function setupVRListeners() {
        DOM.scene.addEventListener('enter-vr', () => {
            State.isVR = true;
            DOM.cursor.setAttribute('cursor', 'fuse: true; fuseTimeout: 1200; rayOrigin: entity');
            DOM.cursor.setAttribute('visible', 'true');
        });

        DOM.scene.addEventListener('exit-vr', () => {
            State.isVR = false;
            DOM.cursor.setAttribute('cursor', 'fuse: false; rayOrigin: mouse');
            DOM.cursor.setAttribute('raycaster', 'objects: .clickable; far: 500');
            DOM.cursor.setAttribute('visible', String(!('ontouchstart' in window)));
            DOM.vrInfoContainer.setAttribute('visible', 'false');
        });

        DOM.vrInfoPrev.addEventListener('click', () => {
            if (State.vrCurrentPage > 0) {
                State.vrCurrentPage--;
                updateVRPagination();
            }
        });
        
        DOM.vrInfoNext.addEventListener('click', () => {
            if (State.vrCurrentPage < State.vrPages.length - 1) {
                State.vrCurrentPage++;
                updateVRPagination();
            }
        });

        DOM.vrInfoDismiss.addEventListener('click', closeInfoModal);
        DOM.vrInfoClose.addEventListener('click', closeInfoModal);
    }

    function hideLoadingOverlay(sceneId) {
        DOM.loadingOverlay.style.opacity = '0';
        setTimeout(() => {
            DOM.loadingOverlay.style.display = 'none';
            preloadAdjacent(sceneId);
        }, 500);
    }

    function loadInitialScene() {
        const scene = getSceneById(tourData.scenes[0].id);
        if (!scene) return;
        State.currentSceneId = scene.id;
        DOM.currentSceneName.textContent = scene.name;

        // Preload image via <a-assets> for reliable texture loading & materialtextureloaded event
        const imgId = 'pano-img-' + scene.id;
        let imgEl = document.getElementById(imgId);
        if (!imgEl) {
            imgEl = document.createElement('img');
            imgEl.id = imgId;
            imgEl.setAttribute('crossorigin', 'anonymous');
            imgEl.src = scene.image;
            DOM.assets.appendChild(imgEl);
        }

        // Fallback: if materialtextureloaded never fires (e.g. network error), still hide overlay
        const fallbackTimer = setTimeout(() => hideLoadingOverlay(scene.id), 8000);

        DOM.sky.addEventListener('materialtextureloaded', function onFirst() {
            DOM.sky.removeEventListener('materialtextureloaded', onFirst);
            clearTimeout(fallbackTimer);
            hideLoadingOverlay(scene.id);
        });

        // Use asset selector so A-Frame manages color space & fires events reliably
        DOM.sky.setAttribute('src', '#' + imgId);
        renderHotspots(scene.hotspots);
    }

    function getAdjacentIds(sceneId) {
        const idx = tourData.scenes.findIndex(s => s.id === sceneId);
        const ids = [];
        if (idx > 0) ids.push(tourData.scenes[idx - 1].id);
        if (idx < tourData.scenes.length - 1) ids.push(tourData.scenes[idx + 1].id);
        // Also include navigation hotspot targets from current scene
        const scene = getSceneById(sceneId);
        if (scene && scene.hotspots) {
            scene.hotspots.forEach(hs => {
                if (hs.type === 'navigation' && hs.targetScene && !ids.includes(hs.targetScene)) {
                    ids.push(hs.targetScene);
                }
            });
        }
        return ids;
    }

    function preloadAdjacent(sceneId) {
        getAdjacentIds(sceneId).forEach(id => {
            const s = getSceneById(id);
            if (s) ensureSceneAsset(s);
        });
    }

    function getSceneById(id) {
        return tourData.scenes.find(s => s.id === id);
    }

    // Create (idempotent) a real <a-assets> <img> element so A-Frame decodes and
    // GPU-uploads the texture ahead of time, avoiding a first-time black flash.
    function ensureSceneAsset(scene) {
        const imgId = 'pano-img-' + scene.id;
        let imgEl = document.getElementById(imgId);
        if (!imgEl) {
            imgEl = document.createElement('img');
            imgEl.id = imgId;
            imgEl.setAttribute('crossorigin', 'anonymous');
            imgEl.src = scene.image;
            DOM.assets.appendChild(imgEl);
        }
        return imgId;
    }

    function loadScene(sceneId) {
        const scene = getSceneById(sceneId);
        if (!scene || sceneId === State.currentSceneId) return;

        State.currentSceneId = sceneId;
        DOM.currentSceneName.textContent = scene.name;

        if (State.isVR) {
            loadSceneVR(scene);
            return;
        }

        // Phase 1: push forward into the current image — zoom in + blur overlay
        DOM.blurOverlay.classList.add('active');
        DOM.camera.setAttribute('animation__zoom', 'property: fov; to: 30; dur: 300; easing: easeInQuad');

        setTimeout(() => {
            DOM.camera.removeAttribute('animation__zoom');

            // Ensure the asset element exists (decoded + GPU-uploaded) before swapping
            const imgId = ensureSceneAsset(scene);

            let done = false;
            const finishTransition = () => {
                if (done) return;
                done = true;

                // New scene starts zoomed OUT (wide fov) while still hidden behind blur,
                // then dolly forward to the normal fov so it feels like stepping into it.
                DOM.camera.removeAttribute('animation__zoom');
                DOM.camera.setAttribute('fov', 100);
                DOM.blurOverlay.classList.remove('active');

                requestAnimationFrame(() => {
                    DOM.camera.setAttribute('animation__zoom',
                        'property: fov; to: 80; dur: 600; easing: easeOutCubic');
                });
                preloadAdjacent(sceneId);
            };

            // Attach listener BEFORE setting src to avoid missing the event on cached images
            DOM.sky.addEventListener('materialtextureloaded', function onLoaded() {
                DOM.sky.removeEventListener('materialtextureloaded', onLoaded);
                finishTransition();
            });

            // Use asset selector so A-Frame manages color space & fires events reliably
            DOM.sky.setAttribute('src', '#' + imgId);
            renderHotspots(scene.hotspots);
            setTimeout(finishTransition, 6000);
        }, 300);
    }

    // FOV is driven by the headset in an immersive session, so the desktop zoom
    // transition above has no effect — swap the scene behind a simple fade instead.
    function loadSceneVR(scene) {
        DOM.vrFade.setAttribute('visible', 'true');
        DOM.vrFade.setAttribute('animation__fade', 'property: material.opacity; to: 1; dur: 300; easing: easeInQuad');

        setTimeout(() => {
            const imgId = ensureSceneAsset(scene);
            DOM.sky.setAttribute('src', '#' + imgId);
            renderHotspots(scene.hotspots);
            preloadAdjacent(scene.id);

            DOM.vrFade.setAttribute('animation__fade', 'property: material.opacity; to: 0; dur: 300; easing: easeOutQuad');
            setTimeout(() => DOM.vrFade.setAttribute('visible', 'false'), 300);
        }, 300);
    }

    function renderHotspots(hotspots) {
        while (DOM.hotspotsContainer.firstChild) {
            DOM.hotspotsContainer.removeChild(DOM.hotspotsContainer.firstChild);
        }
        if (!hotspots) return;

        hotspots.forEach(hs => {
            const entity = document.createElement('a-entity');
            entity.setAttribute('position', hs.position);
            entity.setAttribute('rotation', hs.rotation || "0 0 0");
            entity.setAttribute('look-at', '#camera');

            let imgSrc = '#icon-info';
            let isCustomVideo = false;
            const isNav = hs.type === 'navigation';

            if (hs.animation_config && hs.animation_config.icon) {
                if (hs.animation_config.icon === 'custom') {
                    if (hs.animation_config.custom_url) {
                        const url = hs.animation_config.custom_url;
                        isCustomVideo = !!url.match(/\.(mp4|webm)$/i);
                        if (isCustomVideo) {
                            const assetId = 'video-' + hs.id;
                            let videoEl = document.getElementById(assetId);
                            if (!videoEl) {
                                videoEl = document.createElement('video');
                                videoEl.id = assetId;
                                videoEl.setAttribute('src', url);
                                videoEl.setAttribute('autoplay', 'true');
                                videoEl.setAttribute('loop', 'true');
                                videoEl.setAttribute('muted', 'true');
                                videoEl.setAttribute('playsinline', 'true');
                                videoEl.setAttribute('crossorigin', 'anonymous');
                                document.querySelector('a-assets').appendChild(videoEl);
                                videoEl.play().catch(e => console.log('Video autoplay prevented'));
                            }
                            imgSrc = '#' + assetId;
                        } else {
                            imgSrc = url;
                        }
                    } else {
                        imgSrc = isNav ? '#icon-arrow-up' : '#icon-info';
                    }
                } else {
                    imgSrc = '#' + hs.animation_config.icon;
                }
            } else {
                imgSrc = isNav ? '#icon-arrow-up' : '#icon-info';
            }

            let img;
            let hasScaleAnimation = false;

            if (isCustomVideo) {
                img = document.createElement('a-video');
                img.setAttribute('src', imgSrc);
                img.setAttribute('width', '1');
                img.setAttribute('height', '1');
                img.setAttribute('class', 'clickable');
            } else {
                img = document.createElement('a-image');
                img.setAttribute('src', imgSrc);
                img.setAttribute('width', '1');
                img.setAttribute('height', '1');
                img.setAttribute('class', 'clickable');

                if (hs.animation_config && hs.animation_config.animation) {
                    if (hs.animation_config.animation === 'pulse') {
                        img.setAttribute('animation__scale',
                            'property: scale; dir: alternate; dur: 800; easing: easeInOutSine; loop: true; to: 1.2 1.2 1.2');
                        hasScaleAnimation = true;
                    } else if (hs.animation_config.animation === 'bob') {
                        img.setAttribute('animation__pos',
                            'property: position; dir: alternate; dur: 1000; easing: easeInOutSine; loop: true; to: 0 0.2 0');
                    } else if (hs.animation_config.animation === 'spin') {
                        img.setAttribute('animation__rot',
                            'property: rotation; dur: 2000; easing: linear; loop: true; to: 0 0 360');
                    }
                }
            }

            if (!hasScaleAnimation && !isCustomVideo) {
                img.setAttribute('animation__mouseenter',
                    'property: scale; to: 1.2 1.2 1.2; dur: 200; startEvents: mouseenter');
                img.setAttribute('animation__mouseleave',
                    'property: scale; to: 1 1 1; dur: 200; startEvents: mouseleave');
            }

            img.addEventListener('click', () => {
                if (isNav && hs.targetScene) {
                    loadScene(hs.targetScene);
                } else if (!isNav) {
                    showInfoModal(hs);
                }
            });

            entity.appendChild(img);

            if (hs.label && hs.label.trim() !== '') {
                const label = document.createElement('a-text');
                label.setAttribute('value', hs.label);
                label.setAttribute('align', 'center');
                label.setAttribute('position', '0 -0.8 0');
                label.setAttribute('scale', '1.5 1.5 1.5');
                label.setAttribute('color', 'white');
                entity.appendChild(label);
            }

            DOM.hotspotsContainer.appendChild(entity);
        });
    }

    function paginateText(text, maxChars) {
        if (!text) return [""];
        const words = text.trim().split(/\s+/);
        const pages = [];
        let currentPage = "";
        
        words.forEach(word => {
            if (currentPage.length + word.length + 1 > maxChars && currentPage.length > 0) {
                pages.push(currentPage);
                currentPage = word;
            } else {
                currentPage = currentPage ? currentPage + " " + word : word;
            }
        });
        if (currentPage) pages.push(currentPage);
        
        return pages.length > 0 ? pages : [""];
    }

    function updateVRPagination() {
        DOM.vrInfoBody.setAttribute('value', State.vrPages[State.vrCurrentPage] || '');
        DOM.vrInfoPage.setAttribute('value', `hal. ${State.vrCurrentPage + 1}/${State.vrPages.length}`);
        
        if (State.vrCurrentPage > 0) {
            DOM.vrInfoPrev.setAttribute('visible', 'true');
            DOM.vrInfoPrev.classList.add('vr-ui-clickable');
        } else {
            DOM.vrInfoPrev.setAttribute('visible', 'false');
            DOM.vrInfoPrev.classList.remove('vr-ui-clickable');
        }
        
        if (State.vrCurrentPage < State.vrPages.length - 1) {
            DOM.vrInfoNext.setAttribute('visible', 'true');
            DOM.vrInfoNext.classList.add('vr-ui-clickable');
        } else {
            DOM.vrInfoNext.setAttribute('visible', 'false');
            DOM.vrInfoNext.classList.remove('vr-ui-clickable');
        }
    }

    function showInfoModal(hs) {
        if (State.isVR) {
            // Swap raycaster guard
            DOM.cursor.setAttribute('raycaster', 'objects: .vr-ui-clickable; far: 500');

            const plainText = document.createElement('div');
            plainText.innerHTML = hs.modalContent || '';
            const textContent = (plainText.textContent || '').trim();

            State.vrPages = paginateText(textContent, 300);
            State.vrCurrentPage = 0;

            const titleText = hs.modalTitle || hs.label || 'Info';
            // Truncate title
            DOM.vrInfoTitle.setAttribute('value', titleText.length > 30 ? titleText.substring(0, 27) + '...' : titleText);
            
            updateVRPagination();

            // Calculate spawn position in front of user
            const cameraObj = DOM.camera.object3D;
            const camWorldPos = new THREE.Vector3();
            cameraObj.getWorldPosition(camWorldPos);

            const forward = new THREE.Vector3(0, 0, -1).applyQuaternion(cameraObj.getWorldQuaternion(new THREE.Quaternion()));
            forward.y = 0;
            forward.normalize();
            
            const spawnPos = camWorldPos.clone().add(forward.multiplyScalar(2));
            spawnPos.y = 0; // Lock height around y=0

            DOM.vrInfoContainer.setAttribute('position', `${spawnPos.x} ${spawnPos.y} ${spawnPos.z}`);
            DOM.vrInfoContainer.setAttribute('visible', 'true');
            return;
        }

        DOM.modalTitle.textContent = hs.modalTitle || hs.label;
        DOM.modalContent.innerHTML = hs.modalContent || '';
        if (hs.modalImage) {
            DOM.modalImage.src = hs.modalImage;
            DOM.modalImage.style.display = 'block';
        } else {
            DOM.modalImage.style.display = 'none';
        }
        DOM.modal.classList.add('active');
    }

    function closeInfoModal() {
        DOM.vrInfoContainer.setAttribute('visible', 'false');
        DOM.cursor.setAttribute('raycaster', 'objects: .clickable; far: 500');
        DOM.modal.classList.remove('active');
    }

    function setupEventListeners() {
        document.getElementById('btn-fullscreen').addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                document.exitFullscreen();
            }
        });

        DOM.btnGyro.addEventListener('click', () => {
            State.isGyroEnabled = !State.isGyroEnabled;
            DOM.camera.setAttribute('look-controls', `magicWindowTrackingEnabled: ${State.isGyroEnabled}`);
            if (State.isGyroEnabled) {
                DOM.btnGyro.classList.add('text-cyan-400');
                if (typeof DeviceMotionEvent !== 'undefined' && typeof DeviceMotionEvent.requestPermission === 'function') {
                    DeviceMotionEvent.requestPermission()
                        .then(permissionState => {
                            if (permissionState !== 'granted') {
                                alert('Akses sensor orientasi ditolak.');
                                State.isGyroEnabled = false;
                                DOM.btnGyro.classList.remove('text-cyan-400');
                            }
                        })
                        .catch(console.error);
                }
            } else {
                DOM.btnGyro.classList.remove('text-cyan-400');
            }
        });

        document.getElementById('btn-close-modal').addEventListener('click', closeInfoModal);
        DOM.modal.addEventListener('click', (e) => {
            if (e.target === DOM.modal) closeInfoModal();
        });

        if (!('ontouchstart' in window)) {
            document.getElementById('cursor').setAttribute('visible', 'true');
        }

        // Zoom on scroll — skip when modal is open so modal scrolling doesn't zoom the panorama
        window.addEventListener('wheel', (e) => {
            if (DOM.modal.classList.contains('active')) return;

            const camera = document.getElementById('camera');
            if (!camera) return;

            let fov = parseFloat(camera.getAttribute('fov')) || 80;
            const zoomSpeed = 3;
            fov = e.deltaY > 0 ? fov + zoomSpeed : fov - zoomSpeed;
            fov = Math.max(30, Math.min(110, fov));
            camera.setAttribute('fov', fov);
        }, { passive: true });
    }

    window.addEventListener('DOMContentLoaded', init);
</script>
