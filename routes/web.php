<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/signup', [ParticipantController::class, 'store'])->name('signup.store');

Route::get('/match', function () {
    return view('match');
})->name('match');