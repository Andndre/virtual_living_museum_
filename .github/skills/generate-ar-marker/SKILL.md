---
name: generate-ar-marker
description: "Convert images to AR.js pattern files (.patt) for marker-based augmented reality tracking. Use when adding new AR markers for 3D models, creating custom tracking targets, or optimizing marker detection. Supports NFT (natural feature tracking) and barcode markers."
argument-hint: "Image file path or marker type"
---

# Generate AR.js Marker Pattern

Converts images into AR.js `.patt` (pattern) files for marker-based tracking in A-Frame AR scenes.

## When to Use

- Adding new 3D objects to AR experiences
- Creating custom tracking markers for heritage artifacts
- Replacing default AR.js markers (Hiro, Kanji)
- Optimizing marker detection for specific lighting conditions

## Marker Types

### 1. Pattern Markers (Recommended)

Custom images converted to `.patt` files for tracking.

**Best For**:

- High-contrast logos or symbols
- Black-and-white designs
- Simple geometric patterns
- Heritage site emblems

**Advantages**:

- Customizable appearance
- Good detection in varied lighting
- Smaller file size

### 2. NFT (Natural Feature Tracking)

Uses image features (edges, corners) for tracking without borders.

**Best For**:

- Photographs
- Complex images (paintings, posters)
- Real-world textures

**Advantages**:

- No border required
- More realistic appearance

### 3. Barcode Markers

Pre-defined barcodes (0-63) for quick identification.

**Best For**:

- Multiple objects in same scene
- Rapid prototyping
- Testing without custom images

## Generation Methods

### Online Tool (Easiest)

Use AR.js Marker Training: https://ar-js-org.github.io/AR.js/three.js/examples/marker-training/examples/generator.html

**Steps**:

1. Upload image (square, high contrast recommended)
2. Set pattern ratio (default: 0.5)
3. Click "Generate Marker"
4. Download `.patt` file

**Pattern Ratio**:

- `0.5` = 50% of marker area used for tracking (default)
- `0.75` = More content area, less border (harder to detect)
- `0.9` = Maximum content area (best for text/logos)

### Command Line Tool

```bash
# Install ar-marker-generator (npm package)
npm install -g ar-marker-generator

# Generate pattern file
ar-marker-generator input.png output.patt --ratioInner 0.5
```

### NFT Marker Creator

For natural feature tracking:

```bash
# Install NFT-Marker-Creator
git clone https://github.com/Carnaux/NFT-Marker-Creator.git
cd NFT-Marker-Creator

# Generate NFT descriptors (iset, fset, fset3)
node app.js -i input.jpg
```

## Image Requirements

### Optimal Marker Design

**Must Have**:

- ✅ Square aspect ratio (1:1)
- ✅ High contrast (black on white or vice versa)
- ✅ Non-symmetrical design (for orientation detection)
- ✅ Resolution: 512×512px minimum, 1024×1024px recommended

**Avoid**:

- ❌ Symmetrical patterns (causes rotation ambiguity)
- ❌ Low contrast or grayscale gradients
- ❌ Fine details (get lost in compression)
- ❌ Text smaller than 48pt
- ❌ Photographs (use NFT instead)

### Example Good Markers

- Bold logos (Nike swoosh, Apple logo)
- QR code-like patterns
- High-contrast icons
- Geometric shapes with clear edges

### Example Bad Markers

- Photos of faces
- Complex illustrations
- Low-contrast designs
- Symmetrical mandalas

## Implementation in Project

### 1. Store Pattern File

Upload `.patt` file to storage:

```bash
# Local development
/storage/app/public/patterns/artifact_ganesha.patt

# Database reference (VirtualMuseumObject)
path_patt: 'patterns/artifact_ganesha.patt'
```

### 2. Update Model

```php
// database/seeders or admin controller
VirtualMuseumObject::create([
    'museum_id' => $museumId,
    'situs_id' => $situsId,
    'nama' => 'Arca Ganesha',
    'path_obj' => 'models/ganesha.glb',
    'path_patt' => 'patterns/ganesha.patt',  // ← Pattern file
    'deskripsi' => 'Patung dewa Hindu berkepala gajah',
]);
```

### 3. Use in AR Scene

**File**: [ar-camera.blade.php](../../resources/views/guest/ar-camera.blade.php)

```html
<a-scene embedded arjs="sourceType: webcam; debugUIEnabled: false;">
    <!-- Custom pattern marker -->
    <a-marker
        type="pattern"
        preset="custom"
        url="{{ asset('storage/' . $object->path_patt) }}"
        raycaster="objects: .clickable"
        emitevents="true"
        cursor="fuse: false; rayOrigin: mouse;"
    >
        <!-- 3D model appears when marker detected -->
        <a-gltf-model
            src="{{ asset('storage/' . $object->path_obj) }}"
            scale="0.5 0.5 0.5"
            position="0 0 0"
            rotation="0 0 0"
            gesture-handler
        >
        </a-gltf-model>
    </a-marker>

    <a-entity camera></a-entity>
</a-scene>
```

## Testing Markers

### Print Test

