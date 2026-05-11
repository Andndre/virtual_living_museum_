# AGENTS.md - Architect Mode

This file provides guidance to agents when designing architecture in this repository.

## System Architecture

### Three-Tier Access Control

| Stack    | Middleware       | Purpose                              |
| -------- | ---------------- | ------------------------------------ |
| Guest    | `guest`          | Auth flows only                      |
| User     | `auth` + `user`  | E-learning, AR, maps                 |
| Admin    | `auth` + `admin` | Content management                   |
| AR Token | `ar.token`       | Stateless HMAC auth for cross-device |

### Progressive Learning Flow

```
PRE_TEST (1) → EBOOK (2) → VIRTUAL_MUSEUM (3) → POST_TEST (4) → Next Materi
```

Users progress linearly through each stage. Progress is tracked via:

- `User::$level_sekarang` - Current materi level
- `User::$progress_level_sekarang` - Current stage within materi (1-4)
- Demo mode (`APP_DEMO_MODE=true`) bypasses all progress tracking

### Dual AR Architecture

**Marker-Based (AR.js + A-Frame)**:

- Pattern files (`.patt`) for visual tracking
- Stored via `SitusPeninggalan->path_patt`
- Touch gestures via custom components

**WebXR (Three.js + Native WebXR)**:

- Class-based: `SceneManager`, `RendererManager`, `ModelLoader`
- DRACO compression, HDRI skybox, PCFSoftShadowMap
- Requires device with WebXR support

## Database Design Patterns

### Custom Primary Keys

All tables use `{table_singular}_id` NOT standard `id`:

```php
$table->id('materi_id');
$table->foreignId('materi_id')->constrained('materi', 'materi_id');
```

### Timestamp Strategies (Mixed)

- `$table->timestamps()` - Models needing update tracking
- `$table->timestamp('created_at')->useCurrent()` - Immutable records
- Manual custom timestamps for specific events

### Geo-coordinates

```php
$table->decimal('lat', 10, 8);   // ~1mm precision
$table->decimal('lng', 11, 8);
```

## Asset Organization

| Type        | Location                                            |
| ----------- | --------------------------------------------------- |
| 3D Models   | `/storage/{path_obj}` (DRACO compressed GLTF/GLB)   |
| AR Markers  | `/storage/{path_patt}` (pattern files)              |
| AR Code     | `/public/assets/js/` (served directly, not bundled) |
| Vite Output | `/public/build/`                                    |

## Vite Build Configuration

Three separate bundles:

1. `resources/css/app.css` - Tailwind
2. `resources/js/app.js` - Alpine.js + utilities
3. `resources/js/ebook.js` - PDF.js + page-flip flipbook

Heavy libs (Three.js, A-Frame, PDF.js) loaded via CDN, not bundled.
