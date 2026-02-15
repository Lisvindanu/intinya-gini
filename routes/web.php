<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\TrendingTopicController;
use Illuminate\Support\Facades\Route;

// Public landing page - redirect to login if not authenticated
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Protected dashboard and script routes - require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [ScriptController::class, 'index'])->name('dashboard');
    
    // Script management
    Route::post('/generate', [ScriptController::class, 'generate'])->name('scripts.generate');
    Route::get('/scripts/{id}', [ScriptController::class, 'show'])->name('scripts.show');
    Route::post('/scripts/{id}/regenerate', [ScriptController::class, 'regenerate'])->name('scripts.regenerate');
    Route::get('/scripts/{id}/variations', [ScriptController::class, 'variations'])->name('scripts.variations');
    Route::get('/scripts/{id}/export/{format}', [ScriptController::class, 'export'])->name('scripts.export');
    Route::delete('/scripts/{id}', [ScriptController::class, 'destroy'])->name('scripts.destroy');
    
    // Trending topics
    Route::get('/trending', [TrendingTopicController::class, 'index'])->name('trending.index');
    Route::post('/trending/fetch', [TrendingTopicController::class, 'fetch'])->name('trending.fetch');
    Route::post('/trending/{id}/generate', [TrendingTopicController::class, 'generate'])->name('trending.generate');
    
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';