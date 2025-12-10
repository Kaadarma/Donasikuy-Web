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
use App\Http\Controllers\DanaPuniaController;


Route::get('/', [LandingController::class, 'index'])->name('landing');

//login
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
Route::get('/programs/{idOrSlug}', [ProgramController::class, 'show'])->name('programs.show');

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

Route::get('/donasi/{slug}/nominal', [DonasiController::class, 'nominal'])
    ->name('donasi.nominal');

Route::get('/donasi/{slug}/data-diri', [DonasiController::class, 'dataDiri'])
    ->name('donasi.dataDiri');

// 🔥 jalur ke MIDTRANS
Route::post('/donasi/{slug}/proses', [DonasiController::class, 'proses'])
    ->name('donasi.proses');

// halaman terima kasih, nanti dipakai setelah pembayaran
Route::get('/donasi/sukses', [DonasiController::class, 'sukses'])
    ->name('donasi.sukses');


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

// dana punia
Route::get('/dana-punia', [DanaPuniaController::class, 'index'])->name('dana-punia.index');

// pembayaran
Route::post('/donasi/{program:slug}/proses', [DonasiController::class, 'proses'])->name('donasi.proses');
Route::get('/pembayaran/{kode}', [PembayaranController::class, 'show']) ->name('pembayaran.show');

