<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;

// ============================================
// AUTHENTICATION ROUTES (Laravel UI)
// ============================================
Auth::routes([
    'register' => false, // Nonaktifkan register (opsional, biar cuma admin yang bisa tambah user)
]);

// ============================================
// REDIRECT ROUTES
// ============================================

// Redirect /home ke dashboard yang sesuai berdasarkan role
Route::get('/home', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('dashboard');
})->name('home');

// Root route - redirect berdasarkan status login dan role
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home.root');

// ============================================
// USER ROUTES (Wajib Login, Semua Role)
// ============================================
Route::middleware('auth')->group(function () {
    
    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Projects Management (User bisa CRUD proyek mereka sendiri)
    Route::resource('projects', ProjectController::class);
    
    // Tasks Management (User bisa CRUD tugas mereka)
    Route::resource('tasks', TaskController::class);
    
    // Kanban Board - Update status task via AJAX
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.updateStatus');
});

// ============================================
// ADMIN ROUTES (Wajib Login + Role Admin)
// ============================================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // ----------------------------------------
        // ADMIN DASHBOARD
        // ----------------------------------------
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
        
        // ----------------------------------------
        // USER MANAGEMENT
        // ----------------------------------------
        Route::resource('users', AdminUserController::class);
        
        // Toggle user status (active/inactive)
        Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggle-status');
        
        // Reset password user
        Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])
            ->name('users.reset-password');
        
        // ----------------------------------------
        // TASKS MANAGEMENT (Admin View - Lihat semua tasks)
        // ----------------------------------------
        Route::controller(AdminTaskController::class)->group(function () {
            Route::get('/tasks', 'index')->name('tasks.index');
            Route::get('/tasks/{task}', 'show')->name('tasks.show');
            Route::get('/tasks/{task}/edit', 'edit')->name('tasks.edit');
            Route::put('/tasks/{task}', 'update')->name('tasks.update');
            Route::delete('/tasks/{task}', 'destroy')->name('tasks.destroy');
        });
        
        // ----------------------------------------
        // ACTIVITY LOGS
        // ----------------------------------------
        Route::controller(AdminActivityLogController::class)->group(function () {
            Route::get('/activity-logs', 'index')->name('activity-logs.index');
            Route::get('/activity-logs/{log}', 'show')->name('activity-logs.show');
            Route::delete('/activity-logs/{log}', 'destroy')->name('activity-logs.destroy');
            Route::post('/activity-logs/clear-old', 'clearOld')->name('activity-logs.clear-old');
        });
        
        // ----------------------------------------
        // SYSTEM SETTINGS
        // ----------------------------------------
        Route::controller(AdminSettingsController::class)->group(function () {
            Route::get('/settings', 'index')->name('settings.index');
            Route::put('/settings', 'update')->name('settings.update');
            Route::post('/settings/reset-all', 'resetAll')->name('settings.reset-all');
            Route::get('/settings/{setting}/edit', 'edit')->name('settings.edit');
            Route::put('/settings/{setting}', 'updateSetting')->name('settings.update-setting');
            Route::post('/settings/{setting}/reset', 'reset')->name('settings.reset');
        });
    });

// ============================================
// FALLBACK ROUTE (404)
// ============================================
Route::fallback(function () {
    return redirect()->route('dashboard')->with('error', 'Halaman tidak ditemukan!');
});