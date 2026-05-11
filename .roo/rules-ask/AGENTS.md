# AGENTS.md - Ask Mode

This file provides guidance to agents when explaining code in this repository.

## Project Structure Overview

**Virtual Living Museum** is an AR-enhanced heritage education platform built with Laravel 11 (PHP 8.2+).

**Three Main Subsystems**:

1. **E-Learning**: Pre-test → E-book → Virtual Museum → Post-test progressive flow
2. **AR Experiences**: Dual approach - marker-based (AR.js) and WebXR (Three.js)
3. **Heritage Mapping**: Geolocation-based site exploration

## Key Files by Topic

### Progressive Learning

- `app/Models/User.php` - `incrementLevel()`, `incrementProgressLevel()`, progress constants
- `app/Models/Materi.php` - `shouldIncrementProgress()`, ordering logic
- `app/Models/JawabanUser.php` - Pivot for test answers

### AR Implementation

- `public/assets/js/ar-museum-*.js` - WebXR Three.js implementation
- `public/js/gesture-detector.js` / `gesture-handler.js` - Touch gesture controls
- `app/Helper/TokenHelper.php` - HMAC-SHA256 token generation
- `app/Http/Middleware/ArTokenAuth.php` - Stateless AR authentication

### Database Models

- `app/Models/SitusPeninggalan.php` - Heritage sites with geolocation
- `app/Models/VirtualMuseum.php` - 3D museum scenes per site
- `app/Models/VirtualMuseumObject.php` - Individual AR objects

### Admin Management

- `app/Http/Controllers/Admin/AdminController.php` - Content CRUD
- `app/Http/Controllers/Admin/KatalogController.php` - AR catalog management
- `resources/views/admin/` - Admin Blade views

## Documentation Files

- `CLAUDE.md` - Full project documentation (most comprehensive)
- `GEMINI.md` - Additional project overview
- `.github/copilot-instructions.md` - Detailed Copilot guidance
- `.github/instructions/` - Subsystem-specific guidelines:
    - `ar-development.instructions.md` - AR patterns
    - `assessment-system.instructions.md` - Pre/post-test logic
    - `migration-patterns.instructions.md` - Database conventions
