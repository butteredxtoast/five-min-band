<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BandController;
use App\Http\Controllers\MusicianController;
use App\Http\Controllers\ProfileController;
use App\Models\Musician;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/signup', [MusicianController::class, 'store'])->name('signup.store');

// Auth Routes (from Breeze)
require __DIR__.'/auth.php';

if (app()->environment('local')) {
    Route::get('/dev/generate-musician', function() {
        $musician = Musician::factory()->create();
        return redirect()
            ->route('musicians.show', $musician)
            ->with('success', 'Random musician generated!');
    });
}

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['verified'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::get('/musicians', [AdminController::class, 'musicians'])->name('musicians');
        Route::post('/musicians/bulk-activate', [AdminController::class, 'bulkActivateMusicians'])->name('musicians.bulk-activate');
        Route::post('/musicians/bulk-deactivate', [AdminController::class, 'bulkDeactivateMusicians'])->name('musicians.bulk-deactivate');
        Route::put('/musicians/bulk-update', [AdminController::class, 'bulkUpdateMusicians'])->name('musicians.bulk-update');
        Route::put('/musicians/{musician}', [AdminController::class, 'updateMusician'])->name('musicians.update');

        // Admin Create Band Routes
        Route::prefix('bands')->name('bands.')->group(function () {
            Route::get('/', [AdminController::class, 'bands'])->name('index');
            Route::get('/create', function () {
                return view('admin.bands.create');
            })->name('create');
            Route::post('/generate', [BandController::class, 'generate'])->name('generate');
        });
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/test-web', function() {
        return response()->json(['message' => 'Web route is working']);
    });
});
