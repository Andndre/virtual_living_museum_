---
description: "Use when creating database migrations, altering schemas, or designing table relationships. Covers naming conventions, indexing strategies, foreign keys, and rollback patterns specific to this project."
applyTo: "database/migrations/**"
---

# Migration Patterns & Conventions

## Project-Specific Conventions

### Primary Key Naming

**NON-STANDARD**: Use explicit custom names, not Laravel's default `id()`

```php
// ✅ Correct
Schema::create('materi', function (Blueprint $table) {
    $table->id('materi_id');  // Custom name
});

// ❌ Wrong
Schema::create('materi', function (Blueprint $table) {
    $table->id();  // Defaults to 'id'
});
```

**Pattern**: `{table_singular}_id` (e.g., `situs_id`, `museum_id`, `laporan_id`)

### Foreign Key References

Always reference custom primary keys explicitly:

```php
// ✅ Correct
$table->foreignId('materi_id')
    ->constrained('materi', 'materi_id')
    ->onDelete('cascade');

// ❌ Wrong (assumes 'id' column)
$table->foreignId('materi_id')
    ->constrained('materi')
    ->onDelete('cascade');
```

**Rules**:

- **Always cascade**: `onDelete('cascade')` (no soft deletes in this project)
- **Always indexed**: Foreign keys automatically indexed
- **Naming**: Foreign key column matches referenced primary key name

## Indonesian Naming Convention

All database identifiers use Indonesian language:

**Tables**: `situs_peninggalan`, `kritik_saran`, `laporan_komentar`  
**Columns**: `pertanyaan`, `jawaban_benar`, `deskripsi`, `batas_waktu`  
**Enums**: `['terbuka', 'terkunci']`, `['pretest', 'posttest']`

## Timestamp Strategies

**MIXED APPROACH** — Choose based on use case:

### Option 1: Laravel Standard

```php
$table->timestamps();  // created_at & updated_at (automatic)
```

**Use when**: Model needs update tracking (e.g., admin-editable content)

### Option 2: Manual Created Only

```php
$table->timestamp('created_at')->useCurrent();
// No updated_at column
```

**Use when**: Immutable records (logs, reports, submissions)

### Option 3: No Timestamps

```php
// Omit timestamps entirely
public $timestamps = false;  // In model
```

**Use when**: Pure pivot tables or static reference data

### Option 4: Custom Timestamps

```php
$table->timestamp('visited_at')->nullable();
$table->timestamp('unlocked_at')->nullable();
```

**Use when**: Tracking specific events separate from creation/update

**Example Mix** ([User.php](app/Models/User.php)):

```php
// Standard timestamps for profile edits
$table->timestamps();

// Custom timestamp for site unlocks (AksesSitusUser)
$table->timestamp('unlocked_at')->nullable();
```

## Geolocation Fields

Standard pattern for heritage sites and user reports:

```php
$table->decimal('lat', 10, 8);   // Latitude (8 decimal places)
$table->decimal('lng', 11, 8);   // Longitude (8 decimal places)

// Index for location-based queries
$table->index(['lat', 'lng']);
```

**Precision**: ~1mm accuracy (sufficient for building-level precision)

## Composite Unique Constraints

**CRITICAL**: Prevent duplicate entries in pivot/junction tables:

```php
// User can only unlock each site once
Schema::create('akses_situs_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('situs_id')->constrained('situs_peninggalan', 'situs_id')->onDelete('cascade');
    $table->unique(['user_id', 'situs_id']);  // ← Critical
});

// User can only like each report once
Schema::create('laporan_suka', function (Blueprint $table) {
    $table->id();
    $table->foreignId('laporan_id')->constrained('laporan_peninggalan', 'laporan_id')->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->unique(['laporan_id', 'user_id']);  // ← Critical
});
```

**When to Use**: Any table representing a unique relationship between two entities

## Indexing Strategy

### Automatic Indexes

- Foreign keys (handled by Laravel)
- Primary keys (automatic)
- Unique constraints (automatic)

### Manual Indexes

```php
// Single column (for filtering/sorting)
$table->index('urutan');           // Material ordering
$table->index('created_at');       // Recent items first

// Composite (for combined queries)
$table->index(['user_id', 'created_at']);  // User activity timeline
$table->index(['lat', 'lng']);              // Geospatial queries

// Enum/status columns (if frequently filtered)
$table->index('status');
```

**Rule of Thumb**: Index columns used in `WHERE`, `ORDER BY`, or `JOIN` clauses

## Multi-Table Migrations

**PREFERRED**: Group related tables in single migration file

**Example**: [create_feedback_and_reports_tables.php](database/migrations/2025_08_14_210454_create_feedback_and_reports_tables.php)

