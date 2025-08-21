import * as THREE from "three";
import { GLTFLoader } from "three/jsm/loaders/GLTFLoader.js";
import { DRACOLoader } from "three/jsm/loaders/DRACOLoader.js";
import { ARButton } from "three/jsm/webxr/ARButton.js";

let initialized = false;

window.addEventListener("vlaunch-initialized", (e) => {
    initialized = true;
    document.getElementById("qr-code").innerHTML = "";
    generateLaunchCode();
});

if (VLaunch.initialized) {
    document.getElementById("qr-code").innerHTML = "";
    generateLaunchCode();
} else {
    setTimeout(() => {
        if (!initialized) {
            document.getElementById("qr-code").innerHTML =
                "Web XR tidak didukung di Variant Launch Anda";
            generateQRCode(window.location.href);
        }
    }, 10000);
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
    let url = await VLaunch.getLaunchUrl(window.location.href);

    await generateQRCode(url);
    showToaster("QR berhasil dibuat");
    console.log("Launch Code Generated");
}

const planes = [
    "lukisan-1",
    "lukisan-2",
    "lukisan-3",
    "lukisan-4",
    "lukisan-5",
];

const lukisanFrames = [
    "lukisan-1-frame",
    "lukisan-2-frame",
    "lukisan-3-frame",
    "lukisan-4-frame",
    "lukisan-5-frame",
];

class SceneManager {
    constructor(renderer) {
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(
            70,
            window.innerWidth / window.innerHeight,
            0.01,
            999
        );

        this.reticle = new THREE.Mesh(
            new THREE.RingGeometry(0.15, 0.2, 32).rotateX(-Math.PI / 2),
            new THREE.MeshBasicMaterial()
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

        const light = new THREE.HemisphereLight(0xffffff, 0xbbbbff, 1);
        light.position.set(0.5, 1, 0.25);
        this.scene.add(light);

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
}

/**
 * Displays a toaster notification with the given message.
 *
 * @param {string} message - The message to display in the toaster.
 */
function showToaster(message) {
    console.log(message);
    // const toasterContainer = document.getElementById("toaster-container");
    // const toaster = document.createElement("div");
    // toaster.className = "toaster";
    // toaster.innerText = message;
    // toasterContainer.appendChild(toaster);

    // setTimeout(() => {
    //     toaster.remove();
    // }, 3000);
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
        document.body.appendChild(this.renderer.domElement);

        this.hitTestSource = null;
        this.hitTestSourceRequested = false;

        this.renderer.xr.addEventListener(
            "sessionstart",
            this.onSessionStart.bind(this)
        );
    }

    /**
     * Handles the start of an XR session.
     * Displays the tracking prompt.
     */
    onSessionStart() {
        document.getElementById("tracking-prompt").style.display = "block";
        document.getElementById("expand-bottom-sheet").style.display = "block";
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
            this.render(timestamp, frame, sceneManager)
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
                    this.hitTestSource
                );
                this.handleHitTestResults(
                    hitTestResults,
                    referenceSpace,
                    sceneManager
                );
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
                hit.getPose(referenceSpace).transform.matrix
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
            "https://www.gstatic.com/draco/v1/decoders/"
        );
        this.loader.setDRACOLoader(this.dracoLoader);
        const model = await this.loader.loadAsync(name, onProgress);
        return model.scenes[0];
    }
}

function createARButton(renderer) {
    if (!document.querySelector("#ar-button-container button")) {
        document.querySelector("#ar-button-container").appendChild(
            ARButton.createButton(renderer, {
                requiredFeatures: ["local", "hit-test", "dom-overlay"],
                domOverlay: { root: document.querySelector("#overlay") },
            })
        );
    }
}

async function main() {
    showToaster("Initializing AR...");
    document.getElementById("ar-not-supported").style.display = "none";
    const rendererManager = new RendererManager();
    const sceneManager = new SceneManager(rendererManager.renderer);

    showToaster("Loading model...");
    const model = await ModelLoader.loadModel(
        '/storage/' + museum.path_obj,
        (event) => {
            const fileSize = event.total || 43445936;
            let progress = (event.loaded / fileSize) * 100;
            console.log(event.loaded, event.total || fileSize, progress);
            progress = Math.min(progress, 100); // Ensure progress does not exceed 100%
            document.getElementById("loading-container").style.display =
                "block";
            document.getElementById("loading-bar").style.width = `${progress}%`;
            showToaster(`Loading progress: ${progress}%`);
        }
    );

    document.getElementById("loading-container").style.display = "none";

    showToaster("Model loaded, creating AR button");
    createARButton(rendererManager.renderer);

    showToaster("Adding model to scene");
    model.visible = false;
    sceneManager.scene.add(model);
    model.updateMatrixWorld(true);
    sceneManager.model = model;
    sceneManager.setOnSelect((matrix) => {
        if (model.visible) return;
        document.getElementById("instructions").style.display = "none";
        console.log("Placing model");
        console.log("Position: ", model.position);
        console.log("Quaternion: ", model.quaternion);
        console.log("Scale: ", model.scale);
        matrix.decompose(model.position, model.quaternion, model.scale);

        const targetPosition = new THREE.Vector3();
        sceneManager.camera.getWorldPosition(targetPosition);

        const direction = new THREE.Vector3();
        direction.subVectors(targetPosition, model.position);
        direction.y = 0;
        model.lookAt(direction.add(model.position));
        model.visible = true;
        sceneManager.reticle.visible = false;
        sceneManager.placed = true;

        // const audio = document.getElementById("audio-portal");
        // audio.addEventListener("canplay", async () => {
        //     showToaster("Audio can play");
        //     console.log("Audio can play");
        //     await audio.play().catch((error) => {
        //         showToaster("Audio play error: " + error.message);
        //         console.error("Audio play error:", error);
        //     });
        // });

        // audio.addEventListener("error", (e) => {
        //     showToaster("Audio error: " + e.message);
        //     console.error("Audio error:", e);
        // });

        // if (audio.readyState >= 2) {
        //     showToaster("Audio already loaded");
        //     console.log("Audio already loaded");
        //     audio.dispatchEvent(new Event("canplay"));
        // } else {
        //     showToaster("Loading audio");
        //     console.log("Loading audio");
        //     audio.load();
        // }
    });

    showToaster("Starting animation loop");

    rendererManager.animate(sceneManager);
}

if ("xr" in navigator) {
    navigator.xr.isSessionSupported("immersive-ar").then((supported) => {
        if (supported) {
            //hide "ar-not-supported"
            document.getElementById("ar-not-supported").style.display = "none";
            main();
        }
    });
}
