<?php

use App\Http\Controllers\ScriptController;
use App\Http\Controllers\TrendingTopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ScriptController::class, 'index'])->name('scripts.index');
Route::post('/generate', [ScriptController::class, 'generate'])->name('scripts.generate');
Route::get('/scripts/{id}', [ScriptController::class, 'show'])->name('scripts.show');
Route::post('/scripts/{id}/regenerate', [ScriptController::class, 'regenerate'])->name('scripts.regenerate');
Route::get('/scripts/{id}/variations', [ScriptController::class, 'variations'])->name('scripts.variations');
Route::get('/scripts/{id}/export/{format}', [ScriptController::class, 'export'])->name('scripts.export');
Route::delete('/scripts/{id}', [ScriptController::class, 'destroy'])->name('scripts.destroy');

Route::get('/trending', [TrendingTopicController::class, 'index'])->name('trending.index');
Route::post('/trending/fetch', [TrendingTopicController::class, 'fetch'])->name('trending.fetch');
Route::post('/trending/{id}/generate', [TrendingTopicController::class, 'generate'])->name('trending.generate');
