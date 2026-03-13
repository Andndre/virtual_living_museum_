---
name: create-heritage-site
description: "Automated workflow for creating new heritage sites (situs peninggalan) with all required components: database migration, model, admin interface, AR setup, and map integration. Use when adding new historical sites or cultural heritage locations."
argument-hint: "Site name and basic details"
---

# Create Heritage Site Workflow

Automates the complete setup for a new heritage site (situs peninggalan) with all integrated features.

## When to Use

- Adding a new historical site to the platform
- Creating field trip destinations for students
- Expanding heritage database with user-submitted locations
- Setting up AR-enabled virtual museums

## Prerequisites

- Site name (Indonesian)
- Location coordinates (latitude/longitude)
- Basic description
- Category/type (e.g., candi, museum, monumen)

## Automated Steps

### 1. Create Migration

Generate migration file for new site entry:

```php
// database/migrations/YYYY_MM_DD_HHMMSS_add_[site_name]_to_situs_peninggalan.php
Schema::table('situs_peninggalan', function (Blueprint $table) {
    // If adding single record via seeder, or
    // If creating new category, add enum value
});
```

Or create seeder:

```php
// database/seeders/SitusPeninggalanSeeder.php
SitusPeninggalan::create([
    'nama' => 'Candi Prambanan',
    'deskripsi' => 'Kompleks candi Hindu terbesar di Indonesia...',
    'lat' => -7.752020,
    'lng' => 110.491470,
    'alamat' => 'Jl. Raya Yogyakarta-Prambanan',
    'materi_id' => 1,  // Link to learning material
    'thumbnail' => 'thumbnails/prambanan.jpg',
]);
```

### 2. Verify Model Relationships

Ensure [SitusPeninggalan.php](app/Models/SitusPeninggalan.php) has required relationships:

```php
// Should already exist:
public function materi() {
    return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
}

public function virtualMuseum() {
    return $this->hasMany(VirtualMuseum::class, 'situs_id', 'situs_id');
}

public function aksesSitusUser() {
    return $this->hasMany(AksesSitusUser::class, 'situs_id', 'situs_id');
}
```

### 3. Add Admin Interface Entry

Update [AdminController.php](app/Http/Controllers/Admin/AdminController.php) for CRUD:

```php
// Create form view
public function createSitus() {
    $materi = Materi::orderBy('urutan')->get();
    return view('admin.situs.create', compact('materi'));
}

// Store with validation
public function storeSitus(Request $request) {
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'lat' => 'required|numeric|between:-90,90',
        'lng' => 'required|numeric|between:-180,180',
        'alamat' => 'required|string',
        'materi_id' => 'required|exists:materi,materi_id',
        'thumbnail' => 'required|image|max:2048',
    ]);

    // Handle thumbnail upload
    if ($request->hasFile('thumbnail')) {
        $path = $request->file('thumbnail')->store('thumbnails', 'public');
        $validated['thumbnail'] = $path;
    }

    SitusPeninggalan::create($validated);
    return redirect()->route('admin.situs.index')->with('success', 'Situs berhasil ditambahkan');
}
```

### 4. Create Map Marker

Add map integration in [MapsController.php](app/Http/Controllers/MapsController.php):

```php
// API endpoint for map markers
public function getSitusMarkers() {
    return SitusPeninggalan::select('situs_id', 'nama', 'lat', 'lng', 'thumbnail')
        ->where('status', 'terbuka')  // Only unlocked sites
        ->get();
}
```

Frontend (Blade with Leaflet/Google Maps):

```javascript
// Add marker to map
const marker = L.marker([{{ $situs->lat }}, {{ $situs->lng }}])
    .addTo(map)
    .bindPopup(`
        <b>{{ $situs->nama }}</b><br>
        <img src="/storage/{{ $situs->thumbnail }}" width="100"><br>
        <a href="/situs/{{ $situs->situs_id }}">Kunjungi</a>
    `);
```

### 5. Setup AR Experience (Optional)

If site includes AR museum:

#### A. Create Virtual Museum Entry

```php
VirtualMuseum::create([
    'situs_id' => $situsId,
    'nama' => 'Museum Virtual ' . $situsNama,
    'path_obj' => 'models/museum.glb',  // 3D model file
    'deskripsi' => 'Jelajahi museum secara virtual...',
]);
```

