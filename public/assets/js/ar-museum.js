import * as THREE from "three";
import { GLTFLoader } from "three/jsm/loaders/GLTFLoader.js";
import { DRACOLoader } from "three/jsm/loaders/DRACOLoader.js";
import { ARButton } from "three/jsm/webxr/ARButton.js";

let qrGenerated = false;

function hideElement(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add("hidden");
    el.style.display = "none";
    el.style.pointerEvents = "none";
}

function showElement(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove("hidden", "invisible");
    el.style.display = "";
    el.style.visibility = "visible";
    el.style.pointerEvents = "auto";
}

function getActiveCamera(renderer, fallbackCamera) {
    if (!renderer?.xr) {
        return fallbackCamera;
    }

    const xrCamera = renderer.xr.getCamera(fallbackCamera);
    return xrCamera || fallbackCamera;
}

async function generateQRCode(text) {
    const container = document.getElementById("qr-code");
    if (!container) return;
    container.innerHTML = "";

    const copyInput = document.getElementById("ar-share-url");
    if (copyInput) {
        copyInput.value = text;
    }

    if (typeof QRCode !== "undefined") {
        new QRCode(container, {
            text: text,
            width: 160,
            height: 160,
            colorDark: "#111827",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M,
        });
    } else {
        container.innerHTML = `<a href="${text}" target="_blank" class="text-xs text-primary underline break-all">${text}</a>`;
    }
    qrGenerated = true;
    showToaster("QR berhasil dibuat");
}

async function generateLaunchCode() {
    if (qrGenerated) return;

    let cleanUrl = window.location.origin + window.location.pathname;
    let targetUrl = cleanUrl + (typeof arToken !== "undefined" && arToken ? "?arToken=" + arToken : "");
    let launchUrl = targetUrl;

    if (typeof VLaunch !== "undefined" && typeof VLaunch.getLaunchUrl === "function") {
        try {
            launchUrl = await VLaunch.getLaunchUrl(targetUrl);
            console.log("VLaunch Launch URL:", launchUrl);
        } catch (err) {
            console.warn("VLaunch getLaunchUrl error, fallback ke target URL:", err);
            launchUrl = targetUrl;
        }
    }

    await generateQRCode(launchUrl);
}

// Inisialisasi QR Code / Launch Code
window.addEventListener("vlaunch-initialized", () => {
    generateLaunchCode();
});

if (typeof VLaunch !== "undefined" && VLaunch.initialized) {
    generateLaunchCode();
} else {
    // Fallback cepat jika VLaunch tidak terinisialisasi dalam 2.5 detik
    setTimeout(() => {
        if (!qrGenerated) {
            let cleanUrl = window.location.origin + window.location.pathname;
            let targetUrl = cleanUrl + (typeof arToken !== "undefined" && arToken ? "?arToken=" + arToken : "");
            generateQRCode(targetUrl);
        }
    }, 2500);
}

// WebXR Support Check
async function checkWebXRSupport() {
    if (!("xr" in navigator) || !navigator.xr) {
        return false;
    }
    if (typeof navigator.xr.isSessionSupported !== "function") {
        return false;
    }
    try {
        return await navigator.xr.isSessionSupported("immersive-ar");
    } catch (err) {
        console.warn("WebXR isSessionSupported error:", err);
        return false;
    }
}

function arNotSupported() {
    showElement("ar-not-supported");
    hideElement("ar-button-container");
    hideElement("instructions");
    hideElement("expand-bottom-sheet");
    hideElement("loading-container");
}

checkWebXRSupport().then((supported) => {
    if (supported) {
        hideElement("ar-not-supported");
        main();
    } else {
        arNotSupported();
    }
});

