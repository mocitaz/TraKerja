<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogoController;
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
    
    // Job detail page
    Route::get('/jobs/{job}', function (JobApplication $job) {
        abort_unless($job->user_id === auth()->id(), 403);
        return view('jobs.show', ['job' => $job]);
    })->name('jobs.show');
});

require __DIR__.'/auth.php';
