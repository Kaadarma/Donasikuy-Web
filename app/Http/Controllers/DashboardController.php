<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donasi; // pastikan file Donasi.php ada di app/Models

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
        $totalDonasi = Donasi::where('id_user', $user->id)
            ->where('status_donasi', 'success')
            ->sum('jumlah_donasi');

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

                $amount = Donasi::where('id_user', $user->id)
                    ->where('status_donasi', 'success')
                    ->whereDate('created_at', $date)
                    ->sum('jumlah_donasi');

                return [
                    'date'   => $date->toDateString(), // contoh: 2025-12-03
                    'amount' => $amount,
                ];
            })
            ->sortByDesc('date')
            ->values()
            ->all();

        // =========================
        // 4. KIRIM KE VIEW
        // =========================
        return view('dashboard.index', [
            'totalDonasi'     => $totalDonasi,
            'totalCampaign'   => $totalCampaign,
            'totalPencairan'  => $totalPencairan,
            'isKycVerified'   => $isKycVerified,
            'weeklyDonations' => $weeklyDonations,
        ]);
    }
}
