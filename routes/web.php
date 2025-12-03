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
use App\Http\Controllers\KycController;
use App\Http\Controllers\ProfileController;

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
});

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

// kyc verification
Route::middleware(['auth'])->group(function () {
    Route::prefix('kyc')->name('kyc.')->group(function () {

        // Bagian 1 - Informasi Dasar
        Route::get('/step-1', [KycController::class, 'step1'])->name('step1');
        Route::post('/step-1', [KycController::class, 'storeStep1'])->name('step1.store');

        // Bagian 2
        Route::get('/step-2', [KycController::class, 'step2'])->name('step2');
        Route::post('/step-2', [KycController::class, 'storeStep2'])->name('step2.store');

        // Bagian 3 - Identitas Pemegang Akun
        Route::get('/step-3', [KycController::class, 'step3'])->name('step3');
        Route::post('/step-3', [KycController::class, 'storeStep3'])->name('step3.store');

        // Bagian 4 - Informasi Pencairan Dana
        Route::get('/step-4', [KycController::class, 'step4'])->name('step4');
        Route::post('/step-4', [KycController::class, 'storeStep4'])->name('step4.store');

        // Opsional: halaman selesai
        Route::get('/completed', [KycController::class, 'completed'])->name('completed');
    });
    
});

    // forgot password
    // FORGOT PASSWORD
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'updatePassword'])
    ->name('password.update');

// verif galang dana    
Route::middleware('auth')->group(function () {
    Route::get('/galang-dana/create', [GalangDanaController::class, 'create'])->name('galang.create');
    Route::get('/galang-dana/kategori', [GalangDanaController::class, 'kategori'])->name('galang.kategori');
    
    // kalau nanti ada POST untuk simpan galang dana:
    // Route::post('/galang-dana', [GalangDanaController::class, 'store'])->name('galang.store');
});