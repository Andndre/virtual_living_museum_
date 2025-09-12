import * as THREE from "three";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader.js";
import { ARButton } from "three/examples/jsm/webxr/ARButton.js";
import { EffectComposer, RenderPass, SSAOEffect } from "postprocessing";

let initialized = false;

window.addEventListener("vlaunch-initialized", (e) => {
    initialized = true;
    document.getElementById("qr-code").innerHTML = "";
    generateLaunchCode();
});

if (VLaunch.initialized) {
    generateLaunchCode(); // generate a Launch Code for this url
} else {
    generateQRCode(window.location.href); // generate regular QR code for this url
}

async function generateQRCode(text) {
    new QRCode("qr-code", {
        text: text,
        width: 128,
        height: 128,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H,
    });
}

async function generateLaunchCode() {
    let url = await VLaunch.getLaunchUrl(
        window.location.href + "?arToken=" + arToken,
    );
    console.log(url);

    await generateQRCode(url);
    showToaster("QR berhasil dibuat");
}

class SceneManager {
    constructor(renderer) {
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(
            70,
            window.innerWidth / window.innerHeight,
            0.01,
            999,
        );

        this.composer = new EffectComposer(renderer);
        const renderPass = new RenderPass(this.scene, this.camera);
        this.composer.addPass(renderPass);

        // Ssao effect
        const ssaoEffect = new SSAOEffect(this.camera);

        this.composer.addPass(ssaoEffect);

        this.reticle = new THREE.Mesh(
            new THREE.RingGeometry(0.15, 0.2, 32).rotateX(-Math.PI / 2),
            new THREE.MeshBasicMaterial(),
        );
        this.reticle.matrixAutoUpdate = false;
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
            if (this.reticle.visible) onSelect(this.reticle.matrix);
        });
    }

    onWindowResize() {
        console.log("Resized");
        this.camera.aspect = window.innerWidth / window.innerHeight;
        this.camera.updateProjectionMatrix();
    }

    createSkybox() {
        // Create a skybox using a spherical environment
        const textureLoader = new THREE.TextureLoader();

        // Load sky texture - we'll use a simple equirectangular texture
        // This can be replaced with a more suitable texture for the museum context
        const texture = textureLoader.load("/images/hdri/langit.jpg", () => {
            console.log("Skybox texture loaded");
            showToaster("Skybox texture loaded");
        });

        texture.mapping = THREE.EquirectangularReflectionMapping;
        texture.colorSpace = THREE.SRGBColorSpace;

        // Create a large sphere for the skybox
        const skyGeometry = new THREE.SphereGeometry(500, 60, 40);
        // Flip the geometry inside out
        skyGeometry.scale(-1, 1, 1);

        const skyMaterial = new THREE.MeshBasicMaterial({
            map: texture,
        });

        this.skybox = new THREE.Mesh(skyGeometry, skyMaterial);
        this.skybox.visible = false; // Initially hidden
        this.scene.add(this.skybox);

        // Set the scene's environment map for reflections on the model
        this.scene.environment = texture;

        return this.skybox;
    }
}

/**
 * Displays a toaster notification with the given message.
 *
 * @param {string} message - The message to display in the toaster.
 */
