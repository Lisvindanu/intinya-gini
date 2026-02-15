<?php

use App\Http\Controllers\ScriptController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ScriptController::class, 'index'])->name('scripts.index');
Route::post('/generate', [ScriptController::class, 'generate'])->name('scripts.generate');
Route::get('/scripts/{id}', [ScriptController::class, 'show'])->name('scripts.show');
Route::get('/scripts/{id}/export/{format}', [ScriptController::class, 'export'])->name('scripts.export');
Route::delete('/scripts/{id}', [ScriptController::class, 'destroy'])->name('scripts.destroy');
