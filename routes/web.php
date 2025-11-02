<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\JobApplicationExportController;
use App\Http\Controllers\JobApplicationImportExportController;
use App\Http\Controllers\CvBuilderController;
use App\Http\Controllers\AiAnalyzerController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\JobApplication;

Route::get('/', function () {
    return view('welcome');
});

// Legal Pages
Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('legal.terms');

Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('legal.privacy');

// Force logout route to avoid browser confirm/CSRF issues when session expired
Route::get('/logout-force', function () {
    if (Auth::check()) {
        Auth::logout();
    }
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.force');

// Job Tracker Routes (Only for regular users, not admin)
Route::get('/tracker', function () {
    if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
        return redirect()->route('admin.index');
    }
    return app(TrackerController::class)->index();
})->middleware(['auth', 'verified'])->name('tracker');

Route::get('/summary', function (Request $request) {
    if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
        return redirect()->route('admin.index');
    }
    return app(SummaryController::class)->index($request);
})->middleware(['auth', 'verified'])->name('summary');

Route::get('/goals', function () {
    if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
        return redirect()->route('admin.index');
    }
    return app(GoalsController::class)->index();
})->middleware(['auth', 'verified'])->name('goals');

Route::get('/interviews', function () {
    if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
        return redirect()->route('admin.index');
    }
    return view('interviews.calendar');
})->middleware(['auth', 'verified'])->name('interviews');

Route::get('/dashboard', function () {
    if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
        return redirect()->route('admin.index');
    }
    return redirect()->route('tracker');
})->middleware(['auth', 'verified'])->name('dashboard');

// User Routes (Not accessible by admin)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/personal', [ProfileController::class, 'updatePersonalInfo'])->name('profile.personal.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Password verification route
    Route::post('/password/verify', [ProfileController::class, 'verifyPassword'])->name('password.verify');
    
    // Profile Photo routes (new methods in ProfileController)
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
    
    // Legacy Profile Photo routes (LogoController for AJAX)
    Route::post('/profile-photo/upload', [LogoController::class, 'upload'])->name('profile-photo.upload');
    Route::delete('/profile-photo/delete', [LogoController::class, 'delete'])->name('profile-photo.delete');
    Route::get('/profile-photo/get', [LogoController::class, 'getLogo'])->name('profile-photo.get');
    
    // CV Builder routes (Only for regular users)
    Route::prefix('cv')->group(function () {
        Route::get('/builder', function () {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                return redirect()->route('admin.index');
            }
            return app(CvBuilderController::class)->index();
        })->name('cv.builder');
        
        Route::get('/generator', function () {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                return redirect()->route('admin.index');
            }
            return app(CvBuilderController::class)->generator();
        })->name('cv.generator');
        
        Route::post('/preview', function (Illuminate\Http\Request $request) {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                abort(403, 'Admin cannot access CV builder');
            }
            return app(CvBuilderController::class)->preview($request);
        })->name('cv-builder.preview');
        
        Route::post('/export', function (Illuminate\Http\Request $request) {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                abort(403, 'Admin cannot access CV builder');
            }
            return app(CvBuilderController::class)->export($request);
        })->name('cv-builder.export');
    });
    
    // AI Analyzer routes (Only for regular users)
    Route::prefix('ai-analyzer')->group(function () {
        Route::get('/', function () {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                return redirect()->route('admin.index');
            }
            return app(AiAnalyzerController::class)->index();
        })->name('ai-analyzer.index');
        
        Route::post('/analyze', function (Request $request) {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                abort(403, 'Admin cannot access AI Analyzer');
            }
            return app(AiAnalyzerController::class)->analyze($request);
        })->name('ai-analyzer.analyze');
    });
    
    // Job detail page (Only for regular users)
    Route::get('/jobs/{job}', function (JobApplication $job) {
        if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
            return redirect()->route('admin.index');
        }
        abort_unless($job->user_id === Auth::id(), 403);
        return view('jobs.show', ['job' => $job]);
    })->name('jobs.show');
    
    // CSV Import/Export routes (Only for regular users)
    Route::prefix('csv')->group(function () {
        // Download CSV template
        Route::get('/template', function () {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                abort(403, 'Admin cannot access CSV features');
            }
            return app(JobApplicationImportExportController::class)->downloadTemplate();
        })->name('csv.template');
        
        // Export job applications to CSV
        Route::get('/export', function () {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                abort(403, 'Admin cannot export job applications');
            }
            return app(JobApplicationImportExportController::class)->exportToCsv();
        })->name('csv.export');
        
        // Show import form
        Route::get('/import', function () {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                abort(403, 'Admin cannot import job applications');
            }
            return app(JobApplicationImportExportController::class)->showImportForm();
        })->name('csv.import');
        
        // Process CSV import
        Route::post('/import', function (Request $request) {
            if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
                abort(403, 'Admin cannot import job applications');
            }
            return app(JobApplicationImportExportController::class)->importFromCsv($request);
        })->name('csv.import.process');
    });
    
    // Interview reminder email trigger (for testing/integration)
    Route::post('/jobs/{job}/send-interview-reminder', function (JobApplication $job) {
        if (Auth::user()->isAdmin() || Auth::user()->role === 'admin') {
            abort(403, 'Admin cannot send interview reminders');
        }
        abort_unless($job->user_id === Auth::id(), 403);
        $sent = $job->sendInterviewReminder();
        if ($sent) {
            return response()->json(['success' => true, 'message' => 'Interview reminder email sent.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Premium required to send interview reminder.']);
        }
    })->middleware(['auth', 'verified'])->name('jobs.send-interview-reminder');
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
        return view('admin.monetization');
    })->name('monetization');
    
    // Update premium price
    Route::put('/update-premium-price', function (Request $request) {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }
        
        $validated = $request->validate([
            'premium_price' => 'required|numeric|min:0'
        ]);
        
        \App\Models\Setting::set('premium_price', $validated['premium_price']);
        \App\Models\Setting::clearCache();
        
        return redirect()->route('admin.monetization')
            ->with('success', 'Premium price updated successfully to Rp ' . number_format($validated['premium_price'], 0, ',', '.'));
    })->name('update-premium-price');
    
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
    
    // Analytics
    Route::get('/analytics', function () {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access required');
        }
        return view('admin.analytics');
    })->name('analytics');
});

// Payment Routes (YUKK Payment Gateway)
Route::middleware(['auth'])->prefix('payment')->name('payment.')->group(function () {
    // Payment selection page
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    
    // Checkout and create payment
    Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    
    // Check payment status (AJAX)
    Route::get('/check-status/{orderId}', [PaymentController::class, 'checkStatus'])->name('check-status');
    
    // Payment result pages
    Route::get('/success/{orderId}', [PaymentController::class, 'success'])->name('success');
    Route::get('/failed/{orderId}', [PaymentController::class, 'failed'])->name('failed');
    Route::get('/waiting/{orderId}', [PaymentController::class, 'waiting'])->name('waiting');
});

// Payment Callback & Webhook (no auth middleware)
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

require __DIR__.'/auth.php';
