// AR Museum WebXR Implementation
import * as THREE from "three";

class ARMuseum {
    constructor(museumData) {
        this.museumData = museumData;
        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.reticle = null;
        this.hitTestSource = null;
        this.hitTestSourceRequested = false;
        this.session = null;
        this.isARActive = false;
        this.controller = null;
        
        this.init();
    }

    async init() {
        try {
            await this.checkWebXRSupport();
            await this.initializeAR();
        } catch (error) {
            console.error('AR initialization failed:', error);
            this.showError(error.message);
        }
    }

    async checkWebXRSupport() {
        if (!navigator.xr) {
            throw new Error('WebXR tidak didukung di browser ini. Gunakan Chrome atau Edge terbaru.');
        }

        const supported = await navigator.xr.isSessionSupported('immersive-ar');
        if (!supported) {
            throw new Error('AR tidak didukung di perangkat ini. Pastikan menggunakan device yang kompatibel.');
        }
    }

    async initializeAR() {
        // Hide loading screen
        document.getElementById('loading-screen').style.display = 'none';
        document.getElementById('ar-container').style.display = 'block';

        // Initialize Three.js
        this.setupThreeJS();
        await this.setupWebXRSession();
        
        // Update status to show AR is ready
        this.updateStatus('AR siap! Arahkan kamera ke permukaan datar.');
        
        // Start render loop
        this.renderer.setAnimationLoop((timestamp, frame) => this.render(timestamp, frame));
    }

    setupThreeJS() {
        // Initialize scene
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.01, 20);
        
        // Setup renderer
        this.renderer = new THREE.WebGLRenderer({ 
            canvas: document.getElementById('ar-canvas'),
            antialias: true,
            alpha: true 
        });
        this.renderer.setPixelRatio(window.devicePixelRatio);
        this.renderer.setSize(window.innerWidth, window.innerHeight);
        this.renderer.xr.enabled = true;

        // Add lighting
        const light = new THREE.HemisphereLight(0xffffff, 0xbbbbff, 1);
        light.position.set(0.5, 1, 0.25);
        this.scene.add(light);

        // Create reticle (visual indicator for placement)
        const geometry = new THREE.RingGeometry(0.15, 0.2, 32).rotateX(-Math.PI / 2);
        const material = new THREE.MeshBasicMaterial({ color: 0x4CAF50, transparent: true, opacity: 0.5 });
        this.reticle = new THREE.Mesh(geometry, material);
        this.reticle.matrixAutoUpdate = false;
        this.reticle.visible = false;
        this.scene.add(this.reticle);

