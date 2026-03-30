# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Virtual Living Museum** is an AR-enhanced heritage education platform built with Laravel 11 (PHP 8.2+). It provides gamified e-learning (Pre-test → E-book → Virtual Museum → Post-test), dual AR experiences (marker-based + WebXR), and heritage site mapping.

---

## Commands

```bash
# Setup
composer install && npm install
php artisan key:generate
php artisan migrate --seed           # Fresh db with seed data
php artisan migrate:fresh --seed      # Rebuild everything

# Development
composer run dev                     # Laravel server + Vite (concurrent)

# Production build
npm run build
php artisan storage:link

# Testing
php artisan test                      # Pest PHP

# Linting
./vendor/bin/pint                     # Laravel Pint (code style)
# Qodana: qodana.yaml (PHP 8.2, threshold: 15 issues)

# Cache
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear
```

---

## Architecture

### Route Middleware Stacks (routes/web.php)

| Stack | Middleware | Purpose |
|-------|-----------|---------|
| Guest | `guest` | Auth flows (login, register, password reset) |
| User | `auth` + `user` | E-learning, AR, maps, reports |
| Admin | `auth` + `admin` | Content management dashboard |
| AR Token | `ar.token` | Stateless HMAC auth for AR routes (cross-device) |

### Progressive Learning Flow

User progress tracked via `User::$level_sekarang` and `$progress_level_sekarang`:
1. `PRE_TEST (1)` → Complete pre-test → unlocks EBOOK
2. `EBOOK (2)` → Finish reading → unlocks VIRTUAL_LIVING_MUSEUM
3. `VIRTUAL_LIVING_MUSEUM (3)` → Visit 3D museum → unlocks POST_TEST
4. `POST_TEST (4)` → Complete → advance to next `Materi` level

Key methods: `User::incrementLevel()`, `User::incrementProgressLevel()`, `Materi::shouldIncrementProgress()`

### Dual AR Implementation

1. **Marker-Based (AR.js + A-Frame)**: Pattern files in `/storage/{path_patt}`, touch gestures via `public/js/gesture-detector.js` / `gesture-handler.js`
2. **WebXR (Three.js)**: Class-based architecture in `public/assets/js/ar-museum-3.js` — `SceneManager`, `RendererManager`, `ModelLoader`; DRACO compression, HDRI skybox, PCFSoftShadowMap

AR code in `/public/assets/js/` is served directly (not bundled via Vite). Heavy libs (Three.js, A-Frame, PDF.js) loaded via CDN.

### Token Authentication

`app/Helper/TokenHelper.php` generates HMAC-SHA256 tokens for stateless AR access:
```php
TokenHelper::generate($userId, $expiryMinutes);
TokenHelper::verify($token); // returns userId or false
```

---

## Database Conventions

**Custom primary keys** — always `{table_singular}_id` (NOT standard `id`):
```php
$table->id('materi_id');                        // Primary key
$table->foreignId('materi_id')->constrained('materi', 'materi_id')->onDelete('cascade');
```

**Always `onDelete('cascade')`** — no soft deletes in this project.

**Composite uniques** on pivot tables to prevent duplicates:
```php
$table->unique(['user_id', 'situs_id']);
```

**Indonesian naming** for all tables/columns: `situs_peninggalan`, `pertanyaan`, `jawaban_benar`, `jawaban_benar` enum `['A','B','C','D']`.

**Geo-coordinates**: `decimal('lat', 10, 8)`, `decimal('lng', 11, 8)`.

**Timestamp strategies** vary by table — some use `$table->timestamps()`, others use manual `->useCurrent()` only. Always check the model for `$timestamps`.

---

## Key Models

| Model | Key Fields / Methods |
|-------|----------------------|
| `User` | `level_sekarang`, `progress_level_sekarang`, `incrementLevel()`, `incrementProgressLevel()` |
| `Materi` | `materi_id` PK, `shouldIncrementProgress()`, `getLinearLevel()`, `orderedMateriIds()` |
| `Pretest` / `Posttest` | `materi_id` FK, `pertanyaan`, `pilihan_a/b/c/d`, `jawaban_benar` |
| `JawabanUser` | Pivot: `user_id`, `materi_id`, `jenis` ('pretest'/'posttest'), `benar`, `poin` |
| `SitusPeninggalan` | `situs_id` PK, `lat`, `lng`, `path_patt`, `path_obj` |
| `VirtualMuseum` | `museum_id` PK, `situs_id` FK, `path_obj` for 3D scenes |

---

## Vite Entry Points

Three separate bundles in `vite.config.js`:
- `resources/css/app.css` — Tailwind
- `resources/js/app.js` — Alpine.js + utilities
- `resources/js/ebook.js` — PDF.js + page-flip flipbook (standalone)

---

## Common Pitfalls

- ❌ Using `id()` instead of `id('materi_id')` in migrations — models expect custom PK names
- ❌ Forgetting `onDelete('cascade')` on foreign keys
- ❌ Missing composite unique constraints on pivot tables
- ❌ Bundling AR modules with Vite — keep in `/public/assets/js/`
- ❌ English naming in DB — use Indonesian throughout
- ❌ Assuming soft deletes exist — all deletions are permanent