// Setup tombol salin tautan
document.addEventListener("DOMContentLoaded", () => {
    const btnCopy = document.getElementById("btn-copy-ar-link");
    const inputUrl = document.getElementById("ar-share-url");
    const copyLabel = document.getElementById("copy-btn-label");

    if (btnCopy && inputUrl) {
        btnCopy.addEventListener("click", async () => {
            const url = inputUrl.value || window.location.href;
            try {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(url);
                } else {
                    inputUrl.select();
                    document.execCommand("copy");
                }
                if (copyLabel) {
                    const original = copyLabel.textContent;
                    copyLabel.textContent = "✓ Disalin!";
                    setTimeout(() => {
                        copyLabel.textContent = original;
                    }, 2000);
                }
            } catch (err) {
                console.warn("Gagal menyalin tautan:", err);
            }
        });
    }
});

class SceneManager {
    constructor(renderer) {
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(
            70,
            window.innerWidth / window.innerHeight,
            0.01,
            1000,
        );

        this.reticle = new THREE.Mesh(
            new THREE.RingGeometry(0.15, 0.2, 32).rotateX(-Math.PI / 2),
            new THREE.MeshBasicMaterial({
                color: 0xffffff,
                polygonOffset: true,
                polygonOffsetFactor: -1,
                polygonOffsetUnits: -1,
            }),
        );
        this.reticle.matrixAutoUpdate = false;
        this.reticle.frustumCulled = false;
        this.reticle.visible = false;
        this.scene.add(this.reticle);

        /**
         * The model to be placed in the scene.
         * @type {THREE.Object3D | null}
         */
        this.model = null;
        this.planeFound = false;
        this.placed = false;
        this.skybox = null;

        // Add hemisphere light (ambient light from sky and ground)
        const hemisphereLight = new THREE.HemisphereLight(
            0xffffff,
            0xbbbbff,
            0.3,
        );
        hemisphereLight.position.set(0.5, 1, 0.25);
        this.scene.add(hemisphereLight);

        // Add directional light (sunlight)
        const sunLight = new THREE.DirectionalLight(0xffffff, 1);
        sunLight.position.set(5, 10, 7.5);
        sunLight.castShadow = true;

        // Configure shadow properties for better quality
        sunLight.shadow.mapSize.width = 1024;
        sunLight.shadow.mapSize.height = 1024;
        sunLight.shadow.camera.near = 0.5;
        sunLight.shadow.camera.far = 50;
        sunLight.shadow.bias = -0.001;

        this.scene.add(sunLight);
        this.sunLight = sunLight;

        this.controller = renderer.xr.getController(0);
        this.scene.add(this.controller);

        window.addEventListener("resize", this.onWindowResize.bind(this));
    }

    setOnSelect(onSelect) {
        this.controller.addEventListener("select", () => {
            if (this.reticle.visible) {
                onSelect(this.reticle.matrix);
            }
        });
    }

    onWindowResize() {
        console.log("Resized");
        this.camera.aspect = window.innerWidth / window.innerHeight;
        this.camera.updateProjectionMatrix();
    }

    createSkybox() {
        // Load sky texture for environment reflections and sky dome
        const textureLoader = new THREE.TextureLoader();

        const texture = textureLoader.load("/images/hdri/langit.jpg", () => {
            console.log("Skybox texture loaded");
            showToaster("Skybox texture loaded");
        });

        texture.mapping = THREE.EquirectangularReflectionMapping;
        texture.colorSpace = THREE.SRGBColorSpace;

        // Set the scene's environment map for reflections on the model
        this.scene.environment = texture;

        // Create sky dome with depthTest and depthWrite false so it renders purely as a background
        // without occluding or clipping any 3D model geometry or Z-fighting with depth buffer
        const skyGeometry = new THREE.SphereGeometry(500, 32, 20);
        const skyMaterial = new THREE.MeshBasicMaterial({
            map: texture,
            side: THREE.BackSide,
            depthWrite: false,
            depthTest: false,
            toneMapped: false,
        });

        this.skybox = new THREE.Mesh(skyGeometry, skyMaterial);
        this.skybox.frustumCulled = false;
        this.skybox.renderOrder = -999;
        this.skybox.visible = false; // Initially hidden until placement
        this.scene.add(this.skybox);

        return this.skybox;
    }
}

