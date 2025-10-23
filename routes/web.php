<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\JobApplicationExportController;
use App\Http\Controllers\CvBuilderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;

Route::get('/', function () {
    return view('welcome');
});

// Force logout route to avoid browser confirm/CSRF issues when session expired
Route::get('/logout-force', function () {
    if (Auth::check()) {
        Auth::logout();
    }
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.force');

Route::get('/tracker', [TrackerController::class, 'index'])->middleware(['auth', 'verified'])->name('tracker');
Route::get('/summary', [SummaryController::class, 'index'])->middleware(['auth', 'verified'])->name('summary');
Route::get('/goals', [GoalsController::class, 'index'])->middleware(['auth', 'verified'])->name('goals');
Route::get('/interviews', function () {
    return view('interviews.calendar');
})->middleware(['auth', 'verified'])->name('interviews');
Route::get('/dashboard', function () {
    return redirect()->route('tracker');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Password verification route
    Route::post('/password/verify', [ProfileController::class, 'verifyPassword'])->name('password.verify');
    
    // Profile Photo routes
    Route::post('/profile-photo/upload', [LogoController::class, 'upload'])->name('profile-photo.upload');
    Route::delete('/profile-photo/delete', [LogoController::class, 'delete'])->name('profile-photo.delete');
    Route::get('/profile-photo/get', [LogoController::class, 'getLogo'])->name('profile-photo.get');
    
    // CV Builder routes
    Route::prefix('cv')->name('cv.')->group(function () {
        Route::get('/builder', [CvBuilderController::class, 'index'])->name('builder');
        Route::get('/generator', [CvBuilderController::class, 'generator'])->name('generator');
        Route::post('/export', [CvBuilderController::class, 'export'])->name('export');
    });
    
    // Job detail page
    Route::get('/jobs/{job}', function (JobApplication $job) {
        abort_unless($job->user_id === Auth::id(), 403);
        return view('jobs.show', ['job' => $job]);
    })->name('jobs.show');
    
    // Export routes
    Route::get('/export/job-applications/csv', [JobApplicationExportController::class, 'exportToCsv'])->name('export.job-applications.csv');
    Route::get('/export/job-applications/stats', [JobApplicationExportController::class, 'getExportStats'])->name('export.job-applications.stats');
});

// Admin Routes (protected by admin role)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard - Check admin role
    Route::get('/', function () {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }
        return view('admin.index');
    })->name('index');
    
    // Monetization control
    Route::get('/monetization', function () {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }
        return view('admin.index'); // Shows monetization control on main dashboard
    })->name('monetization');
    
    // Users management
    Route::get('/users', function () {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }
        return view('admin.users');
    })->name('users');
    
    // Payments monitoring
    Route::get('/payments', function () {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }
        return view('admin.payments');
    })->name('payments');
    
    // Settings
    Route::get('/settings', function () {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }
        return view('admin.settings');
    })->name('settings');
    
    // Analytics
    Route::get('/analytics', function () {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }
        return view('admin.analytics');
    })->name('analytics');
});

require __DIR__.'/auth.php';
