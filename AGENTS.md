# AGENTS.md

This file provides guidance to agents when working with code in this repository.

## Build Commands

```bash
composer install && npm install && php artisan key:generate
php artisan migrate --seed                              # Fresh DB with seed data
php artisan migrate:fresh --seed                        # Rebuild everything
composer run dev                                        # Laravel + Vite concurrent
npm run build                                           # Production Vite build
php artisan storage:link                                # Required after build
./vendor/bin/pint                                      # Laravel Pint code style
php artisan test                                        # Pest PHP tests
```

## Critical Conventions (Non-Obvious)

**Custom Primary Keys**: All tables use `{table_singular}_id` NOT standard `id`

```php
$table->id('materi_id');  // Correct - not just $table->id();
```

**Indonesian Naming**: Database uses Indonesian throughout (tables, columns, enums)

- Tables: `situs_peninggalan`, `kritik_saran`
- Enums: `['A','B','C','D']`, `['pretest','posttest']`, `['terbuka','terkunci']`

**Cascade Deletes**: Always `->onDelete('cascade')` on foreign keys (no soft deletes)

**Composite Uniques**: Pivot tables require `->unique(['user_id', 'situs_id'])`

**AR Code NOT Bundled**: Files in `/public/assets/js/` served directly (not via Vite)

**Heavy CDN Libraries**: Three.js, A-Frame, PDF.js loaded via CDN, not bundled

## Demo Mode Toggle

```env
APP_DEMO_MODE=true
```

Unlocks all content without tracking progress (`incrementProgressLevel()` calls skipped).

## Progressive Learning Constants

```php
const PRE_TEST = 1;
const EBOOK = 2;
const VIRTUAL_LIVING_MUSEUM = 3;
const POST_TEST = 4;
```

Check `shouldIncrementProgress()` before unlocking content.

## Token-Based AR Authentication

```php
TokenHelper::generate($userId, $expiryMinutes);  // HMAC-SHA256 token
TokenHelper::verify($token);  // returns userId or false
```

Use `ArTokenAuth` middleware for stateless AR routes.

## Common Pitfalls

- Using `$table->id()` instead of `$table->id('materi_id')`
- Bundling AR code with Vite (keep in `/public/assets/js/`)
- Missing `onDelete('cascade')` on foreign keys
- English naming in database
- Assuming soft deletes exist