/**
 * Displays a toaster notification with the given message.
 *
 * @param {string} message - The message to display in the toaster.
 */
function showToaster(message) {
    console.log(message);
    // const toasterContainer = document.getElementById("toaster-container");
    // if (!toasterContainer) {
    //     console.warn("Toaster container not found");
    //     return;
    // }
    // const toaster = document.createElement("div");
    // toaster.className = "toaster";
    // toaster.innerText = message;
    // toasterContainer.appendChild(toaster);
    // setTimeout(() => {
    //     if (toaster && toaster.parentNode) {
    //         toaster.remove();
    //     }
    // }, 5000);
}

/**
 * Manages the rendering process and XR session for the application.
 */
class RendererManager {
    /**
     * Creates an instance of RendererManager.
     * Initializes the WebGLRenderer and sets up XR session event listeners.
     */
    constructor() {
        this.renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true,
        });
        this.onSessionStarts = [];
        this.renderer.setPixelRatio(window.devicePixelRatio);
        this.renderer.setSize(window.innerWidth, window.innerHeight);
        this.renderer.xr.enabled = true;

        // Enable shadows
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;

        document.body.appendChild(this.renderer.domElement);

        this.hitTestSource = null;
        this.hitTestSourceRequested = false;
        this.hitTestMissedFrames = 0;
        this.maxHitTestMissedFrames = 6;

        this.renderer.xr.addEventListener(
            "sessionstart",
            this.onSessionStart.bind(this),
        );
        this.renderer.xr.addEventListener(
            "sessionend",
            this.onSessionEnd.bind(this),
        );
    }

    /**
     * Handles the start of an XR session.
     * Displays the tracking prompt.
     */
    onSessionStart() {
        const trackingPrompt = document.getElementById("tracking-prompt");
        const expandBottomSheet = document.getElementById(
            "expand-bottom-sheet",
        );
        const arButtonContainer = document.getElementById(
            "ar-button-container",
        );

        if (trackingPrompt) {
            trackingPrompt.style.display = "flex";
            trackingPrompt.classList.remove("invisible");
        }
        if (expandBottomSheet) {
            expandBottomSheet.classList.add("hidden");
            expandBottomSheet.style.display = "none";
        }
        if (arButtonContainer) {
            arButtonContainer.style.display = "none";
        }

        for (const callback of this.onSessionStarts) {
            callback();
        }
    }

    /**
     * Handles the end of an XR session (e.g. back button pressed).
     * Resets UI and scene state.
     */
    onSessionEnd() {
        this.stopHitTestSource();
        this.hitTestMissedFrames = 0;

        hideElement("instructions");
        hideElement("tracking-prompt");

        const expandBottomSheet = document.getElementById("expand-bottom-sheet");
        if (expandBottomSheet) {
            expandBottomSheet.classList.add("hidden");
            expandBottomSheet.style.display = "none";
            expandBottomSheet.classList.remove("opacity-0", "pointer-events-none");
        }

        const bottomSheet = document.getElementById("bottom-sheet");
        if (bottomSheet) {
            bottomSheet.classList.add("translate-y-full");
            bottomSheet.classList.remove("translate-y-0");
        }

        showElement("app");

        const arButtonContainer = document.getElementById("ar-button-container");
        if (arButtonContainer) {
            arButtonContainer.style.display = "block";
        }

        if (this.sceneManager) {
            this.sceneManager.placed = false;
            this.sceneManager.planeFound = false;
            if (this.sceneManager.model) {
                this.sceneManager.model.visible = false;
            }
            if (this.sceneManager.skybox) {
                this.sceneManager.skybox.visible = false;
            }
            if (this.sceneManager.reticle) {
                this.sceneManager.reticle.visible = false;
                if (!this.sceneManager.scene.children.includes(this.sceneManager.reticle)) {
                    this.sceneManager.scene.add(this.sceneManager.reticle);
                }
            }
        }
    }

    /**
     * Starts the animation loop for rendering.
     * @param {Object} sceneManager - The scene manager containing the scene and camera.
     */
    animate(sceneManager) {
        this.sceneManager = sceneManager;
        this.renderer.setAnimationLoop((timestamp, frame) =>
            this.render(timestamp, frame, sceneManager),
        );
    }

    /**
     * Renders the scene for each frame.
     * Handles hit test source requests and hit test results.
     * @param {number} timestamp - The current timestamp.
     * @param {XRFrame} frame - The current XR frame.
     * @param {Object} sceneManager - The scene manager containing the scene and camera.
     */
    render(timestamp, frame, sceneManager) {
        if (frame) {
            const referenceSpace = this.renderer.xr.getReferenceSpace();
            const session = this.renderer.xr.getSession();

            if (!sceneManager.placed) {
                if (!this.hitTestSourceRequested) {
                    this.requestHitTestSource(session, referenceSpace);
                }

                if (this.hitTestSource) {
                    const hitTestResults = frame.getHitTestResults(
                        this.hitTestSource,
                    );
                    this.handleHitTestResults(
                        hitTestResults,
                        referenceSpace,
                        sceneManager,
                    );
                }
            } else if (this.hitTestSource) {
                this.stopHitTestSource();
            }

            // Update skybox position to follow the camera if it exists and is visible
            if (
                sceneManager.skybox &&
                sceneManager.skybox.visible &&
                sceneManager.placed
            ) {
                const xrCam = this.renderer.xr.getCamera();
                if (xrCam) {
                    xrCam.getWorldPosition(sceneManager.skybox.position);
                } else {
                    sceneManager.camera.getWorldPosition(sceneManager.skybox.position);
                }
            }
        }

        // Render using the application camera.
        // Three.js WebXRManager automatically manages the XR camera internally during active XR sessions.
        this.renderer.render(sceneManager.scene, sceneManager.camera);
    }

    /**
     * Requests a hit test source for the XR session.
     * @param {XRSession} session - The current XR session.
     * @param {XRReferenceSpace} referenceSpace - The reference space for the XR session.
     */
    requestHitTestSource(session, referenceSpace) {
        if (!session) return;
        session
            .requestReferenceSpace("viewer")
            .then((viewerSpace) => {
                return session.requestHitTestSource({ space: viewerSpace });
            })
            .then((source) => {
                this.hitTestSource = source;
            })
            .catch((err) => {
                console.warn("Hit test request error:", err);
            });

        session.addEventListener("end", () => {
            this.stopHitTestSource();
        });

        this.hitTestSourceRequested = true;
    }

    /**
     * Cancels the active hit test source to save resources once placement is complete.
     */
    stopHitTestSource() {
        if (this.hitTestSource) {
            if (typeof this.hitTestSource.cancel === "function") {
                this.hitTestSource.cancel();
            }
            this.hitTestSource = null;
        }
        this.hitTestSourceRequested = false;
    }

    /**
     * Handles the results of a hit test.
     * Updates the visibility and position of the reticle based on hit test results.
     * @param {Array<XRHitTestResult>} hitTestResults - The results of the hit test.
     * @param {XRReferenceSpace} referenceSpace - The reference space for the XR session.
     * @param {Object} sceneManager - The scene manager containing the scene and camera.
     */
    handleHitTestResults(hitTestResults, referenceSpace, sceneManager) {
        if (sceneManager.placed) {
            sceneManager.reticle.visible = false;
            if (sceneManager.scene.children.includes(sceneManager.reticle)) {
                sceneManager.scene.remove(sceneManager.reticle);
            }
            return;
        }

        if (hitTestResults.length > 0) {
            this.hitTestMissedFrames = 0;

            if (!sceneManager.planeFound) {
                sceneManager.planeFound = true;
                hideElement("tracking-prompt");
                showElement("instructions");
            }

            const hit = hitTestResults[0];
            const pose = hit.getPose(referenceSpace);
            if (pose) {
                sceneManager.reticle.visible = true;
                sceneManager.reticle.matrix.fromArray(pose.transform.matrix);
                sceneManager.reticle.matrixWorldNeedsUpdate = true;
                sceneManager.reticle.updateMatrixWorld(true);
            }
        } else {
            this.hitTestMissedFrames = (this.hitTestMissedFrames || 0) + 1;
            // Retain reticle for a few missed frames to prevent rapid blinking/flickering
            if (this.hitTestMissedFrames > (this.maxHitTestMissedFrames || 6)) {
                sceneManager.reticle.visible = false;
            }
        }
    }
}

