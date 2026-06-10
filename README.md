# Smart Prasada

Smart Prasada is an interactive web platform designed for virtual heritage exploration, combining Augmented Reality (AR), 3D virtual museums, interactive 360° VR panoramas, and a gamified e-learning system. The project is built using Laravel 11, Vite, and Tailwind CSS.

## Capabilities

- **Virtual Exploration & 3D WebXR:** Immersive 3D and visual navigation of museum artifacts and heritage objects using WebXR (Three.js/A-Frame).
- **360° VR Panorama Tour:** Create, edit, and explore interactive 360° panorama virtual tours with custom hotspot templates (navigation, info modals, text labels) integrated directly with heritage sites.
- **Augmented Reality (AR):** Marker-based AR integration and WebXR support for immersive cross-device learning.
- **E-Learning & Progressive System:**
    - **Structured Flow:** A linear gamified system (Pre-Test → E-Book with flipbook functionality → Virtual Living Museum / 3D Scene → Post-Test) mapped across historical eras (from Prehistory to Post-Independence).
    - **Progress Tracking:** Interactive student report cards (Rapor) and levels.
    - **Assessment System:** Comprehensive Pre-Tests and Post-Tests.
    - **Assignment Management:** Portal for student assignment submissions.
- **Heritage Mapping:** Geographical mapping and visualization of heritage sites (Situs Peninggalan) with latitude and longitude.
- **Engagement Tools:** Feedback, criticism/suggestions, and detailed error/report submission systems.
- **Management Dashboard:** A complete administrative control center to manage eras, learning materials (Materi), quizzes/assessments, virtual tours, uploaded 3D assets, feedback, and student progress.

## Technologies

- **Core:** PHP 8.4+ and Laravel 11
- **Frontend:** Blade, Tailwind CSS, and Alpine.js
- **Build System:** Vite (v8.x)
- **Specialized Libraries:** Three.js, A-Frame, n8ao, page-flip, postprocessing
- **Database:** MySQL

## Setup Guide

Follow these instructions to set up the development environment locally.

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL Database

### Installation

1.  **Clone the repository**

    ```bash
    git clone https://github.com/Andndre/prototype_smart_prasada.git
    cd prototype_smart_prasada
    ```

2.  **Install PHP dependencies**

    ```bash
    composer install
    ```

3.  **Install frontend dependencies**

    ```bash
    npm install
    ```

4.  **Environment configuration**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Configure your database credentials and add the `APP_TOKEN_SECRET` in the `.env` file (required for token-based AR authentication):
    ```env
    APP_TOKEN_SECRET=your_32_character_random_secret
    ```

5.  **Database initialization**
    Run the migrations and seed the initial data (which includes full historical content and test users):

    ```bash
    php artisan migrate --seed
    # Or to rebuild and seed everything fresh:
    php artisan migrate:fresh --seed
    ```

6.  **Run development servers**
    Start both the Laravel server and Vite development build concurrently:
    ```bash
    composer run dev
    ```

### Test Users
The database seeders prepare the following default accounts for testing:
- **Admin**: `admin@gmail.com` / `password`
- **Students / Users**: `siswa@example.com` / `password` or `test@example.com` / `password`

## Testing

The project utilizes Pest for unit and feature testing.

```bash
php artisan test
```

## Directory Structure

- `app/Models`: Core data structures and Eloquent models (using Indonesian conventions and explicit primary keys).
- `app/Http/Controllers`: Logic for administration, learning features, and AR/VR modules.
- `app/Helper`: Helper utilities (e.g. token-based cross-device AR/VR authorization).
- `public/assets/js`: Direct-served AR, 3D, and Panorama viewer scripts (not compiled by Vite to ensure performance).
- `resources/js`: Frontend JavaScript, state management (Alpine.js), and page-flip flipbook components.
- `routes/web.php`: Application routing categorized by guest, student/user, and admin access levels.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

