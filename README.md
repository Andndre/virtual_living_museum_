# Virtual Living Museum

Virtual Living Museum is an interactive web platform designed to facilitate virtual heritage exploration through Augmented Reality (AR) and a comprehensive e-learning system. The project is built using Laravel 11 and Vite.

## Capabilities

- **Virtual Exploration:** Interactive 3D and visual navigation of museum artifacts and heritage objects.
- **Augmented Reality:** Marker-based AR integration using `ArMarkerCameraController` for immersive educational experiences.
- **E-Learning System:**
    - Learning materials and interactive e-books with flipbook functionality.
    - Assessment system featuring pre-tests and post-tests to track learning progress.
    - Assignment management for student submissions.
- **Heritage Mapping:** Geographic visualization of heritage sites (Situs Peninggalan).
- **Engagement Tools:** User reporting, feedback systems, and progress tracking (Rapor).
- **Management Dashboard:** Administrative interface for content, user, and report management.

## Technologies

- **Core:** PHP 8.2+ and Laravel 11
- **Frontend:** Blade, Tailwind CSS, and Alpine.js
- **Build System:** Vite (v6.x)
- **Specialized Libraries:** n8ao, page-flip, postprocessing
- **Database:** MySQL

## Setup Guide

Follow these instructions to set up the development environment locally.

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and NPM

### Installation

1.  **Clone the repository**

    ```bash
    git clone https://github.com/Andndre/virtual_living_museum_.git
    cd virtual_living_museum_
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

    Configure the database credentials in the `.env` file.

5.  **Database initialization**
    Run the migrations and seed the initial data:

    ```bash
    php artisan migrate --seed
    ```

6.  **Run development servers**
    Start both the Laravel server and Vite development build:
    ```bash
    composer run dev
    ```

## Testing

The project utilizes Pest for unit and feature testing.

```bash
php artisan test
```

## Directory Structure

- `app/Models`: Core data structures and Eloquent models.
- `app/Http/Controllers`: Logic for administration, guest access, and AR features.
- `resources/js`: Implementation of AR logic, 3D interactions, and frontend components.
- `routes/web.php`: Application routing categorized by access level.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
