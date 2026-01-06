<?php

use App\Http\Controllers\FileShareController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadedFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public share routes (no authentication required)
Route::get('/share/{token}', [FileShareController::class, 'show'])->name('share.show');
Route::get('/share/{token}/download/{file}', [FileShareController::class, 'download'])->name('share.download');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // File management routes
    Route::resource('files', UploadedFileController::class);
    Route::get('/files/{file}/download', [UploadedFileController::class, 'download'])->name('files.download');

    // QR code generation route
    Route::post('/files/generate-qr', [FileShareController::class, 'generate'])->name('files.generate-qr');
});

require __DIR__.'/auth.php';
