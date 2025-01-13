<?php

use App\Http\Controllers\AdminController;
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

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::get('/participants', [AdminController::class, 'participants'])->name('admin.participants');
        Route::put('/participants/{participant}', [AdminController::class, 'updateParticipant'])->name('admin.participants.update');
        Route::put('/participants/bulk-update', [AdminController::class, 'bulkUpdateParticipants'])->name('admin.participants.bulk-update');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
