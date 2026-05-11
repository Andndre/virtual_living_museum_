# AGENTS.md - Code Mode

This file provides guidance to agents when writing code in this repository.

## PHP/Laravel Coding Rules

**Custom Primary Keys**: Always use explicit naming `{table}_id`, not `$table->id()`

```php
$table->id('materi_id');  // Always specify custom PK name
```

**Indonesian Naming**: Use Indonesian for all database identifiers

- Tables: `situs_peninggalan`, `kritik_saran`, `jawaban_user`
- Columns: `pertanyaan`, `jawaban_benar`, `deskripsi`

**Cascade Deletes**: Always add `->onDelete('cascade')` to foreign keys (no soft deletes in this project)

**Composite Uniques**: Pivot tables MUST have unique constraints

```php
$table->unique(['user_id', 'situs_id']);
```

**Business Logic Location**: Controllers handle business logic directly (no repositories/services)

**Helper Utilities**: Shared logic in `app/Helper/` (e.g., `TokenHelper.php`)

## Frontend/AR Coding Rules

**AR Code NOT Bundled**: Files in `/public/assets/js/` served directly (not via Vite)

- Heavy libs (Three.js, A-Frame, PDF.js) loaded via CDN
- AR modules use ES6 syntax and serve directly

**Tailwind Primary Color**: `#2D8BEB` (extends theme with `'primary'`)

**Alpine.js Pattern**: Use `x-data`, `x-show`, `@click.outside` for reactive UI

**Gesture Controls**: Single finger rotates Y-axis, two fingers scale uniformly

## Progressive Learning Implementation

```php
// Check eligibility before unlocking
if (!$materi->shouldIncrementProgress($user, 'ebook')) {
    return redirect()->back()->with('error', 'Selesaikan pretest terlebih dahulu');
}

// Track progress (respect APP_DEMO_MODE)
if (!App::isDemoMode()) {
    $user->incrementProgressLevel();
}
```

## Demo Mode Pattern

```php
// Every incrementProgressLevel() call must check demo mode
if (!App::isDemoMode()) {
    $user->incrementProgressLevel();
}
```
