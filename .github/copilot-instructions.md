# Virtual Living Museum — Copilot Instructions

An interactive AR-enhanced heritage education platform built with Laravel 11, featuring gamified e-learning, WebXR/marker-based AR, and geolocation-based heritage exploration.

## Code Style

**PHP/Laravel:**

- Laravel 11 conventions with PHP 8.2+ syntax
- Controllers handle business logic directly (no repositories/services)
- Helper classes in `app/Helper/` for utilities ([TokenHelper.php](app/Helper/TokenHelper.php))
- Indonesian naming for models, tables, and columns (e.g., `materi`, `situs_peninggalan`, `laporan`)
- Type hints and return types preferred in new code

**Frontend:**

- **CSS**: Tailwind utility-first approach with custom `primary` color (#2D8BEB)
- **JavaScript**: Alpine.js for reactive UI state, avoid jQuery
- **AR Code**: ES6 modules in `/public/assets/js/` (not bundled)
- Global functions pattern: `window.initEbookPageFlip()` called from Blade templates

**Blade Templates:**

- Component-based architecture with Alpine.js directives
- Standard patterns: `x-data`, `x-show`, `@click.outside`

## Architecture

### Access Control (Three-Tier)

Routes organized by middleware stacks in [routes/web.php](routes/web.php):

1. **Guest** (`guest`): Authentication flows only
2. **User** (`auth`, `user`): E-learning, AR experiences, heritage reporting via [HomeController.php](app/Http/Controllers/HomeController.php)
3. **Admin** (`auth`, `admin`): Management dashboard via [AdminController.php](app/Http/Controllers/Admin/AdminController.php)

Custom middleware:

- [IsAdmin](app/Http/Middleware/IsAdmin.php)/[IsUser](app/Http/Middleware/IsUser.php): Role-based redirects
- **[ArTokenAuth](app/Http/Middleware/ArTokenAuth.php)**: HMAC tokens for cross-device AR (bypasses sessions)

### Progressive Learning System

[User.php](app/Models/User.php) tracks gamified progression:

- `incrementLevel()`, `incrementProgressLevel()` methods
- Progress constants: `PRE_TEST=1`, `EBOOK=2`, `VIRTUAL_LIVING_MUSEUM=3`, `POST_TEST=4`
- [Materi.php](app/Models/Materi.php) validates eligibility with `shouldIncrementProgress()`

### AR Implementation (Dual Approach)

1. **Marker-Based**: AR.js + A-Frame ([ar-camera.blade.php](resources/views/guest/ar-camera.blade.php))
    - Pattern files (`.patt`) for tracking
    - Touch gestures via [gesture-detector.js](public/js/gesture-detector.js) / [gesture-handler.js](public/js/gesture-handler.js)

2. **WebXR Hit-Test**: Three.js r153 ([ar-museum-1.js](public/assets/js/ar-museum-1.js))
    - Class-based: `SceneManager`, `RendererManager`, `ModelLoader`
    - DRACO compression, HDRI skybox, shadow mapping
    - Native WebXR API for plane detection

### E-Book System

[ebook.js](resources/js/ebook.js): PDF.js + `page-flip` library

- 1400x1980px canvas rendering (anti-blur high-DPI)
- Fullscreen mode, keyboard navigation
- Completion tracking via POST to `/kunjungi-peninggalan/ebook/{id}/read`

## Build and Test

### Development

```bash
composer install        # PHP dependencies
npm install            # Frontend dependencies
php artisan key:generate
php artisan migrate --seed
composer run dev       # Runs both: php artisan serve & npm run dev
```

### Testing

```bash
php artisan test       # Pest PHP framework
```

**Note**: Minimal test coverage currently — only example tests exist.

### Production Build

```bash
npm run build          # Vite compilation
php artisan storage:link
```

## Database Conventions

**Custom Primary Keys** (not standard `id`):

- Explicit naming: `materi_id`, `situs_id`, `museum_id`, `laporan_id`
- Update foreign key references to match

**Timestamps**:

- Mixed strategy: Some models use `timestamps()`, others disable with `$timestamps = false`
- Manual `created_at` with `useCurrent()` is common

**Key Patterns**:

- No soft deletes, no UUIDs, no polymorphic relations
- Composite unique constraints on pivot tables (e.g., `['user_id', 'situs_id']`)
- Foreign keys always indexed with `onDelete('cascade')`
- Geo-coordinates: `decimal('lat', 10, 8)` and `decimal('lng', 11, 8)`

**Example Best Practice**: [create_feedback_and_reports_tables.php](database/migrations/2025_08_14_210454_create_feedback_and_reports_tables.php)

## Critical Patterns

### Token-Based AR Access

[TokenHelper.php](app/Helper/TokenHelper.php) generates HMAC tokens for stateless AR:

```php
TokenHelper::generate($userId, $expiryMinutes);
TokenHelper::verify($token); // returns userId or false
```

Enables AR experiences on external devices without session cookies.

### Model Relationships

Key models follow explicit naming:

- [User.php](app/Models/User.php): `materi` (many-to-many via `jawaban_user`), `laporanPeninggalan`, `aksesSitusUser`
- [Materi.php](app/Models/Materi.php): `pretest`, `posttest`, `ebook`, `situsPeninggalan`, `tugas`
- [SitusPeninggalan.php](app/Models/SitusPeninggalan.php): `virtualMuseum`, `virtualMuseumObject`

### Asset Organization

- **3D Models**: `/storage/{path_obj}` (GLTF/GLB with DRACO)
- **AR Markers**: `/storage/{path_patt}` (pattern files)
- **HDRI**: `/public/images/hdri/langit.jpg` (skybox)
- **Build Output**: `/public/build/` (Vite-hashed), `/public/assets/js/` (legacy AR modules)

### Vite Configuration

Multiple entry points in [vite.config.js](vite.config.js):

- `resources/css/app.css` — Tailwind compilation
- `resources/js/app.js` — Alpine.js + utilities
- `resources/js/ebook.js` — Standalone flipbook system

Heavy libraries (Three.js, A-Frame, PDF.js) loaded via CDN, not bundled.

## Common Pitfalls

❌ **Using standard `id` primary keys** — Models expect custom naming (`materi_id`, etc.)  
❌ **Assuming soft deletes exist** — All deletions are permanent  
❌ **Bundling AR code with Vite** — AR modules in `/public/assets/js/` served directly  
❌ **Missing cascade deletes** — Always set `onDelete('cascade')` on foreign keys  
❌ **Forgetting composite uniques** — Pivot tables need unique constraints to prevent duplicates

## Future Enhancements

- **n8ao** and **postprocessing** libraries installed but unused (planned for advanced AR visual effects)
- Expand test coverage beyond example tests
- Consider TypeScript for complex AR logic