class ModelLoader {
    static loader = new GLTFLoader();
    static dracoLoader = new DRACOLoader();

    static async loadModel(name, onProgress) {
        this.dracoLoader.setDecoderConfig({ type: "js" });
        this.dracoLoader.setDecoderPath(
            "https://www.gstatic.com/draco/v1/decoders/",
        );
        this.loader.setDRACOLoader(this.dracoLoader);
        const model = await this.loader.loadAsync(name, onProgress);

        const rootScene = model.scenes[0];
        rootScene.frustumCulled = false;

        // Enable shadows and disable frustum culling on all child meshes
        // to prevent parts of the model from blinking/disappearing in AR
        rootScene.traverse((object) => {
            if (object.isMesh) {
                object.castShadow = true;
                object.receiveShadow = true;
                object.frustumCulled = false;
            }
        });

        return rootScene;
    }
}

function createARButton(renderer) {
    if (!document.querySelector("#ar-button-container button")) {
        document.querySelector("#ar-button-container").appendChild(
            ARButton.createButton(renderer, {
                requiredFeatures: ["local", "hit-test", "dom-overlay"],
                domOverlay: { root: document.querySelector("#overlay") },
            }),
        );
    }
}

async function requestSensorPermission() {
    try {
        if (
            typeof DeviceMotionEvent !== "undefined" &&
            typeof DeviceMotionEvent.requestPermission === "function"
        ) {
            const response = await DeviceMotionEvent.requestPermission();
            console.log("Motion permission:", response);
        }
        if (
            typeof DeviceOrientationEvent !== "undefined" &&
            typeof DeviceOrientationEvent.requestPermission === "function"
        ) {
            const response = await DeviceOrientationEvent.requestPermission();
            console.log("Orientation permission:", response);
        }
    } catch (err) {
        console.warn("Sensor permission request failed:", err);
    }
}