```php
// Creates: laporan_peninggalan, laporan_gambar, laporan_komentar, laporan_suka, kritik_saran
public function up(): void
{
    Schema::create('laporan_peninggalan', function (Blueprint $table) { /* ... */ });
    Schema::create('laporan_gambar', function (Blueprint $table) { /* ... */ });
    Schema::create('laporan_komentar', function (Blueprint $table) { /* ... */ });
    Schema::create('laporan_suka', function (Blueprint $table) { /* ... */ });
    Schema::create('kritik_saran', function (Blueprint $table) { /* ... */ });
}

public function down(): void
{
    // Reverse order (child tables first)
    Schema::dropIfExists('kritik_saran');
    Schema::dropIfExists('laporan_suka');
    Schema::dropIfExists('laporan_komentar');
    Schema::dropIfExists('laporan_gambar');
    Schema::dropIfExists('laporan_peninggalan');
}
```

**Benefits**: Atomic feature deployment, clearer history, easier rollback

## Enum Columns

Use `enum()` for fixed sets of values:

```php
$table->enum('jawaban_benar', ['A', 'B', 'C', 'D']);
$table->enum('jenis', ['pretest', 'posttest']);
$table->enum('status', ['terbuka', 'terkunci']);
```

**Advantages**: Database-level validation, clear options  
**Limitations**: Requires migration to add new values

**Alternative**: Use `string()` + model-level validation if values may expand

## File Storage Paths

Columns storing file paths in `/storage/`:

```php
$table->string('path_obj')->nullable();   // 3D models (GLTF/GLB)
$table->string('path_patt')->nullable();  // AR marker patterns
$table->string('thumbnail')->nullable();  // Preview images
$table->string('profile_photo')->nullable();  // User photos
```

**Storage Convention**: Paths relative to `/storage/app/public/`  
**Access**: Symlink via `php artisan storage:link`

## Common Migration Patterns

### Adding Columns to Existing Table

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('profile_photo')->nullable()->after('email');
});
```

### Dropping Columns (Destructive)

```php
Schema::table('materi', function (Blueprint $table) {
    $table->dropColumn('masa');  // Permanent data loss
});
```

### Renaming Columns

```php
Schema::table('users', function (Blueprint $table) {
    $table->renameColumn('old_name', 'new_name');
});
```

### Modifying Column Type

```php
Schema::table('pretest', function (Blueprint $table) {
    $table->text('pertanyaan')->change();  // varchar → text
});
```

## Rollback Best Practices

**Always implement `down()` method**:

```php
public function up(): void
{
    Schema::create('new_table', function (Blueprint $table) { /* ... */ });
}

public function down(): void
{
    Schema::dropIfExists('new_table');  // Reverse the up() operation
}
```

**Test rollback before deploying**:

```bash
php artisan migrate:rollback
php artisan migrate  # Re-apply
```

**Irreversible Migrations**: Comment why rollback is destructive

```php
public function down(): void
{
    // Cannot restore deleted 'masa' column data
    Schema::table('materi', function (Blueprint $table) {
        $table->string('masa')->nullable();  // Empty column
    });
}
```

## Comments for Clarity

Use inline comments in complex migrations:

```php
$table->foreignId('materi_id')
    ->constrained('materi', 'materi_id')
    ->onDelete('cascade')
    ->comment('Links to learning material');

$table->enum('status', ['terbuka', 'terkunci'])
    ->default('terkunci')
    ->comment('Site access: terbuka (unlocked) or terkunci (locked)');
```

**Benefits**: Self-documenting schema, helps future developers

## Common Pitfalls

❌ **Using `id()` without custom name** — Models expect `{table}_id`  
❌ **Forgetting `onDelete('cascade')`** — Orphaned rows in child tables  
❌ **Missing composite uniques** — Duplicate pivot table entries  
❌ **English naming** — Use Indonesian throughout  
❌ **No indexing on foreign keys** — Already indexed automatically  
❌ **Inconsistent timestamp strategy** — Choose one approach per table  
❌ **Dropping tables with data** — Backup before destructive migrations

## Migration Testing Checklist

Before merging:

- ✅ Run `php artisan migrate:fresh --seed` on clean database
- ✅ Verify foreign key constraints work (try deleting parent)
- ✅ Test rollback: `php artisan migrate:rollback`
- ✅ Check indexes exist: `SHOW INDEX FROM table_name;`
- ✅ Validate data types match model expectations
- ✅ Confirm unique constraints prevent duplicates
- ✅ Review cascade delete behavior (intentional data loss?)

## Example Best Practice Migration

See [create_feedback_and_reports_tables.php](database/migrations/2025_08_14_210454_create_feedback_and_reports_tables.php):

- ✅ Multiple related tables in one file
- ✅ Comprehensive indexing
- ✅ Proper cascade relationships
- ✅ Clear inline comments
- ✅ Indonesian naming
- ✅ Custom primary keys
- ✅ Composite unique constraints
