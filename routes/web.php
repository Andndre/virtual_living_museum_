<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('guest.home');
});

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.change');

Route::group(['middleware' => ['auth', 'user']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('guest.home');
    Route::get('/panduan', [HomeController::class, 'panduan'])->name('guest.panduan');
    Route::get('/pengaturan', [HomeController::class, 'pengaturan'])->name('guest.pengaturan');
    Route::get('/pengembang', [HomeController::class, 'pengembang'])->name('guest.pengembang');
    Route::get('/ar-marker', [HomeController::class, 'arMarker'])->name('guest.ar-marker');
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Admin management routes
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/materi', [AdminController::class, 'materi'])->name('admin.materi');
    Route::get('/admin/situs', [AdminController::class, 'situs'])->name('admin.situs');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/feedback', [AdminController::class, 'feedback'])->name('admin.feedback');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
