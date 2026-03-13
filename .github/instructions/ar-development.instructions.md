---
description: "Use when developing AR features, WebXR experiences, Three.js scene management, AR.js marker tracking, gesture controls, or 3D model optimization. Covers both marker-based and WebXR hit-test approaches."
applyTo: "public/assets/js/**, public/js/gesture-\*.js, resources/views/**/ar-\*.blade.php"

---

# AR Development Guidelines

## Dual AR Architecture

### Marker-Based AR (AR.js + A-Frame)

**Files**: [ar-camera.blade.php](../../resources/views/guest/ar-camera.blade.php), [gesture-detector.js](../../public/js/gesture-detector.js), [gesture-handler.js](../../public/js/gesture-handler.js)

**Pattern**:

```html
<!-- Declarative A-Frame entities -->
<a-scene embedded arjs>
    <a-marker preset="custom" type="pattern" url="/storage/{path_patt}">
        <a-gltf-model src="#model" gesture-handler></a-gltf-model>
    </a-marker>
</a-scene>
```

**Key Points**:

- Pattern files (`.patt`) stored in `/storage/` via `SitusPeninggalan->path_patt`
- Touch gestures use custom `gesture-detector` + `gesture-handler` components
- Single-finger rotation (Y-axis), two-finger scale
- Always include sensor permission prompts (DeviceMotion/Orientation)

### WebXR Hit-Test (Three.js + Native WebXR)

**Files**: [ar-museum-1.js](../../public/assets/js/ar-museum-1.js), [ar/museum.blade.php](../../resources/views/guest/ar/museum.blade.php)

**Class-Based Pattern**:

```javascript
class SceneManager {
    constructor() {
        this.scene = new THREE.Scene();
        this.setupLighting(); // Directional + Hemisphere
        this.loadSkybox(); // HDRI from /public/images/hdri/
    }
}

class RendererManager {
    async startXRSession() {
        this.xrSession = await navigator.xr.requestSession("immersive-ar", {
            requiredFeatures: ["hit-test"],
        });
    }
}

class ModelLoader {
    constructor() {
        const dracoLoader = new THREE.DRACOLoader();
        dracoLoader.setDecoderPath(
            "https://www.gstatic.com/draco/versioned/decoders/1.5.7/",
        );
        this.loader.setDRACOLoader(dracoLoader);
    }
}
```

**Key Points**:

- DRACO compression required for model optimization
- Shadow mapping: `renderer.shadowMap.enabled = true`, `PCFSoftShadowMap`
- HDRI skybox: `/public/images/hdri/langit.jpg` loaded via `THREE.RGBELoader`
- Reticle for placement feedback, hit-test for surface detection
- Progress tracking during model load (show filesize from server)

## 3D Model Standards

**Formats**: GLTF/GLB with DRACO compression
**Storage**: `/storage/{VirtualMuseum->path_obj}` or `/storage/{VirtualMuseumObject->path_obj}`

**Optimization Checklist**:

- ✅ DRACO compression enabled
- ✅ Textures power-of-2 resolution (512×512, 1024×1024)
- ✅ Polygon count < 50k for mobile devices
- ✅ PBR materials (metalness/roughness workflow)
- ✅ Baked lighting where possible

## Asset Loading Pattern

```javascript
// Show progress with filesize
fetch("/path/to/model.glb").then((response) => {
    const total = response.headers.get("content-length");
    // Update progress bar from 0 to total bytes
});

// Always handle load errors
loader.load(url, onLoad, onProgress, (error) => {
    console.error("Model load failed:", error);
    // Show user-friendly error message
});
```

## AR Token Authentication

When creating AR routes, use [ArTokenAuth](../../app/Http/Middleware/ArTokenAuth.php) middleware:

```php
Route::get('/situs/{situs_id}/ar/{museum_id}', [HomeController::class, 'arMuseum'])
    ->middleware(['ar.token']);
```

Generate tokens using [TokenHelper](../../app/Helper/TokenHelper.php):

```php
$token = TokenHelper::generate($userId, expiryMinutes: 60);
// Token format: base64(userId|timestamp|hmac_signature)
```

## Performance Best Practices

- **Lazy Load CDN Libraries**: Three.js, A-Frame, PDF.js loaded only on AR pages
- **Not Bundled in Vite**: AR code in `/public/assets/js/` served directly
- **Shadow Optimization**: Use `castShadow`/`receiveShadow` selectively
- **Dispose Resources**: Clean up geometries, materials, textures on unmount
- **Mobile-First**: Test on mid-range Android devices (throttled CPU)

## Gesture Controls

Standard gesture patterns from [gesture-handler.js](../../public/js/gesture-handler.js):

- **Single finger**: Rotate Y-axis (horizontal swipe)
- **Two fingers**: Scale uniformly (pinch)
- **Rotation limits**: Smooth damping, no axis lock
- **Scale limits**: Min 0.5, max 3.0

## Common AR Pitfalls

❌ **Loading uncompressed models** — Always enable DRACO  
❌ **Missing sensor permissions** — Prompt before AR session  
❌ **Forgetting shadow maps** — PCFSoft required for realistic lighting  
❌ **Blocking main thread** — Use Web Workers for heavy computations  
❌ **iOS WebXR compatibility** — Test with Variant Launch SDK fallback

## Future Enhancements

Libraries installed but not yet integrated:

- **n8ao** v1.10.1: Scalable ambient occlusion for Three.js
- **postprocessing** v6.37.7: Bloom, tone mapping, depth-of-field

Example integration:

```javascript
import {
    EffectComposer,
    EffectPass,
    N8AOPass,
} from "three/examples/jsm/postprocessing/...";
const composer = new EffectComposer(renderer);
const n8aoPass = new N8AOPass(scene, camera);
composer.addPass(n8aoPass);
```