        // Setup controller
        this.controller = this.renderer.xr.getController(0);
        this.controller.addEventListener('select', () => this.onSelect());
        this.scene.add(this.controller);
    }

    async setupWebXRSession() {
        const sessionInit = {
            requiredFeatures: ['hit-test'],
            optionalFeatures: ['dom-overlay']
        };

        // Add dom overlay only if element exists
        const domOverlayRoot = document.getElementById('ar-ui');
        if (domOverlayRoot) {
            sessionInit.domOverlay = { root: domOverlayRoot };
        }

        try {
            this.session = await navigator.xr.requestSession('immersive-ar', sessionInit);
            this.renderer.xr.setSession(this.session);
            this.isARActive = true;

            // Setup session event listeners
            this.session.addEventListener('end', () => this.onSessionEnd());

            console.log('AR session started successfully');
        } catch (error) {
            // If hit-test fails, try without it
            try {
                const fallbackSessionInit = {
                    optionalFeatures: ['dom-overlay']
                };
                
                if (domOverlayRoot) {
                    fallbackSessionInit.domOverlay = { root: domOverlayRoot };
                }
                
                this.session = await navigator.xr.requestSession('immersive-ar', fallbackSessionInit);
                this.renderer.xr.setSession(this.session);
                this.isARActive = true;
                this.session.addEventListener('end', () => this.onSessionEnd());
                
                console.log('AR session started without hit-test');
                this.updateStatus('AR dimulai (mode terbatas)');
            } catch (fallbackError) {
                throw new Error(`Gagal memulai sesi AR: ${fallbackError.message}`);
            }
        }
    }

    render(timestamp, frame) {
        if (frame && this.isARActive) {
            const referenceSpace = this.renderer.xr.getReferenceSpace();
            const session = this.renderer.xr.getSession();

            // Request hit test source if not already requested
            if (!this.hitTestSourceRequested) {
                this.requestHitTestSource(session, referenceSpace);
            }

            // Handle hit test results
            if (this.hitTestSource) {
                const hitTestResults = frame.getHitTestResults(this.hitTestSource);
                this.handleHitTestResults(hitTestResults, referenceSpace);
            }
        }

        if (this.renderer && this.scene && this.camera && this.isARActive) {
            this.renderer.render(this.scene, this.camera);
        }
    }

    requestHitTestSource(session, referenceSpace) {
        // Use viewer space instead of local-floor to avoid NotSupportedError
        session.requestReferenceSpace('viewer').then((viewerSpace) => {
            session.requestHitTestSource({ space: viewerSpace })
                .then((source) => {
                    this.hitTestSource = source;
                    console.log('Hit test source created successfully');
                })
                .catch((error) => {
                    console.warn('Hit test source request failed, continuing without hit test:', error);
                    this.updateStatus('AR aktif - mode sederhana (tanpa deteksi permukaan)');
                });
        }).catch((error) => {
            console.warn('Viewer reference space request failed:', error);
            this.updateStatus('AR aktif - mode sederhana');
        });

        session.addEventListener('end', () => {
            this.hitTestSourceRequested = false;
            this.hitTestSource = null;
        });

        this.hitTestSourceRequested = true;
    }

    handleHitTestResults(hitTestResults, referenceSpace) {
        if (hitTestResults.length > 0) {
            const hit = hitTestResults[0];
            this.reticle.visible = true;
            
            try {
                this.reticle.matrix.fromArray(hit.getPose(referenceSpace).transform.matrix);
                // Update status when surface is detected
                this.updateStatus('Permukaan terdeteksi! Tap untuk menempatkan objek.');
            } catch (error) {
                console.warn('Error updating reticle position:', error);
                this.reticle.visible = false;
            }
        } else {
            this.reticle.visible = false;
            // Only show searching message if we have hit test capability
            if (this.hitTestSource) {
                this.updateStatus('Cari permukaan datar...');
            }
        }
    }

    onSelect() {
        if (this.reticle.visible && this.hitTestSource) {
            // Place object using hit test position
            const geometry = new THREE.BoxGeometry(0.1, 0.1, 0.1);
            const material = new THREE.MeshBasicMaterial({ color: 0x00ff00 });
            const cube = new THREE.Mesh(geometry, material);
            
            // Copy reticle position
            cube.position.setFromMatrixPosition(this.reticle.matrix);
            cube.quaternion.setFromRotationMatrix(this.reticle.matrix);
            
            this.scene.add(cube);
            this.updateStatus('Objek ditempatkan! Gunakan tombol "Objek Peninggalan" untuk info lebih lanjut.');
        } else {
            // Place object in front of camera as fallback
            const geometry = new THREE.BoxGeometry(0.1, 0.1, 0.1);
            const material = new THREE.MeshBasicMaterial({ color: 0x0000ff });
            const cube = new THREE.Mesh(geometry, material);
            
            // Position in front of camera
            cube.position.set(0, 0, -1);
            this.scene.add(cube);
            this.updateStatus('Objek ditempatkan di depan kamera! Gunakan tombol "Objek Peninggalan" untuk info lebih lanjut.');
        }
    }

    onSessionEnd() {
        this.isARActive = false;
        this.hitTestSource = null;
        this.hitTestSourceRequested = false;
        this.session = null;
        console.log('AR session ended');
    }

    updateStatus(message) {
        const statusElement = document.getElementById('status-message');
        if (statusElement) {
            statusElement.textContent = message;
        }
    }

    showError(message) {
        document.getElementById('loading-screen').style.display = 'none';
        document.getElementById('ar-container').style.display = 'none';
        document.getElementById('error-container').style.display = 'block';
        
        const errorTextElement = document.getElementById('error-text');
        if (errorTextElement) {
            errorTextElement.textContent = message;
        }
    }

    // Object selector panel functions
    openObjectSelector() {
        const panel = document.getElementById('object-selector');
        if (panel) {
            panel.style.bottom = '0';
        }
    }

    closeObjectSelector() {
        const panel = document.getElementById('object-selector');
        if (panel) {
            panel.style.bottom = '-100%';
        }
    }

    toggleObjectSelector() {
        const panel = document.getElementById('object-selector');
        if (panel) {
            const currentBottom = panel.style.bottom;
            if (currentBottom === '0px' || currentBottom === '0') {
                this.closeObjectSelector();
            } else {
                this.openObjectSelector();
            }
        }
    }

    goBack() {
        if (this.session && this.isARActive) {
            this.session.end().catch(console.warn);
        }
        window.history.back();
    }

    // Handle window resize
    onWindowResize() {
        if (this.camera && this.renderer) {
            this.camera.aspect = window.innerWidth / window.innerHeight;
            this.camera.updateProjectionMatrix();
            this.renderer.setSize(window.innerWidth, window.innerHeight);
        }
    }

    // Cleanup function
    destroy() {
        if (this.session) {
            this.session.end().catch(console.warn);
        }
        if (this.renderer) {
            this.renderer.setAnimationLoop(null);
            this.renderer.dispose();
        }
        this.isARActive = false;
    }
}

