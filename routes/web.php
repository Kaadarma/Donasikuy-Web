<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\GalangDanaController;
use App\Http\Controllers\DonasiController;



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

// inspirasi / berita
Route::get('/inspirasi', [NewsController::class, 'index'])->name('inspirasi.index');
Route::get('/inspirasi/{slug}', [NewsController::class, 'show'])->name('inspirasi.show');

// Search
Route::get('/search', [ProgramController::class, 'search'])->name('program.search');


// galang dana
Route::get('/galangdana', [GalangDanaController::class, 'create'])->name('galang.create');

// kategori galangan
Route::get('/galangdana/kategori', [GalangDanaController::class, 'kategori'])
    ->name('galang.kategori');

// donasi 
Route::get('/nominal', [DonasiController::class, 'nominal'])->name('nominal');
Route::get('/datadiri', [DonasiController::class, 'dataDiri'])->name('datadiri');
Route::post('/donasi/proses', [DonasiController::class, 'prosesDonasi'])->name('donasi.proses');
Route::get('/donasi/sukses', [DonasiController::class, 'sukses'])->name('donasi.sukses');
    
// login google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);