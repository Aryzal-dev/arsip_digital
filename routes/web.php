
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {

    // =========================
    // ARSIP: AKSES PENGGUNA
    // =========================

    // Daftar arsip
    Route::get('/arsip', [ArsipController::class, 'index'])
        ->name('arsip.index');

    // Export arsip ke Excel
    Route::get('/arsip/export/excel', [ArsipController::class, 'export'])
        ->name('arsip.export');

    // Download template import Excel
    Route::get('/arsip/import/template', [ArsipController::class, 'downloadTemplate'])
        ->name('arsip.template');

    // Detail arsip
    Route::get('/arsip/{id}', [ArsipController::class, 'show'])
        ->whereNumber('id')
        ->name('arsip.show');

    // Download file arsip
    Route::get('/arsip/{id}/download', [ArsipController::class, 'download'])
        ->whereNumber('id')
        ->name('arsip.download');

    // =========================
    // KHUSUS ADMIN
    // =========================

    Route::middleware('admin')->group(function () {

        // Import arsip dari Excel
        Route::post('/arsip/import/excel', [ArsipController::class, 'import'])
            ->name('arsip.import');

        // Tambah arsip
        Route::get('/arsip/create', [ArsipController::class, 'create'])
            ->name('arsip.create');

        Route::post('/arsip', [ArsipController::class, 'store'])
            ->name('arsip.store');

        // Edit arsip
        Route::get('/arsip/{id}/edit', [ArsipController::class, 'edit'])
            ->whereNumber('id')
            ->name('arsip.edit');

        Route::put('/arsip/{id}', [ArsipController::class, 'update'])
            ->whereNumber('id')
            ->name('arsip.update');

        // Hapus arsip
        Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])
            ->whereNumber('id')
            ->name('arsip.destroy');

        // Manajemen pengguna
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::patch('/users/{id}/role', [UserController::class, 'updateRole'])
            ->whereNumber('id')
            ->name('users.updateRole');

        Route::delete('/users/{id}', [UserController::class, 'destroy'])
            ->whereNumber('id')
            ->name('users.destroy');

        // Log aktivitas
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs.index');
    });

    // =========================
    // PROFIL PENGGUNA
    // =========================

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Route autentikasi Breeze
require __DIR__ . '/auth.php';