function showToaster(message) {
    // console.log(message);
    const toasterContainer = document.getElementById("toaster-container");
    const toaster = document.createElement("div");
    toaster.className = "toaster";
    toaster.innerText = message;
    toasterContainer.appendChild(toaster);
    setTimeout(() => {
        toaster.remove();
    }, 3000);
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

        this.renderer.xr.addEventListener(
            "sessionstart",
            this.onSessionStart.bind(this),
        );
    }

    /**
     * Handles the start of an XR session.
     * Displays the tracking prompt.
     */
    onSessionStart() {
        document.getElementById("tracking-prompt").style.display = "block";
        document.getElementById("expand-bottom-sheet").style.display = "block";
        // hilangkan tombol #ar-button-container #ARButton
        document.getElementById("ar-button-container").style.display = "none";
        for (const callback of this.onSessionStarts) {
            callback();
        }
    }

    /**
     * Starts the animation loop for rendering.
     * @param {Object} sceneManager - The scene manager containing the scene and camera.
     */
    animate(sceneManager) {
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

            // Update skybox position to follow the camera if it exists and is visible
            if (
                sceneManager.skybox &&
                sceneManager.skybox.visible &&
                sceneManager.placed
            ) {
                const cameraPosition = new THREE.Vector3();
                sceneManager.camera.getWorldPosition(cameraPosition);
                sceneManager.skybox.position.copy(cameraPosition);
            }
        }

        this.renderer.render(sceneManager.scene, sceneManager.camera);
    }

    /**
     * Requests a hit test source for the XR session.
     * @param {XRSession} session - The current XR session.
     * @param {XRReferenceSpace} referenceSpace - The reference space for the XR session.
     */
    requestHitTestSource(session, referenceSpace) {
        session.requestReferenceSpace("viewer").then((viewerSpace) => {
            session
                .requestHitTestSource({ space: viewerSpace })
                .then((source) => {
                    this.hitTestSource = source;
                });
        });

        session.addEventListener("end", () => {
            this.hitTestSourceRequested = false;
            this.hitTestSource = null;
        });

        this.hitTestSourceRequested = true;
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
            return;
        }
        if (hitTestResults.length > 0) {
            if (!sceneManager.planeFound) {
                sceneManager.reticle.visible = false;
                document.getElementById("tracking-prompt").style.display =
                    "none";
                console.log("Plane found");
                document.getElementById("instructions").style.display = "block";
            }

            const hit = hitTestResults[0];
            sceneManager.reticle.visible = true;
            sceneManager.reticle.matrix.fromArray(
                hit.getPose(referenceSpace).transform.matrix,
            );
        } else {
            sceneManager.reticle.visible = false;
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

        // Enable shadows on the model
        model.scenes[0].traverse((object) => {
            if (object.isMesh) {
                object.castShadow = true;
                object.receiveShadow = true;
            }
        });

        return model.scenes[0];
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

async function checkSensors() {
    let sensors = {
        accelerometer: false,
        gyroscope: false,
        orientation: false,
        magnetometer: false,
    };

    return new Promise((resolve) => {
        function handleMotion(event) {
            if (event.acceleration || event.accelerationIncludingGravity) {
                sensors.accelerometer = true;
            }
            window.removeEventListener("devicemotion", handleMotion);
            maybeDone();
        }

        function handleOrientation(event) {
            if (
                event.alpha !== null ||
                event.beta !== null ||
                event.gamma !== null
            ) {
                sensors.gyroscope = true;
                sensors.orientation = true;
            }
            window.removeEventListener("deviceorientation", handleOrientation);
            maybeDone();
        }

        let timeout = setTimeout(() => resolve(sensors), 2000);

        function maybeDone() {
            clearTimeout(timeout);
            resolve(sensors);
        }

        window.addEventListener("devicemotion", handleMotion, { once: true });
        window.addEventListener("deviceorientation", handleOrientation, {
            once: true,
        });
    });
}

async function main() {
    showToaster("Initializing AR...");

    const sensors = await checkSensors();
    const debugInfo = document.getElementById("debug-info");
    debugInfo.innerHTML = JSON.stringify(sensors, null, 2);

    document.getElementById("ar-not-supported").style.display = "none";
    const rendererManager = new RendererManager();
    const sceneManager = new SceneManager(rendererManager.renderer);

    showToaster("Loading model...");
    const model = await ModelLoader.loadModel(
        "/storage/" + museum.path_obj,
        (event) => {
            const fileSize = event.total || museum.file_size || 43445936; // Use server-provided size as fallback
            let progress = (event.loaded / fileSize) * 100;
            console.log(event.loaded, fileSize, progress);
            progress = Math.min(progress, 100); // Ensure progress does not exceed 100%
            document.getElementById("loading-container").style.display =
                "block";
            document.getElementById("loading-bar").style.width = `${progress}%`;
            showToaster(`Loading progress: ${progress}%`);
        },
    );

    document.getElementById("loading-container").style.display = "none";

    showToaster("Model loaded, creating AR button");
    createARButton(rendererManager.renderer);

    showToaster("Adding model to scene");
    model.visible = false;
    sceneManager.scene.add(model);
    model.updateMatrixWorld(true);
    sceneManager.model = model;

    // Create the skybox
    showToaster("Preparing environment");
    const skybox = sceneManager.createSkybox();

    sceneManager.setOnSelect((matrix) => {
        if (model.visible) return;
        document.getElementById("app").style.display = "none";
        document.getElementById("instructions").style.display = "none";
        showToaster("Placing model");
        showToaster("Position: " + model.position);
        showToaster("Quaternion: " + model.quaternion);
        showToaster("Scale: " + model.scale);
        matrix.decompose(model.position, model.quaternion, model.scale);

        const targetPosition = new THREE.Vector3();
        sceneManager.camera.getWorldPosition(targetPosition);

        const direction = new THREE.Vector3();
        direction.subVectors(targetPosition, model.position);
        direction.y = 0;
        model.lookAt(direction.add(model.position));
        model.visible = true;

        // Position the skybox at the model position to ensure proper alignment
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

        sceneManager.reticle.visible = false;
        sceneManager.placed = true;
    });

    showToaster("Starting animation loop");

    rendererManager.animate(sceneManager);
}

if ("xr" in navigator) {
    navigator.xr.isSessionSupported("immersive-ar").then((supported) => {
        if (supported) {
            showToaster("Supported");
            //hide "ar-not-supported"
            document.getElementById("ar-not-supported").style.display = "none";
            main();
        } else {
            arNotSupported();
        }
    });
} else {
    arNotSupported();
}

function arNotSupported() {
    document.getElementById("ar-not-supported").style.display = "block";
    document.getElementById("ar-button-container").style.display = "none";
    document.getElementById("instructions").style.display = "none";
    document.getElementById("expand-bottom-sheet").style.display = "none";
    document.getElementById("loading-container").style.display = "none";
}
