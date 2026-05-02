<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesPageController;
use Illuminate\Support\Facades\Route;

// ── Landing page → redirect to app ───────────────────────────────────────
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('sales-pages.index')
        : view('landing');
});

// ── Dashboard redirect (keep Breeze happy) ────────────────────────────────
Route::get('/dashboard', function () {
    return redirect()->route('sales-pages.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Authenticated routes ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Sales Pages ───────────────────────────────────────────────────────
    Route::resource('sales-pages', SalesPageController::class)
        ->only(['index', 'create', 'show', 'destroy']);

    // store & regenerate call OpenAI — rate-limited to 5 requests/minute per user
    Route::post('sales-pages', [SalesPageController::class, 'store'])
        ->name('sales-pages.store')
        ->middleware('throttle:openai');

    Route::post('sales-pages/{salesPage}/regenerate', [SalesPageController::class, 'regenerate'])
        ->name('sales-pages.regenerate')
        ->middleware('throttle:openai');

    Route::get('sales-pages/{salesPage}/export', [SalesPageController::class, 'export'])
        ->name('sales-pages.export');

    Route::get('sales-pages/{salesPage}/status', [SalesPageController::class, 'status'])
        ->name('sales-pages.status');
});

require __DIR__.'/auth.php';
