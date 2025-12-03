<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\GalangDanaController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;

// =====================
// HALAMAN UTAMA
// =====================

// Root (/) -> Landing/home page yang pakai home.blade.php
Route::get('/', [LandingController::class, 'index'])->name('landing');
// pastikan di LandingController@index kamu return view('home');


// =====================
// AUTH MANUAL (LOGIN / REGISTER)
// =====================

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =====================
// PROFIL & DASHBOARD
// =====================

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


// =====================
// PROGRAM DONASI
// =====================

Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/program/{idOrSlug}', [ProgramController::class, 'show'])->name('programs.show');

// Search
Route::get('/search', [ProgramController::class, 'search'])->name('program.search');

// Inspirasi / berita
Route::get('/inspirasi', [NewsController::class, 'index'])->name('inspirasi.index');
Route::get('/inspirasi/{slug}', [NewsController::class, 'show'])->name('inspirasi.show');


// =====================
// GALANG DANA
// =====================

Route::get('/galangdana', [GalangDanaController::class, 'create'])->name('galang.create');
Route::get('/galangdana/kategori', [GalangDanaController::class, 'kategori'])->name('galang.kategori');


// =====================
// DONASI
// =====================

Route::get('/nominal', [DonasiController::class, 'nominal'])->name('nominal');
Route::get('/datadiri', [DonasiController::class, 'dataDiri'])->name('datadiri');
Route::post('/donasi/proses', [DonasiController::class, 'prosesDonasi'])->name('donasi.proses');
Route::get('/donasi/sukses', [DonasiController::class, 'sukses'])->name('donasi.sukses');


// =====================
// LOGIN DENGAN GOOGLE (Socialite)
// =====================

// Cukup pakai GoogleController saja
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('auth.google.callback');

// dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');
});
