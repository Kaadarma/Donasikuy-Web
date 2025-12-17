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
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKycController;

Route::get('/', [LandingController::class, 'index'])
    ->name('landing');

// =====================
// LOGIN & REGISTER
// =====================
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.attempt');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// =====================
// PROFIL & DASHBOARD
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile/edit', [ProfileController::class, 'update'])
        ->name('profile.update');
});

// =====================
// DASHBOARD (UTAMA)
// =====================
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index');
    
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index');


Route::middleware('auth')
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('/donations', [DashboardController::class, 'donationsIndex'])
            ->name('donations.index');
    });
   

// =====================
// PROGRAM DONASI
// =====================
Route::get('/programs', [ProgramController::class, 'index'])
    ->name('programs.index');

Route::get('/programs/{idOrSlug}', [ProgramController::class, 'show'])
    ->name('programs.show');

Route::get('/search', [ProgramController::class, 'search'])
    ->name('program.search');

// =====================
// INSPIRASI / BERITA
// =====================
Route::get('/inspirasi', [NewsController::class, 'index'])
    ->name('inspirasi.index');

Route::get('/inspirasi/{slug}', [NewsController::class, 'show'])
    ->name('inspirasi.show');

// =====================
// GALANG DANA (KYC VERIFIED)
// =====================
Route::middleware(['auth', 'kyc.verified'])
    ->prefix('galang-dana')
    ->name('galang.')
    ->group(function () {

        Route::get('/create', [GalangDanaController::class, 'create'])
            ->name('create');

        Route::get('/form', [GalangDanaController::class, 'form'])
            ->name('form');

        Route::post('/store', [GalangDanaController::class, 'store'])
            ->name('store');

        Route::get('/{program}/kategori', [GalangDanaController::class, 'kategori'])
            ->name('kategori');

        Route::post('/{program}/kategori', [GalangDanaController::class, 'storeKategori'])
            ->name('storeKategori');
    });

// =====================
// DONASI
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/donasi/{slug}/nominal', [DonasiController::class, 'nominal'])
        ->name('donasi.nominal');

    Route::get('/donasi/{slug}/data-diri', [DonasiController::class, 'dataDiri'])
        ->name('donasi.dataDiri');

    Route::post('/donasi/{slug}/proses', [DonasiController::class, 'proses'])
        ->name('donasi.proses');

    Route::get('/donasi/sukses', [DonasiController::class, 'sukses'])
        ->name('donasi.sukses');
});

// =====================
// AUTH GOOGLE
// =====================
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('auth.google.callback');


// =====================
// DASHBOARD CAMPAIGNS
// =====================
Route::middleware(['auth', 'kyc.verified'])
    ->prefix('dashboard/campaigns')
    ->name('dashboard.campaigns.')
    ->group(function () {

        // MASTER (ringkasan semua status)
        Route::get('/', [DashboardController::class, 'campaignsIndex'])
            ->name('index');

        // SUB LIST
        Route::get('/running',  [DashboardController::class, 'campaignsRunning'])
            ->name('running');

        Route::get('/review',   [DashboardController::class, 'campaignsReview'])
            ->name('review');

        Route::get('/drafts',   [DashboardController::class, 'campaignsDrafts'])
            ->name('drafts');

        Route::get('/rejected', [DashboardController::class, 'campaignsRejected'])
            ->name('rejected');

        // batalkan draft
        Route::delete('/{program}', [DashboardController::class, 'campaignDestroy'])->name('destroy');
    
        // EDIT (khusus draft & rejected)
        Route::get('/{program}/edit', [DashboardController::class, 'campaignEdit'])
            ->name('edit');

        Route::put('/{program}', [DashboardController::class, 'campaignUpdate'])
            ->name('update');

        // SUBMIT ke admin (ubah status -> pending)
        Route::post('/{program}/submit', [DashboardController::class, 'campaignSubmit'])
            ->name('submit');

        // MANAGE (khusus running/approved)
        Route::get('/{program}/manage', [DashboardController::class, 'campaignManage'])
            ->name('manage');

        // OPTIONAL: page "draft tersimpan"
        Route::get('/{program}/saved', [DashboardController::class, 'campaignSaved'])
            ->name('saved');

        // DETAIL (read-only) — TARUH PALING BAWAH (biar gak nabrak route di atas)
        Route::get('/{program}', [DashboardController::class, 'campaignShow'])
            ->whereNumber('program')
            ->name('show');

        
        Route::get('/{program}/manage', [DashboardController::class, 'campaignManage'])->name('manage');

        Route::post('/{program}/updates', [DashboardController::class, 'campaignStoreUpdate'])->name('updates.store');
        Route::delete('/{program}/updates/{update}', [DashboardController::class, 'campaignUpdateDestroy'])->name('updates.destroy');

        Route::post('/{program}/disbursements', [DashboardController::class, 'campaignStoreDisbursement'])->name('disbursements.store');


    });



