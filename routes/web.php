<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/signup', [ParticipantController::class, 'store'])->name('signup.store');

// Auth Routes (from Breeze)
require __DIR__.'/auth.php';

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/match', function () {
        return view('match');
    })->name('match');

    Route::post('/match/generate', [MatchController::class, 'generate'])->name('match.generate');

    // Admin Routes
    Route::middleware(['verified'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/matches', [AdminController::class, 'matches'])->name('matches');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::get('/participants', [AdminController::class, 'participants'])->name('participants');
        Route::post('/participants/bulk-activate', [AdminController::class, 'bulkActivateParticipants'])->name('participants.bulk-activate');
        Route::post('/participants/bulk-deactivate', [AdminController::class, 'bulkDeactivateParticipants'])->name('participants.bulk-deactivate');
        Route::put('/participants/bulk-update', [AdminController::class, 'bulkUpdateParticipants'])->name('participants.bulk-update');
        Route::put('/participants/{participant}', [AdminController::class, 'updateParticipant'])->name('participants.update');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/test-web', function() {
        return response()->json(['message' => 'Web route is working']);
    });
});