async function checkSensors() {
    return new Promise((resolve) => {
        const sensors = {
            accelerometer: false,
            gyro: false,
            orientation: false,
            magnetometer: false, // hampir selalu false di web
        };

        let checks = 0;
        let timeout = null;

        function done() {
            clearTimeout(timeout);
            resolve(sensors);
        }

        function motionHandler(event) {
            if (
                event.acceleration &&
                (event.acceleration.x !== null ||
                    event.acceleration.y !== null ||
                    event.acceleration.z !== null)
            ) {
                sensors.accelerometer = true;
            }
            if (
                event.rotationRate &&
                (event.rotationRate.alpha !== null ||
                    event.rotationRate.beta !== null ||
                    event.rotationRate.gamma !== null)
            ) {
                sensors.gyro = true;
            }
            checks++;
            if (checks >= 2) done();
            window.removeEventListener("devicemotion", motionHandler);
        }

        function orientationHandler(event) {
            if (
                event.alpha !== null ||
                event.beta !== null ||
                event.gamma !== null
            ) {
                sensors.orientation = true;
            }
            checks++;
            if (checks >= 2) done();
            window.removeEventListener("deviceorientation", orientationHandler);
        }

        window.addEventListener("devicemotion", motionHandler);
        window.addEventListener("deviceorientation", orientationHandler);

        // fallback kalau event tidak pernah muncul
        timeout = setTimeout(done, 2000);
    });
}