#### B. Add Virtual Objects

```php
VirtualMuseumObject::create([
    'museum_id' => $museumId,
    'situs_id' => $situsId,
    'nama' => 'Arca Ganesha',
    'path_obj' => 'models/ganesha.glb',
    'path_patt' => 'patterns/ganesha.patt',  // AR marker
    'deskripsi' => 'Patung dewa berkepala gajah...',
]);
```

#### C. Generate AR Route

Add to [routes/web.php](routes/web.php):

```php
Route::get('/situs/{situs_id}/ar/{museum_id}', [HomeController::class, 'arMuseum'])
    ->middleware(['ar.token'])
    ->name('situs.ar');
```

### 6. Configure Access Control

Set initial lock status in [AksesSitusUser](app/Models/AksesSitusUser.php):

```php
// Lock site for all new users (unlocks via progress)
User::all()->each(function ($user) use ($situsId) {
    AksesSitusUser::create([
        'user_id' => $user->id,
        'situs_id' => $situsId,
        'status' => 'terkunci',  // Locked by default
    ]);
});

// Or unlock immediately for testing
$status = 'terbuka';
```

## Post-Creation Checklist

After automated setup, verify:

- ✅ Migration runs without errors: `php artisan migrate`
- ✅ Site appears in admin dashboard list
- ✅ Map marker displays at correct coordinates
- ✅ Thumbnail image loads properly
- ✅ Site detail page accessible
- ✅ AR token generation works (if AR enabled)
- ✅ Access control logic functions (locked/unlocked)
- ✅ Search/filter includes new site

## Manual Steps Required

After automation completes:

1. **Upload Assets**:
    - Thumbnail image → `/storage/app/public/thumbnails/`
    - 3D models → `/storage/app/public/models/`
    - AR markers → `/storage/app/public/patterns/`

2. **Content Creation**:
    - Write detailed Indonesian description
    - Add historical context and dates
    - Link related materi for learning path

3. **AR Marker Generation** (if needed):
    - Use `/generate-ar-marker` skill to create `.patt` file
    - Test marker tracking in AR.js scene

4. **Quality Assurance**:
    - Test on mobile devices
    - Verify geolocation accuracy
    - Check AR model positioning
    - Validate access control flow

## Example Usage

### Minimal Heritage Site

```
Site Name: Museum Nasional Indonesia
Lat: -6.176580
Lng: 106.821680
Description: Museum terkemuka yang menampilkan sejarah dan budaya Indonesia
Category: Museum
```

### AR-Enabled Site

```
Site Name: Candi Borobudur
Lat: -7.607874
Lng: 110.203751
Include AR: Yes
3D Model: borobudur_temple.glb
AR Objects:
  - stupa_main.glb (stupa.patt)
  - relief_panel_1.glb (relief1.patt)
```

## Integration Points

**User Flow**:

1. View site on map (MapsController)
2. Check lock status (AksesSitusUser)
3. Visit site detail page (HomeController)
4. Launch AR experience (ArTokenAuth)
5. Track visit (MuseumUserVisit)
6. Progress to next level (User->incrementLevel)

**Admin Flow**:

1. Create site (AdminController)
2. Upload assets (FileSystem)
3. Create virtual museum (optional)
4. Publish to users
5. Monitor visit statistics

## Troubleshooting

**Issue**: Site not appearing on map  
**Solution**: Check `status` field (must be 'terbuka' for public visibility)

**Issue**: AR experience fails to load  
**Solution**: Verify `path_obj` exists in `/storage/app/public/`, check DRACO compression

**Issue**: Access control not working  
**Solution**: Ensure `akses_situs_user` entries exist for all users, verify middleware stack

**Issue**: Coordinates incorrect  
**Solution**: Use decimal degrees (WGS84), not degrees/minutes/seconds format

## Related Resources

- [AR Development Guidelines](../.github/instructions/ar-development.instructions.md)
- [Migration Patterns](../.github/instructions/migration-patterns.instructions.md)
- [Generate AR Marker Skill](../generate-ar-marker/SKILL.md)
- [SitusPeninggalan Model](../../app/Models/SitusPeninggalan.php)
