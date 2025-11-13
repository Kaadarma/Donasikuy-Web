<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\NewsController;


// Halaman utama (landing page)
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// profile
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// program 
Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/program/{idOrSlug}', [ProgramController::class, 'show'])->name('programs.show');

Route::get('/inspirasi', [NewsController::class, 'index'])->name('inspirasi.index');
Route::get('/inspirasi/{slug}', [NewsController::class, 'show'])->name('inspirasi.show');