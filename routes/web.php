<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ListenerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

// Caller routes
Route::middleware(['auth', 'verified', 'caller'])->group(function () {
    Route::get('/listeners', [ListController::class, 'index'])->name('listeners.index');
    Route::post('/calls', [CallController::class, 'store'])->name('calls.store');
    Route::get('/donate/{call}', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
});

// Shared call view (both caller & listener)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/call/{call}', [CallController::class, 'show'])->name('calls.show');
    Route::delete('/calls/{call}', [CallController::class, 'end'])->name('calls.end');
    Route::post('/signal/{call}', [SignalController::class, 'send'])->name('signal.send');
});

// Listener routes
Route::middleware(['auth', 'verified', 'listener'])->group(function () {
    Route::get('/listener/dashboard', [ListenerController::class, 'dashboard'])->name('listener.dashboard');
    Route::post('/listener/status', [ListenerController::class, 'updateStatus'])->name('listener.status');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

// Profile (all auth users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
