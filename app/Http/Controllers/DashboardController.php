<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\KycSubmission;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // =========================
    // DASHBOARD UTAMA
    // =========================
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1) Total donasi user ini
        $totalDonasi = Donation::where('user_id', $user->id)
            ->where('status', 'success')
            ->sum('amount');

        // 2) Kartu lain (sementara)
        $totalCampaign  = 0;
        $totalPencairan = 0;

        // 3) Donasi 7 hari terakhir
        $weeklyDonations = collect(range(0, 6))
            ->map(function ($i) use ($user) {
                $date = now()->subDays($i)->startOfDay();

                $amount = Donation::where('user_id', $user->id)
                    ->where('status', 'success')
                    ->whereDate('created_at', $date)
                    ->sum('amount');

                return [
                    'date'   => $date->toDateString(),
                    'amount' => $amount,
                ];
            })
            ->sortByDesc('date')
            ->values()
            ->all();

        // 4) Status KYC dari tabel kyc_submissions
        $kyc = KycSubmission::where('user_id', $user->id)->first();

        $hasSubmittedKyc = (bool) $kyc;
        $kycStatus       = $kyc->status ?? null; // null|pending|approved|rejected
        $isKycVerified   = $kycStatus === 'approved';
        $kycNote         = $kyc->note ?? null;

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

    // =========================
    // BASE QUERY CAMPAIGNS
    // =========================
    private function campaignsBaseQuery()
    {
        return Program::query()
            ->where('user_id', auth()->id())
            ->withSum(['donations as raised_sum' => function ($q) {
                $q->whereIn('status', ['settlement', 'capture']);
            }], 'amount')
            ->latest();
    }

    // =========================
    // DASHBOARD -> CAMPAIGNS (RINGKASAN)
    // /dashboard/campaigns
    // =========================
    public function campaignsIndex()
    {
        $userId = auth()->id();
        $base = $this->campaignsBaseQuery();

        $drafts = (clone $base)
            ->where('status', Program::STATUS_DRAFT)
            ->paginate(6, ['*'], 'drafts');

        $rejected = (clone $base)
            ->where('status', Program::STATUS_REJECTED)
            ->paginate(6, ['*'], 'rejected');

        $running = (clone $base)
            ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
            ->paginate(6, ['*'], 'running');

        $review = (clone $base)
            ->where('status', Program::STATUS_PENDING)
            ->paginate(6, ['*'], 'review');

        $kyc = KycSubmission::where('user_id', $userId)
            ->latest('id')
            ->first();

        return view('dashboard.campaigns.index', compact(
            'drafts', 'rejected', 'running', 'review', 'kyc'
        ));
    }

    // =========================
    // LIST: RUNNING
    // /dashboard/campaigns/running
    // =========================
    public function campaignsRunning()
    {
        $campaigns = $this->campaignsBaseQuery()
            ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
            ->paginate(10);

        return view('dashboard.campaigns.running', compact('campaigns'));
    }

    // =========================
    // LIST: REVIEW (PENDING)
    // /dashboard/campaigns/review
    // =========================
    public function campaignsReview()
    {
        $campaigns = $this->campaignsBaseQuery()
            ->where('status', Program::STATUS_PENDING)
            ->paginate(10);

        return view('dashboard.campaigns.review', compact('campaigns'));
    }

    // =========================
    // LIST: DRAFTS (DRAFT ONLY)
    // /dashboard/campaigns/drafts
    // =========================
    public function campaignsDrafts()
    {
        $campaigns = $this->campaignsBaseQuery()
            ->where('status', Program::STATUS_DRAFT)
            ->paginate(10);

        return view('dashboard.campaigns.drafts', compact('campaigns'));
    }

    // =========================
    // LIST: REJECTED
    // /dashboard/campaigns/rejected
    // =========================
    public function campaignsRejected()
    {
        $campaigns = $this->campaignsBaseQuery()
            ->where('status', Program::STATUS_REJECTED)
            ->paginate(10);

        return view('dashboard.campaigns.rejected', compact('campaigns'));
    }

    // =========================
    // SAVED PAGE
    // /dashboard/campaigns/{program}/saved
    // =========================
    public function campaignSaved(Program $program)
    {
        abort_unless($program->user_id === auth()->id(), 403);

        return view('dashboard.campaigns.saved', compact('program'));
    }

    // =========================
    // SUBMIT -> PENDING
    // /dashboard/campaigns/{program}/submit
    // =========================
    public function campaignSubmit(Program $program)
    {
        abort_unless($program->user_id === auth()->id(), 403);

        abort_unless(in_array($program->status, [
            Program::STATUS_DRAFT,
            Program::STATUS_REJECTED,
        ]), 403);

        $program->update([
            'status' => Program::STATUS_PENDING,
        ]);

        return redirect()->route('dashboard.campaigns.index')
            ->with('success', 'Campaign berhasil dikirim ke admin untuk direview.');
    }
}