1. **Generate printable marker**:
    - Add border around pattern (20% margin)
    - Print on white paper at least 10×10cm
    - Ensure crisp, dark ink

2. **Test detection**:

    ```bash
    php artisan serve
    # Visit /ar-camera route with test marker
    ```

3. **Optimize if needed**:
    - Adjust pattern ratio
    - Increase border size
    - Improve lighting conditions

### Digital Test

Use marker image on tablet/second screen:

- Avoid glare from glass screen
- Maximum brightness
- Matte screen better than glossy

## Validation Script

Check pattern file format (630 lines expected):

```bash
# Count lines in pattern file
wc -l patterns/ganesha.patt
# Should output: 630 patterns/ganesha.patt

# Validate structure
head -n 5 patterns/ganesha.patt
# Should show space-separated integers (0-255)
```

**Pattern File Structure**:

- Lines 1-315: Red channel matrix (21×15)
- Lines 316-630: Green and Blue channels
- Each value: 0-255 (grayscale intensity)

## Performance Optimization

### Detection Settings

Adjust AR.js parameters for better tracking:

```html
<a-scene
    arjs="
  detectionMode: mono_and_matrix;
  matrixCodeType: 3x3;
  patternRatio: 0.5;
  minConfidence: 0.6;
  trackingMethod: best;
"
></a-scene>
```

**Parameters**:

- `minConfidence`: 0.6 (default) → 0.8 (stricter, fewer false positives)
- `patternRatio`: Match generation ratio
- `trackingMethod`: `best` (slower, accurate) or `fast` (quick, less precise)

### Lighting Conditions

Best detection:

- Even, diffuse lighting (avoid harsh shadows)
- Bright but not washed out
- No glare on marker surface
- Fixed camera exposure (not auto-adjusting)

## Multiple Markers in Scene

**Pattern**: Each object has unique marker

```html
<a-scene embedded arjs>
    <!-- Marker 1: Ganesha -->
    <a-marker type="pattern" url="{{ asset('storage/patterns/ganesha.patt') }}">
        <a-gltf-model
            src="{{ asset('storage/models/ganesha.glb') }}"
        ></a-gltf-model>
    </a-marker>

    <!-- Marker 2: Relief Panel -->
    <a-marker type="pattern" url="{{ asset('storage/patterns/relief.patt') }}">
        <a-gltf-model
            src="{{ asset('storage/models/relief.glb') }}"
        ></a-gltf-model>
    </a-marker>

    <a-entity camera></a-entity>
</a-scene>
```

**Note**: AR.js can track 2-3 markers simultaneously on mid-range devices

## Troubleshooting

### Issue: Marker not detected

**Solutions**:

- ✅ Verify `.patt` file is 630 lines
- ✅ Check pattern ratio matches generation
- ✅ Increase border size (try 0.5 → 0.75)
- ✅ Improve lighting (add desk lamp)
- ✅ Print larger (15×15cm minimum)
- ✅ Test with high-contrast source image

### Issue: Detection unstable (flickers)

**Solutions**:

- ✅ Use `trackingMethod: best` in AR.js settings
- ✅ Reduce ambient light variations
- ✅ Increase `minConfidence` threshold
- ✅ Simplify marker design (less fine details)
- ✅ Use thicker border

### Issue: Wrong orientation on detection

**Solutions**:

- ✅ Make marker non-symmetrical (add corner indicator)
- ✅ Rotate 3D model in scene: `rotation="0 90 0"`
- ✅ Adjust marker design to have clear "up" direction

### Issue: False positives (detects random objects)

**Solutions**:

- ✅ Increase `minConfidence` to 0.8+
- ✅ Use more unique, complex marker design
- ✅ Avoid patterns that resemble common objects

## Database Integration

Store marker metadata in `virtual_museum_object`:

```php
Schema::table('virtual_museum_object', function (Blueprint $table) {
    $table->string('path_patt')->nullable()->comment('AR.js pattern file for marker tracking');
    $table->float('pattern_ratio')->default(0.5)->comment('Marker pattern ratio used in generation');
    $table->integer('marker_size_cm')->nullable()->comment('Recommended print size in cm');
});
```

## Best Practices

1. **Version Control**: Commit pattern files with descriptive names

    ```
    patterns/
    ├── borobudur_stupa.patt
    ├── prambanan_reliefs.patt
    └── national_museum_logo.patt
    ```

2. **Naming Convention**: `{site_name}_{object_name}.patt`

3. **Documentation**: Comment pattern ratio and optimal size

    ```php
    // Pattern ratio: 0.75, print size: 15×15cm
    'path_patt' => 'patterns/ganesha.patt'
    ```

4. **Testing**: Always test on target devices before deployment

5. **Backup Source Images**: Keep original high-res images for regeneration

## Related Resources

- [AR.js Marker Training Tool](https://ar-js-org.github.io/AR.js/three.js/examples/marker-training/examples/generator.html)
- [NFT Marker Creator](https://github.com/Carnaux/NFT-Marker-Creator)
- [AR Development Guidelines](../.github/instructions/ar-development.instructions.md)
- [Create Heritage Site Skill](../create-heritage-site/SKILL.md)
