<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donasi; // pastikan file Donasi.php ada di app/Models
use App\Models\KycSubmission;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // =========================
        // 1. TOTAL DONASI USER INI
        // =========================
        // pakai kolom: id_user, status_donasi, jumlah_donasi
        $totalDonasi = Donation::where('user_id', $user->id)
            ->where('status', 'success')
            ->sum('amount');

        // =========================
        // 2. KARTU LAIN (sementara 0 dulu)
        // =========================
        // nanti bisa diisi dari tabel kampanye & pencairan
        $totalCampaign  = 0;
        $totalPencairan = 0;

        // Status KYC (kalau di users ada kolom kyc_verified)
        $isKycVerified = (bool) ($user->kyc_verified ?? false);

        // =========================
        // 3. DONASI 7 HARI TERAKHIR
        // =========================
        $weeklyDonations = collect(range(0, 6))
            ->map(function ($i) use ($user) {
                $date = now()->subDays($i)->startOfDay();

                $amount = Donation::where('user_id', $user->id)
                    ->where('status', 'success')
                    ->whereDate('created_at', $date)
                    ->sum('amount');

                return [
                    'date'   => $date->toDateString(), // contoh: 2025-12-03
                    'amount' => $amount,
                ];
            })
            ->sortByDesc('date')
            ->values()
            ->all();

         // =========================
        // 3. STATUS KYC DARI TABEL kyc_submissions
        // =========================
        // cari pengajuan KYC milik user ini (jika ada)
        $kyc = KycSubmission::where('user_id', $user->id)->first();

        // apakah user sudah pernah submit KYC?
        $hasSubmittedKyc = (bool) $kyc;

        // status KYC: null | pending | approved | rejected
        $kycStatus = $kyc->status ?? null;

        // buat convenience flag: apakah sudah diverifikasi?
        $isKycVerified = $kycStatus === 'approved';

        // kalau kamu mau pakai catatan penolakan admin
        $kycNote = $kyc->note ?? null;

        // =========================
        // 4. KIRIM KE VIEW
        // =========================
        return view('dashboard.index', [
            'totalDonasi'     => $totalDonasi,
            'totalCampaign'   => $totalCampaign,
            'totalPencairan'  => $totalPencairan,
            'weeklyDonations' => $weeklyDonations,

            // KYC
            'isKycVerified'   => $isKycVerified,
            'hasSubmittedKyc' => $hasSubmittedKyc,
            'kycStatus'       => $kycStatus,
            'kycNote'         => $kycNote,
        ]);
    }
}