// Global AR instance
let arMuseumInstance = null;

// Check WebXR support and initialize
if ('xr' in navigator) {
    navigator.xr.isSessionSupported('immersive-ar').then((supported) => {
        if (supported) {
            // Hide AR not supported message if it exists
            const notSupportedEl = document.getElementById('ar-not-supported');
            if (notSupportedEl) {
                notSupportedEl.style.display = 'none';
            }
            
            // Initialize AR when page loads
            window.addEventListener('load', () => {
                // Small delay to ensure all resources are loaded
                setTimeout(() => {
                    const museumData = window.museumData || {};
                    arMuseumInstance = new ARMuseum(museumData);
                }, 1000);
            });
        } else {
            // Show not supported message
            const loadingScreen = document.getElementById('loading-screen');
            const errorContainer = document.getElementById('error-container');
            
            if (loadingScreen) loadingScreen.style.display = 'none';
            if (errorContainer) {
                errorContainer.style.display = 'block';
                const errorText = document.getElementById('error-text');
                if (errorText) {
                    errorText.textContent = 'AR tidak didukung di perangkat ini.';
                }
            }
        }
    }).catch(() => {
        // WebXR check failed
        const loadingScreen = document.getElementById('loading-screen');
        const errorContainer = document.getElementById('error-container');
        
        if (loadingScreen) loadingScreen.style.display = 'none';
        if (errorContainer) {
            errorContainer.style.display = 'block';
            const errorText = document.getElementById('error-text');
            if (errorText) {
                errorText.textContent = 'Gagal mengecek dukungan WebXR.';
            }
        }
    });
} else {
    // WebXR not available
    const loadingScreen = document.getElementById('loading-screen');
    const errorContainer = document.getElementById('error-container');
    
    if (loadingScreen) loadingScreen.style.display = 'none';
    if (errorContainer) {
        errorContainer.style.display = 'block';
        const errorText = document.getElementById('error-text');
        if (errorText) {
            errorText.textContent = 'WebXR tidak tersedia di browser ini.';
        }
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    // Back button
    const backButton = document.getElementById('back-button');
    if (backButton) {
        backButton.addEventListener('click', () => {
            if (arMuseumInstance) {
                arMuseumInstance.goBack();
            } else {
                window.history.back();
            }
        });
    }

    // Object toggle button
    const objectToggleButton = document.getElementById('object-toggle-button');
    if (objectToggleButton) {
        objectToggleButton.addEventListener('click', () => {
            if (arMuseumInstance) {
                arMuseumInstance.toggleObjectSelector();
            }
        });
    }

    // Close button for object selector
    window.closeObjectSelector = () => {
        if (arMuseumInstance) {
            arMuseumInstance.closeObjectSelector();
        }
    };

    // Go back function for error page
    window.goBack = () => {
        if (arMuseumInstance) {
            arMuseumInstance.goBack();
        } else {
            window.history.back();
        }
    };
});

// Window resize handler
window.addEventListener('resize', () => {
    if (arMuseumInstance) {
        arMuseumInstance.onWindowResize();
    }
});

// Handle page visibility change
document.addEventListener('visibilitychange', () => {
    if (document.hidden && arMuseumInstance && arMuseumInstance.session) {
        arMuseumInstance.session.end();
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (arMuseumInstance) {
        arMuseumInstance.destroy();
    }
});
