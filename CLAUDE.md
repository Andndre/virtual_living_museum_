# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Smart Prasada** is an AR-enhanced heritage education platform built with Laravel 11 (PHP 8.2+). It provides gamified e-learning (Pre-test → E-book → Virtual Museum → Post-test), dual AR experiences (marker-based + WebXR), 360° VR panorama tours, heritage site mapping, and assignment/report management.

---

## Environment Setup

**Required .env variables:**

```env
APP_TOKEN_SECRET=your_32_character_random_secret  # REQUIRED for AR token auth
APP_DEMO_MODE=false                                # Toggle demo mode
DB_CONNECTION=sqlite                               # Default (or mysql)
QUEUE_CONNECTION=database                          # Queue driver
```

**Test Users** (seeded via `AdminSeeder` / `TestUserSeeder`):
- Admin: `admin@gmail.com` / `password`
- Students: `siswa@example.com` / `password` or `test@example.com` / `password`

**Default Database:** SQLite via `DB_CONNECTION=sqlite` (`.env.example`, `config/database.php`). `database/database.sqlite` is not checked in — create it (`touch database/database.sqlite`) before the first `migrate`. Can switch to MySQL via `.env`.

---

## Commands

```bash
# Setup
composer install && npm install
php artisan key:generate
touch database/database.sqlite       # if using sqlite and the file doesn't exist yet
php artisan migrate --seed           # Fresh db with seed data
php artisan migrate:fresh --seed      # Rebuild everything

# Development
composer run dev                     # Runs 4 concurrent services:
                                     # 1. php artisan serve (Laravel server)
                                     # 2. php artisan queue:listen --tries=1 (Queue worker)
                                     # 3. php artisan pail --timeout=0 (Log viewer)
                                     # 4. npm run dev (Vite HMR)

# Individual services (if needed)
php artisan serve                    # Laravel server only
php artisan queue:listen             # Queue worker only
php artisan pail                     # Log viewer only
npm run dev                          # Vite only

# Frontend
npm run prepare                      # Setup Husky git hooks
npx prettier --write .               # Format with Prettier (Tailwind plugin)

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

### Route Middleware Stacks

| Stack    | Middleware       | Defined in         | Purpose                                          |
| -------- | ---------------- | ------------------ | ------------------------------------------------ |
| Guest    | `guest`          | `routes/auth.php`  | Auth flows (login, register, password reset)     |
| User     | `auth` + `user`  | `routes/web.php`   | E-learning, AR, maps, reports                    |
| Admin    | `auth` + `admin` | `routes/web.php`   | Content management dashboard                     |
| AR Token | `ar.token`       | `routes/web.php`   | Stateless HMAC auth for AR routes (cross-device) |

`user`/`admin`/`ar.token` aliases map to `IsUser` / `IsAdmin` / `ArTokenAuth` middleware, registered in `bootstrap/app.php`.

### Progressive Learning Flow

User progress tracked via `User::$level_sekarang` and `$progress_level_sekarang`:

1. `PRE_TEST (1)` → Complete pre-test → unlocks EBOOK
2. `EBOOK (2)` → Finish reading → unlocks VIRTUAL_LIVING_MUSEUM
3. `VIRTUAL_LIVING_MUSEUM (3)` → Visit 3D museum → unlocks POST_TEST
4. `POST_TEST (4)` → Complete → advance to next `Materi` level

Key methods: `User::incrementLevel()`, `User::incrementProgressLevel()`, `Materi::shouldIncrementProgress()`

### Dual AR Implementation

1. **Marker-Based (AR.js + A-Frame)**: Pattern files in `/storage/{path_patt}`, touch gestures via `public/js/gesture-detector.js` / `gesture-handler.js`
2. **WebXR (Three.js)**: `public/assets/js/ar-museum.js` — DRACO compression, HDRI skybox, PCFSoftShadowMap

AR code in `/public/assets/js/` is served directly (not bundled via Vite). Heavy libs (Three.js, A-Frame, PDF.js) loaded via CDN.

### 360° Panorama & Virtual Museum System

Both the 360° panorama tours and the 3D virtual museum scenes share the same `Scene`/`Hotspot` model pair (Alpine.js editor UI under `resources/views/admin/panorama/`):

- **`Scene`** (table `adegan`, PK `adegan_id`) — belongs to `SitusPeninggalan` via `situs_id`; `type` distinguishes `Scene::TYPE_PANORAMA` vs virtual-museum scenes; `hasMany` ordered `hotspots()`.
- **`Hotspot`** (table `hotspot`, PK `hotspot_id`) — belongs to a `Scene` via `adegan_id`; `type` enum `navigation|info|text`; navigation hotspots reference `target_adegan_id` (another `Scene`); optionally reuses a `HotspotTemplate` (table `templat_hotspot`) for default icon/animation.
- **Editor:** `App\Http\Controllers\Admin\PanoramaController` (admin CRUD for scenes/hotspots/templates, ~30 routes).
- **Viewer:** Public route `/panorama/{situsId}`, no authentication required (unlike AR routes).

### Token Authentication

`app/Helper/TokenHelper.php` generates HMAC-SHA256 tokens for stateless AR access:

```php
TokenHelper::generate($userId, $expiryMinutes);
TokenHelper::verify($token); // returns userId or false
```

---

## Database Conventions

**Custom primary keys** — most domain tables use `{table_singular}_id` (NOT standard `id`); a few tables (`users`, `jobs`, `museum_user_visits`, `katalogs`, `riwayat_pengembangs`, `video_peninggalans`) keep the default `id`. Check the migration/model before assuming:

```php
$table->id('materi_id');                        // Custom primary key
$table->foreignId('materi_id')->constrained('materi', 'materi_id')->onDelete('cascade');
```

**`onDelete('cascade')` is the norm** on foreign keys (no soft deletes in this project), but it's not universal — verify the migration rather than assuming.

**Composite uniques** on pivot tables to prevent duplicates:

```php
$table->unique(['user_id', 'situs_id']);
```

**Indonesian naming** for all tables/columns: `situs_peninggalan`, `pertanyaan`, `jawaban_benar` enum `['A','B','C','D']`, `adegan` (scene), `hotspot`.

**Geo-coordinates**: `decimal('lat', 10, 8)`, `decimal('lng', 11, 8)`.

**Timestamp strategies** vary by table — some use `$table->timestamps()`, others use manual `->useCurrent()` only. Always check the model for `$timestamps`.

---

## Key Models

| Model                  | Key Fields / Methods                                                                        |
| ---------------------- | ------------------------------------------------------------------------------------------- |
| `User`                 | `level_sekarang`, `progress_level_sekarang`, `incrementLevel()`, `incrementProgressLevel()` |
| `Materi`               | `materi_id` PK, `shouldIncrementProgress()`, `getLinearLevel()`, `orderedMateriIds()`       |
| `Pretest` / `Posttest` | `materi_id` FK, `pertanyaan`, `pilihan_a/b/c/d`, `jawaban_benar`                            |
| `JawabanUser`          | Pivot: `user_id`, `materi_id`, `jenis` ('pretest'/'posttest'), `benar`, `poin`              |
| `SitusPeninggalan`     | `situs_id` PK, `lat`, `lng`, `path_patt`, `path_obj`                                        |
| `Scene`                | Table `adegan`, PK `adegan_id`; `situs_id` FK; panorama & virtual-museum scenes; `hotspots()` |
| `Hotspot`              | Table `hotspot`, PK `hotspot_id`; `adegan_id` FK; `type` enum navigation/info/text; optional `templat_hotspot_id` |
| `VideoPeninggalan`     | `video_id` PK, heritage video content management                                            |
| `LaporanPeninggalan`   | `laporan_id` PK, user-submitted heritage site reports with likes/comments                   |
| `KritikSaran`          | `kritik_id` PK, feedback submissions (rate-limited to 10 per minute)                        |

---

## Demo Mode

Toggle via `.env`:

```env
APP_DEMO_MODE=true
```

When enabled:

- All materi are accessible (no "Terkunci" locks on materi cards or tabs)
- All tabs within a materi (pre-test, ebook, museum, post-test) are open
- **No progress is tracked** — `incrementProgressLevel()` calls are skipped across all controllers
- User can explore all content freely without affecting their `level_sekarang` or `progress_level_sekarang`

Use case: shareable demo accounts where anyone can browse all content without a linear progression gate.

> Note: existing demo-mode checks call `env('APP_DEMO_MODE', false)` directly inside controllers/models rather than `config()`. This deviates from the `config()`-only convention below — match existing call sites here, don't "fix" it mid-task unless asked.

### Files that respect `APP_DEMO_MODE`

| File                                                 | Behavior                                              |
| ---------------------------------------------------- | ----------------------------------------------------- |
| `HomeController::elearningMateri()`                  | All tab availability flags set to open                |
| `HomeController::elearningList()` / `elearningEra()` | All materi marked `is_available = true`               |
| `HomeController::submitPretest()`                    | Skips `incrementProgressLevel()`                      |
| `HomeController::submitPosttest()`                   | Skips `incrementProgressLevel()`                      |
| `HomeController::markEbookRead()`                    | Skips progress increment                              |
| `HomeController::arMuseum()`                         | Skips museum visit progress tracking                  |
| `Materi::shouldIncrementProgress()`                  | Returns `true` (safety net for any future call sites) |

---

## Frontend Tooling

### Vite Bundles

Three separate entry points in `vite.config.js`:
- `resources/css/app.css` — Tailwind base styles
- `resources/js/app.js` — Alpine.js + utilities
- `resources/js/ebook.js` — PDF.js + page-flip flipbook (standalone)

### Code Formatting

- **Prettier** with Tailwind CSS plugin (`.prettierrc.json`)
- **Husky pre-commit hook** automatically runs `npm run build` and stages `public/build` assets

### Module Strategy

- **Bundled via Vite:** Alpine.js, Tailwind, core app logic
- **Direct-served (NOT bundled):** AR modules (`/public/assets/js/`), heavy CDN libs (Three.js, A-Frame, PDF.js)

---

## Common Pitfalls

- ❌ Using `id()` instead of `id('{table}_id')` on a new migration for a table that follows the custom-PK convention — check sibling migrations first
- ❌ Forgetting `onDelete('cascade')` on foreign keys
- ❌ Missing composite unique constraints on pivot tables
- ❌ Bundling AR modules with Vite — keep in `/public/assets/js/`
- ❌ English naming in DB — use Indonesian throughout
- ❌ Assuming soft deletes exist — all deletions are permanent
- ❌ Forgetting `APP_TOKEN_SECRET` in .env — AR routes will fail silently
- ❌ Not running `npm run prepare` after clone — pre-commit hooks won't be installed
- ❌ Using MySQL commands when default DB is SQLite — check .env first
- ❌ Assuming `database/database.sqlite` exists — it's gitignored, create it before first `migrate`
- ❌ Calling the panorama/museum data model `Panorama` — it's `Scene` (table `adegan`) and `Hotspot` (table `hotspot`)

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.21
- laravel/framework (LARAVEL) - v11
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/breeze (BREEZE) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v3
- phpunit/phpunit (PHPUNIT) - v11
- alpinejs (ALPINEJS) - v3
- prettier (PRETTIER) - v3
- tailwindcss (TAILWINDCSS) - v3

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs

- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function \_\_construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments

- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks

- Add useful array shape type definitions for arrays when appropriate.

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] <name>` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v11 rules ===