// =====================
// KYC VERIFICATION
// =====================
Route::middleware('auth')->group(function () {
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/step-1', [KycController::class, 'step1'])
            ->name('step1');

        Route::post('/step-1', [KycController::class, 'storeStep1'])
            ->name('step1.store');

        Route::get('/step-2', [KycController::class, 'step2'])
            ->name('step2');

        Route::post('/step-2', [KycController::class, 'storeStep2'])
            ->name('step2.store');

        Route::get('/step-3', [KycController::class, 'step3'])
            ->name('step3');

        Route::post('/step-3', [KycController::class, 'storeStep3'])
            ->name('step3.store');

        Route::get('/step-4', [KycController::class, 'step4'])
            ->name('step4');

        Route::post('/step-4', [KycController::class, 'storeStep4'])
            ->name('step4.store');

        Route::get('/completed', [KycController::class, 'completed'])
            ->name('completed');
    });
});

// =====================
// FORGOT PASSWORD
// =====================
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'updatePassword'])
    ->name('password.update');

// =====================
// VERIF GALANG DANA
// =====================
Route::middleware(['auth', 'kyc.verified'])->group(function () {
    Route::get('/galang-dana/create', [GalangDanaController::class, 'create'])
        ->name('galang.create');

    Route::get('/galang-dana/kategori', [GalangDanaController::class, 'kategori'])
        ->name('galang.kategori');
});
    
// =====================
// DANA PUNIA
// =====================
Route::get('/dana-punia', [DanaPuniaController::class, 'index'])
    ->name('dana-punia.index');

// =====================
// KYC REQUIRED NOTICE
// =====================
Route::middleware('auth')->get('/kyc-required', function () {
    $kyc = \App\Models\KycSubmission::where('user_id', auth()->id())->first();

    return view('kyc.required', compact('kyc'));
})->name('kyc.required');

// =====================
// PEMBAYARAN
// =====================
Route::post('/donasi/{program:slug}/proses', [DonasiController::class, 'proses'])
    ->name('donasi.proses');

Route::get('/pembayaran/{kode}', [PembayaranController::class, 'show'])
    ->name('pembayaran.show');

// =====================
// EMAIL VERIFICATION
// =====================
Route::get('/register/verify/{token}', [AuthController::class, 'verifyPreRegister'])
    ->name('preregister.verify');

Route::post('/register/resend', [AuthController::class, 'resendPreRegister'])
    ->name('preregister.resend');

Route::get('/register/notice', function () {
    if (!session()->has('preregister_email')) {
        return redirect()->route('register');
    }

    if (auth()->check()) {
        return redirect()->route('landing');
    }

    return view('auth.register-notice');
})->name('register.notice');

// =====================
// EDIT PROFILE EMAIL
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/profile/email/notice', function () {
        if (!session()->has('pending_new_email')) {
            return redirect()->route('profile');
        }

        return view('profile.email-notice');
    })->name('profile.email.notice');

    Route::get('/profile/email/verify/{token}', [ProfileController::class, 'verifyNewEmail'])
        ->name('profile.email.verify');

    Route::post('/profile/email/resend', [ProfileController::class, 'resendNewEmail'])
        ->name('profile.email.resend');
});

// Pencairan Dana
Route::middleware(['auth','kyc.verified'])
    ->prefix('dashboard/disbursements')
    ->name('dashboard.disbursements.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'disbursementsIndex'])->name('index');
        Route::post('/{program}/request', [DashboardController::class, 'campaignStoreDisbursement'])->name('request');
        
    });

    Route::post('/dashboard/campaigns/{program}/disbursements/{disbursement}/items', 
    [DashboardController::class, 'disbursementItemStore']
)->name('dashboard.disbursements.items.store');
// =====================
// ADMIN
// =====================

Route::prefix('admin')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->name('admin.login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('admin.login.submit');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::post('/logout', [AdminAuthController::class, 'logout'])
            ->name('admin.logout');
        
        Route::get('/kyc', [AdminKycController::class, 'index'])
            ->name('admin.kyc.index');

        Route::get('/kyc/{kyc}', [AdminKycController::class, 'show'])
            ->name('admin.kyc.show');

        Route::post('kyc/{kyc}/approve', [AdminKycController::class, 'approve'])
            ->name('admin.kyc.approve');

        Route::post('kyc/{kyc}/reject', [AdminKycController::class, 'reject'])
            ->name('admin.kyc.reject');
    });

});