async function main() {
    showToaster("Initializing AR...");

    await requestSensorPermission();

    hideElement("ar-not-supported");
    const rendererManager = new RendererManager();
    const sceneManager = new SceneManager(rendererManager.renderer);

    showToaster("Loading model...");
    showToaster(
        "File size: " + (museum.file_size / 1024 / 1024).toFixed(2) + " MB",
    );
    const percentEl = document.getElementById("loading-percentage");
    const model = await ModelLoader.loadModel(
        "/storage/" + museum.path_obj,
        (event) => {
            const fileSize = event.total || museum.file_size || 43445936; // Use server-provided size as fallback
            let progress = (event.loaded / fileSize) * 100;
            progress = Math.min(progress, 100); // Ensure progress does not exceed 100%
            showElement("loading-container");
            const loadingBar = document.getElementById("loading-bar");
            if (loadingBar) {
                loadingBar.style.width = `${progress}%`;
            }
            if (percentEl) {
                percentEl.textContent = `${Math.round(progress)}%`;
            }
        },
    );

    hideElement("loading-container");

    showToaster("Model loaded, creating AR button");
    createARButton(rendererManager.renderer);

    showToaster("Adding model to scene");
    model.visible = false;
    model.frustumCulled = false;
    sceneManager.scene.add(model);
    model.updateMatrixWorld(true);
    sceneManager.model = model;

    // Initialize environment reflections
    showToaster("Preparing environment");
    const skybox = sceneManager.createSkybox();

    sceneManager.setOnSelect((matrix) => {
        if (model.visible) return;
        // document.getElementById("app").style.visibility = "hidden";
        // document.getElementById("instructions").style.visibility = "hidden";
        hideElement("app");
        hideElement("instructions");

        showToaster("Placing model");
        showToaster("Position: " + model.position);
        showToaster("Quaternion: " + model.quaternion);
        showToaster("Scale: " + model.scale);
        matrix.decompose(model.position, model.quaternion, model.scale);

        const targetPosition = new THREE.Vector3();
        const activeCamera = getActiveCamera(
            rendererManager.renderer,
            sceneManager.camera,
        );
        activeCamera.getWorldPosition(targetPosition);

        const direction = new THREE.Vector3();
        direction.subVectors(targetPosition, model.position);
        direction.y = 0;
        if (direction.lengthSq() > 0.0001) {
            model.lookAt(direction.add(model.position));
        }
        model.visible = true;
        model.updateMatrixWorld(true);

        // Position and reveal the skybox upon placement
        if (skybox) {
            skybox.position.copy(model.position);
            skybox.visible = true;
            showToaster("Environment loaded");
        }

        // Update the sun light to cast shadows from the correct angle
        if (sceneManager.sunLight) {
            // Position the sun light relative to the model
            const lightOffset = new THREE.Vector3(15, 20, 10);
            sceneManager.sunLight.position
                .copy(model.position)
                .add(lightOffset);
            sceneManager.sunLight.target = model;

            // Make sure the target is part of the scene for the directional light to work properly
            if (
                !sceneManager.scene.children.includes(
                    sceneManager.sunLight.target,
                )
            ) {
                sceneManager.scene.add(sceneManager.sunLight.target);
            }

            showToaster("Sunlight positioned");
        }

        // Hide reticle and completely remove it from the scene graph
        sceneManager.reticle.visible = false;
        if (sceneManager.scene.children.includes(sceneManager.reticle)) {
            sceneManager.scene.remove(sceneManager.reticle);
        }
        sceneManager.placed = true;
        rendererManager.stopHitTestSource();

        // Reveal the "Lihat Objek Peninggalan" button in AR
        const expandBottomSheet = document.getElementById("expand-bottom-sheet");
        if (expandBottomSheet) {
            expandBottomSheet.classList.remove("hidden");
            expandBottomSheet.style.display = "inline-flex";
            expandBottomSheet.style.visibility = "visible";
            expandBottomSheet.style.pointerEvents = "auto";
        }
    });

    showToaster("Starting animation loop");

    rendererManager.animate(sceneManager);
}