## Laravel 11

- Use the `search-docs` tool to get version specific documentation.
- Laravel 11 brought a new streamlined file structure which this project now uses.

### Laravel 11 Structure

- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

### New Artisan Commands

- List Artisan commands using Boost's MCP tool, if available. New commands available in Laravel 11:
    - `php artisan make:enum`
    - `php artisan make:class`
    - `php artisan make:interface`

=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.

=== pest/core rules ===

## Pest

### Testing

- If you need to verify a feature is working, write or update a Unit / Feature test.

### Pest Tests

- All tests must be written using Pest. Use `php artisan make:test --pest <name>`.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files - these are core to the application.
- Tests should test all of the happy paths, failure paths, and weird paths.
- Tests live in the `tests/Feature` and `tests/Unit` directories.
- Pest tests look and behave like this:
  <code-snippet name="Basic Pest Test Example" lang="php">
  it('is true', function () {
  expect(true)->toBeTrue();
  });
  </code-snippet>

### Running Tests

- Run the minimal number of tests using an appropriate filter before finalizing code edits.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).
- When the tests relating to your changes are passing, ask the user if they would like to run the entire test suite to ensure everything is still passing.

### Pest Assertions

- When asserting status codes on a response, use the specific method like `assertForbidden` and `assertNotFound` instead of using `assertStatus(403)` or similar, e.g.:
  <code-snippet name="Pest Example Asserting postJson Response" lang="php">
  it('returns all', function () {
  $response = $this->postJson('/api/docs', []);

        $response->assertSuccessful();

    });
    </code-snippet>

