<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\KycSubmission;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CampaignUpdate;
use App\Models\DisbursementRequest;
use Illuminate\Support\Facades\Storage;

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

        // =========================
        // 1. TOTAL DONASI USER INI
        // =========================
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
        $rejected = $this->campaignsBaseQuery()
            ->where('status', Program::STATUS_REJECTED)
            ->paginate(10);

        return view('dashboard.campaigns.rejected', compact('rejected'));
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

        abort_unless($program->status === Program::STATUS_DRAFT, 403);

        $program->update([
            'status' => Program::STATUS_PENDING,
        ]);

        return back()->with('success', 'Campaign kamu berhasil diajukan dan sedang direview admin.');

            
    }

    public function campaignDestroy(Program $program)
    {
        abort_unless($program->user_id === auth()->id(), 403);
        abort_unless(in_array($program->status, [
            Program::STATUS_DRAFT,
            Program::STATUS_REJECTED,
        ]), 403);

        $program->update(['status' => Program::STATUS_CANCELLED]);

        return redirect()->route('dashboard.campaigns.index')
            ->with('success', 'Campaign berhasil dibatalkan.');
    }

    // DETAIL
    public function campaignShow(Program $program)
    {
        abort_unless($program->user_id === auth()->id(), 403);

        $program->loadSum(['donations as raised_sum' => function ($q) {
            $q->whereIn('status', ['settlement','capture','success','paid']);
        }], 'amount');

        $program->load([
            'updates' => fn($q) => $q->latest(),
            'disbursements' => fn($q) => $q->latest(),
        ]);

        return view('dashboard.campaigns.show', compact('program'));
    }

    // EDIT
    public function campaignEdit(Program $program)
    {
        abort_unless($program->user_id === auth()->id(), 403);
        abort_unless($program->status === Program::STATUS_DRAFT, 403);

        return view('dashboard.campaigns.edit', compact('program'));
    }

    // UPDATE
    public function campaignUpdate(Request $request, Program $program)
    {
        abort_unless($program->user_id === auth()->id(), 403);
        abort_unless(in_array($program->status, [Program::STATUS_DRAFT, Program::STATUS_REJECTED]), 403);

        $data = $request->validate([
            'title' => ['required','string','max:150'],
            'short_description' => ['nullable','string','max:255'],
            'target' => ['nullable','integer','min:0'],
            'deadline' => ['nullable','date'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('image')) {
            // hapus lama (opsional)
            if ($program->image && Storage::disk('public')->exists($program->image)) {
                Storage::disk('public')->delete($program->image);
            }
            $data['image'] = $request->file('image')->store('programs', 'public');
        }

        $program->update($data);

        return redirect()->route('dashboard.campaigns.show', $program->id)
            ->with('success', 'Campaign berhasil diperbarui.');
    }

    // MANAGE (RUNNING)
    public function campaignManage(Program $program)
    {
    abort_unless($program->user_id === auth()->id(), 403);
    abort_unless(in_array($program->status, [Program::STATUS_APPROVED, Program::STATUS_RUNNING]), 403);

    $updates = CampaignUpdate::where('program_id', $program->id)
        ->latest()
        ->get();

    $disbursements = DisbursementRequest::where('program_id', $program->id)
        ->with('items') // 🔥 ini kuncinya
        ->latest()
        ->paginate(10);

    // kalau kamu masih butuh items global, ini opsional:
    $items = collect(); // boleh hapus kalau view udah gak pakai

    return view('dashboard.campaigns.manage', compact('program', 'updates', 'disbursements', 'items'));
}
    // ADD UPDATE (RUNNING)
 public function campaignStoreUpdate(Request $request, Program $program)
{
    abort_unless($program->user_id === auth()->id(), 403);
    abort_unless(in_array($program->status, [
        Program::STATUS_APPROVED,
        Program::STATUS_RUNNING
    ]), 403);

    $data = $request->validate([
        'title' => ['required', 'string', 'max:150'],
        'body'  => ['required', 'string'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')
            ->store('campaign-updates', 'public');
    }

    CampaignUpdate::create([
        'program_id' => $program->id,
        'user_id'    => auth()->id(),
        'title'      => $data['title'],
        'body'       => $data['body'],
        'image'      => $data['image'] ?? null,
    ]);

    return back()->with('success', 'Kabar terbaru berhasil ditambahkan.');
}


    // ADD DISBURSEMENT REQUEST (RUNNING)
    public function campaignStoreDisbursement(Request $request, Program $program)
    {
        abort_unless($program->user_id === auth()->id(), 403);
        abort_unless(in_array($program->status, [Program::STATUS_APPROVED, Program::STATUS_RUNNING]), 403);

        $data = $request->validate([
            'amount'          => ['required','integer','min:1000'],
            'note'            => ['nullable','string'],
            'bank_name'       => ['required','string','max:100'],
            'account_name'    => ['required','string','max:150'],
            'account_number'  => ['required','string','max:50'],
        ]);

        DisbursementRequest::create([
            'program_id'      => $program->id,
            'user_id'         => auth()->id(),
            'amount'          => $data['amount'],
            'note'            => $data['note'] ?? null,
            'bank_name'       => $data['bank_name'],
            'account_name'    => $data['account_name'],
            'account_number'  => $data['account_number'],
            'status'          => 'requested',
        ]);



        return back()->with('success', 'Permintaan pencairan berhasil diajukan (menunggu admin).');
    }

    // HISTORY PAGE
    public function campaignsHistory()
    {
        $campaigns = $this->campaignsBaseQuery()
            ->whereIn('status', [Program::STATUS_COMPLETED, Program::STATUS_EXPIRED, Program::STATUS_CANCELLED])
            ->paginate(10);

        return view('dashboard.campaigns.history', compact('campaigns'));
    }

    public function campaignUpdateDestroy(Program $program, CampaignUpdate $update)
    {
        abort_unless($program->user_id === auth()->id(), 403);
        abort_unless($update->program_id === $program->id, 403);

        $update->delete();

        return back()->with('success', 'Kabar terbaru berhasil dihapus.');
    }

    public function disbursementsIndex()
{
    $userId = auth()->id();

    $kyc = KycSubmission::where('user_id', $userId)->latest('id')->first();

    // semua program running/approved user (biar bisa pilih mau cairin yang mana)
    $programs = $this->campaignsBaseQuery()
        ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
        ->get();

    // riwayat semua pencairan user
    $disbursements = DisbursementRequest::where('user_id', $userId)
        ->latest()
        ->paginate(10);

        // =========================
    // INI DUA YANG KAMU TANYA
    // =========================

    // total campaign user
    $totalCampaigns = Program::where('user_id', $userId)
    ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
    ->count();


    // total dana terkumpul dari semua campaign user
    $totalRaisedAll = Donation::whereHas('program', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->whereIn('status', ['settlement', 'capture', 'success', 'paid'])
        ->sum('amount');

    return view('dashboard.disbursements.index', compact(
        'programs',
        'kyc',
        'disbursements',
        'totalCampaigns',
        'totalRaisedAll'
    ));    
    }

    public function donationsIndex()
{
    $userId = auth()->id();

    $donations = Donation::query()
        ->where('user_id', $userId)
        ->whereIn('status', ['success', 'settlement', 'capture', 'paid'])
        ->with(['program:id,title,slug,image'])
        ->latest()
        ->paginate(10);

    $totalDonasi = Donation::where('user_id', $userId)
        ->whereIn('status', ['success', 'settlement', 'capture', 'paid'])
        ->sum('amount');

    return view('dashboard.donations.index', compact('donations', 'totalDonasi'));
}

public function disbursementItemStore(Request $request, Program $program, DisbursementRequest $disbursement)
{
    abort_unless($program->user_id === auth()->id(), 403);
    abort_unless($disbursement->program_id === $program->id, 403);

    $data = $request->validate([
        'title'  => ['required','string','max:150'],
        'amount' => ['required','integer','min:1000'],
        'note'   => ['nullable','string'],
    ]);

    $disbursement->items()->create($data);

    return back()->with('success', 'Rincian penggunaan dana berhasil ditambahkan.');
}



    

}
