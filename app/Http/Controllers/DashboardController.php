<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\Withdrawal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Wajib login dulu buat akses dashboard.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan halaman dashboard user DonasiKuy.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // =========================
        // 1. KARTU RINGKASAN ATAS
        // =========================

        // Total donasi yang dilakukan user ini
        // (sesuaikan nama kolom/relasi kalau beda, misal donor_id)
        $totalDonasi = Donation::where('user_id', $user->id)
            ->where('status', 'success')
            ->sum('amount');

        // Total campaign yang dimiliki user ini (sebagai pemilik galang dana)
        $totalCampaign = Campaign::where('user_id', $user->id)->count();

        // Total pencairan dana yang sudah berhasil
        $totalPencairan = Withdrawal::where('user_id', $user->id)
            ->where('status', 'success')
            ->sum('amount');

        // Status KYC user (boolean), sesuaikan dengan kolom di tabel users
        $isKycVerified = (bool) ($user->kyc_verified ?? false);

        // =========================
        // 2. TABEL 7 HARI TERAKHIR
        // =========================
        //
        // Kita generate 7 baris: dari hari ini mundur 6 hari ke belakang.
        // Lalu untuk tiap hari, ambil total donasi user di tanggal itu.

        $weeklyDonations = collect(range(0, 6))
            ->map(function ($i) use ($user) {
                $date = now()->subDays($i)->startOfDay();

                $amount = Donation::where('user_id', $user->id)
                    ->where('status', 'success')
                    ->whereDate('created_at', $date)
                    ->sum('amount');

                return [
                    'date'   => $date->toDateString(), // 2025-12-03
                    'amount' => $amount,
                ];
            })
            // biar yang paling baru di atas (03-Des, 02-Des, dst)
            ->sortByDesc('date')
            ->values()
            ->all();

        // =========================
        // 3. KIRIM KE VIEW
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