### Mocking

- Mocking can be very helpful when appropriate.
- When mocking, you can use the `Pest\Laravel\mock` Pest function, but always import it via `use function Pest\Laravel\mock;` before using it. Alternatively, you can use `$this->mock()` if existing tests do.
- You can also create partial mocks using the same import or self method.

### Datasets

- Use datasets in Pest to simplify tests which have a lot of duplicated data. This is often the case when testing validation rules, so consider going with this solution when writing tests for validation rules.

<code-snippet name="Pest Dataset Example" lang="php">
it('has emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with([
    'james' => 'james@laravel.com',
    'taylor' => 'taylor@laravel.com',
]);
</code-snippet>

=== tailwindcss/core rules ===

## Tailwind Core

- Use Tailwind CSS classes to style HTML, check and use existing tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions (i.e. Blade, JSX, Vue, etc..)
- Think through class placement, order, priority, and defaults - remove redundant classes, add classes to parent or child carefully to limit repetition, group elements logically
- You can use the `search-docs` tool to get exact examples from the official documentation when needed.

### Spacing

- When listing items, use gap utilities for spacing, don't use margins.

      <code-snippet name="Valid Flex Gap Spacing Example" lang="html">
          <div class="flex gap-8">
              <div>Superior</div>
              <div>Michigan</div>
              <div>Erie</div>
          </div>
      </code-snippet>

### Dark Mode

- If existing pages and components support dark mode, new pages and components must support dark mode in a similar way, typically using `dark:`.

=== tailwindcss/v3 rules ===

## Tailwind 3

- Always use Tailwind CSS v3 - verify you're using only classes supported by this version.

=== tests rules ===

## Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test` with a specific filename or filter.
  </laravel-boost-guidelines>
