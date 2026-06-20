<script>
    const tourData = @json($situs->toViewerJson());

    const State = {
        currentSceneId: null,
        isGyroEnabled: false
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
        modal: document.getElementById('info-modal'),
        modalTitle: document.getElementById('modal-title'),
        modalContent: document.getElementById('modal-content'),
        modalImage: document.getElementById('modal-image'),
        btnGyro: document.getElementById('btn-gyro'),
        blurOverlay: document.getElementById('blur-overlay'),
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

        setupEventListeners();
    }

    function loadInitialScene() {
        // Skip transition — loading screen already covers this
        const scene = getSceneById(tourData.scenes[0].id);
        if (!scene) return;
        State.currentSceneId = scene.id;
        DOM.currentSceneName.textContent = scene.name;
        DOM.sky.setAttribute('src', scene.image);
        renderHotspots(scene.hotspots);

        setTimeout(() => {
            DOM.loadingOverlay.style.opacity = '0';
            setTimeout(() => {
                DOM.loadingOverlay.style.display = 'none';
                preloadScenes();
            }, 500);
        }, 1000);
    }

    function preloadScenes() {
        tourData.scenes.forEach(scene => {
            if (scene.id !== State.currentSceneId) new Image().src = scene.image;
        });
    }

    function getSceneById(id) {
        return tourData.scenes.find(s => s.id === id);
    }

    function loadScene(sceneId) {
        const scene = getSceneById(sceneId);
        if (!scene || sceneId === State.currentSceneId) return;

        State.currentSceneId = sceneId;
        DOM.currentSceneName.textContent = scene.name;

        // Phase 1: zoom in + show blur overlay (never touch the WebGL canvas/filter)
        DOM.blurOverlay.classList.add('active');
        DOM.camera.setAttribute('animation__zoom', 'property: fov; to: 28; dur: 250; easing: easeInQuad');

        setTimeout(() => {
            DOM.camera.removeAttribute('animation__zoom');
            DOM.camera.setAttribute('fov', 28);

            let done = false;
            const finishTransition = () => {
                if (done) return;
                done = true;
                DOM.blurOverlay.classList.remove('active');
                DOM.camera.setAttribute('animation__zoom',
                    'property: fov; to: 80; dur: 450; easing: easeOutQuad');
            };

            // Attach listener BEFORE setting src to avoid missing the event on cached images
            DOM.sky.addEventListener('materialtextureloaded', function onLoaded() {
                DOM.sky.removeEventListener('materialtextureloaded', onLoaded);
                finishTransition();
            });

            DOM.sky.setAttribute('src', scene.image);
            renderHotspots(scene.hotspots);
            setTimeout(finishTransition, 6000); // fallback for very slow images
        }, 250);
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

    function showInfoModal(hs) {
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
