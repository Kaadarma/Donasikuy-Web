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
use App\Models\Event;
use App\Models\SeedDonation;
use Illuminate\Pagination\LengthAwarePaginator;


class DashboardController extends Controller
{

    public function eventsIndex()
    {
        $events = Event::where('user_id', auth()->id())
            ->latest()
            ->paginate(9);

        return view('dashboard.events.index', compact('events'));
    }

    public function eventsShow(Event $event)
    {
        abort_unless($event->user_id === auth()->id(), 403);

        return view('dashboard.events.show', compact('event'));
    }
    
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

        $seedTotal = collect(session('seed_donations', []))
            ->filter(fn($x) => ($x['user_id'] ?? null) == $user->id)
            ->whereIn('status', ['success','settlement','capture','paid'])
            ->sum('amount');

        $totalDonasi = (int) $totalDonasi + (int) $seedTotal;


        $totalCampaign = Program::where('user_id', $user->id)
            ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
            ->count();

        $totalPencairan = DisbursementRequest::where('user_id', $user->id)
            ->where('status', DisbursementRequest::STATUS_PAID) // atau 'paid'
            ->sum('amount');

        // 3) Donasi 7 hari terakhir
        $valid = ['success','settlement','capture','paid'];

        $seedRows = collect(session('seed_donations', []))
            ->filter(fn ($x) => ($x['user_id'] ?? null) == $user->id)
            ->filter(fn ($x) => in_array(($x['status'] ?? 'success'), $valid))
            ->map(function ($x) {
                return [
                    'date'   => \Carbon\Carbon::parse($x['created_at'] ?? now())->toDateString(),
                    'amount' => (int) ($x['amount'] ?? 0),
                ];
            });

        $weeklyDonations = collect(range(0, 6))
            ->map(function ($i) use ($user, $seedRows, $valid) {
                $date = now()->subDays($i)->toDateString();

                // DB
                $dbAmount = (int) Donation::where('user_id', $user->id)
                    ->whereIn('status', $valid)
                    ->whereDate('created_at', $date)
                    ->sum('amount');

                // SEED (session)
                $seedAmount = (int) $seedRows
                    ->where('date', $date)
                    ->sum('amount');

                return [
                    'date'   => $date,
                    'amount' => $dbAmount + $seedAmount,
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
        $valid = ['success','settlement','capture','paid'];

        return Program::query()
            ->where('user_id', auth()->id())
            ->withSum(['donations as raised_sum' => function ($q) use ($valid) {
                $q->whereIn('status', $valid);
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

    // RUNNING: approved/running yang masih aktif (deadline null ATAU belum lewat)
    $running = (clone $base)
        ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
        ->where(function ($q) {
            $q->whereNull('deadline')
              ->orWhereDate('deadline', '>=', now()->toDateString());
        })
        ->paginate(6, ['*'], 'running');

    // COMPLETED (expired): approved/running yang deadline sudah lewat
    $completed = (clone $base)
        ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
        ->whereNotNull('deadline')
        ->whereDate('deadline', '<', now()->toDateString())
        ->paginate(6, ['*'], 'completed');

        $review = (clone $base)
            ->where('status', Program::STATUS_PENDING)
            ->paginate(6, ['*'], 'review');

        $kyc = KycSubmission::where('user_id', $userId)
            ->latest('id')
            ->first();

        return view('dashboard.campaigns.index', compact(
            'drafts', 'rejected', 'running', 'completed', 'review', 'kyc'
        ));
    }

    public function campaignsCompleted()
    {
        $campaigns = $this->campaignsBaseQuery()
            ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', now()->toDateString())
            ->paginate(10);

        return view('dashboard.campaigns.completed', compact('campaigns'));
    }


    // =========================
    // LIST: RUNNING
    // /dashboard/campaigns/running
    // =========================
    public function campaignsRunning()
    {
        $campaigns = $this->campaignsBaseQuery()
            ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
            ->where(function ($q) {
                $q->whereNull('deadline')
                ->orWhereDate('deadline', '>=', now()->toDateString());
            })
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

        return redirect()
            ->route('dashboard.campaigns.index')
            ->with('success', 'Campaign kamu berhasil diajukan dan sedang direview admin.');


            
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

        return redirect()->route('dashboard.campaigns.index')
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

        // ✅ KYC harus approved
        $kyc = KycSubmission::where('user_id', auth()->id())->first();
        abort_unless($kyc && $kyc->status === 'approved', 403);

        // ✅ rekening wajib ada
        abort_unless($kyc->bank_name && $kyc->account_name && $kyc->account_number, 403);

        $data = $request->validate([
            'amount' => ['required','integer','min:1000'],
            'note'   => ['required','string','min:5','max:1000'],
        ]);

        $request->validate([
            'amount' => ['required','integer','min:1000'],
            'note'   => ['required','string','min:5','max:1000'],
        ], [
            'note.required' => 'Catatan wajib diisi.',
            'note.min'      => 'Catatan minimal 5 karakter.',
        ]);



        // ✅ (minimal) cek saldo available (biar proper)
        $totalRaised = Donation::where('program_id', $program->id)
            ->whereIn('status', ['settlement','capture','success','paid'])
            ->sum('amount');

        $totalRequestedOrPaid = DisbursementRequest::where('program_id', $program->id)
            ->whereIn('status', ['requested','approved','paid']) // kamu bisa sesuaikan: mau lock juga requested atau enggak
            ->sum('amount');

        $available = (int) $totalRaised - (int) $totalRequestedOrPaid;
        if ($data['amount'] > $available) {
            return back()->withErrors([
                'amount' => 'Nominal melebihi dana yang tersedia untuk dicairkan.'
            ])->withInput();
        }

        DisbursementRequest::create([
            'program_id'      => $program->id,
            'user_id'         => auth()->id(),
            'amount'          => $data['amount'],
            'note'            => $data['note'] ?? null,
            'status'          => 'requested',

            // ✅ snapshot rekening dari KYC
            'bank_name'       => $kyc->bank_name,
            'account_name'    => $kyc->account_name,
            'account_number'  => $kyc->account_number,
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

    public function campaignHistoryShow(Program $program)
    {
        abort_unless($program->user_id === auth()->id(), 403);

        // hanya boleh lihat rincian untuk yang "selesai" versi kamu (approved/running tapi deadline lewat)
        // (kalau kamu mau longgarin untuk semua status juga boleh)
        // abort_unless(...);

        $program->load([
            'updates' => fn($q) => $q->latest(),
            'disbursements' => fn($q) => $q->latest(),
        ]);

        // riwayat pencairan (kalau kamu punya items per request, bisa ->with('items'))
        $disbursements = DisbursementRequest::where('program_id', $program->id)
            ->latest()
            ->paginate(10, ['*'], 'disbursements');

        // ✅ daftar donatur + nominal + tanggal
        $donations = Donation::query()
            ->where('program_id', $program->id)
            ->whereIn('status', ['settlement','capture','success','paid']) // sesuaikan status yang valid di sistemmu
            ->with(['user:id,name']) // pastikan Donation punya relasi user()
            ->latest()
            ->paginate(10, ['*'], 'donations');

        // summary (opsional)
        $totalRaised = Donation::where('program_id', $program->id)
            ->whereIn('status', ['settlement','capture','success','paid'])
            ->sum('amount');

        return view('dashboard.campaigns.history_show', compact(
            'program',
            'disbursements',
            'donations',
            'totalRaised'
        ));
    }

    public function campaignHistoryExtendDeadline(Request $request, Program $program)
    {
    abort_unless($program->user_id === auth()->id(), 403);

    // kamu bilang: dari selesai -> pending
    // optional: batasi hanya kalau deadline sudah lewat
    // abort_unless($program->deadline && now()->startOfDay()->gt(\Carbon\Carbon::parse($program->deadline)->startOfDay()), 403);

    $data = $request->validate([
        'deadline' => ['required', 'date', 'after:today'],
    ]);

    $program->update([
        'deadline' => $data['deadline'],
        'status'   => Program::STATUS_PENDING, // menunggu review
    ]);

    return redirect()
        ->route('dashboard.campaigns.index')
        ->with('success', 'Deadline berhasil diperpanjang. Campaign masuk ke Menunggu Review.');
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

        $base = Program::query()
            ->where('user_id', $userId)
            ->withSum(['donations as raised_sum' => function ($q) {
                $q->whereIn('status', ['settlement','capture','success','paid']);
            }], 'amount');

        $runningPrograms = (clone $base)
            ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
            ->where(function ($q) {
                $q->whereNull('deadline')
                ->orWhereDate('deadline', '>=', now()->toDateString());
            })
            ->get();

        $completedPrograms = (clone $base)
            ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', now()->toDateString())
            ->get();

        $programs = Program::where('user_id', $userId)
        ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
        ->get();    

        $kyc = KycSubmission::where('user_id', $userId)->latest('id')->first();

        // riwayat pencairan
        $disbursements = DisbursementRequest::where('user_id', $userId)
            ->latest()
            ->paginate(10);


        // total dana terkumpul dari SEMUA campaign user
        $programIdsForThisPage = $runningPrograms->pluck('id')
            ->merge($completedPrograms->pluck('id'))
            ->unique()
            ->values();

        $totalCampaigns = $programIdsForThisPage->count();

        $totalRaisedAll = Donation::whereIn('program_id', $programIdsForThisPage)
            ->whereIn('status', ['settlement','capture','success','paid'])
            ->sum('amount');


        return view('dashboard.disbursements.index', compact(
            'programs',
            'kyc',
            'runningPrograms',
            'completedPrograms',
            'disbursements',
            'totalCampaigns',
            'totalRaisedAll'
        ));
    }

    public function disbursementsCreate(Program $program)
    {
    // owner check
    abort_unless($program->user_id === auth()->id(), 403);

    // hanya untuk program approved/running (opsional: boleh juga completed by deadline)
    abort_unless(in_array($program->status, [Program::STATUS_APPROVED, Program::STATUS_RUNNING]), 403);

    $userId = auth()->id();

    // KYC user
    $kyc = KycSubmission::where('user_id', $userId)->latest('id')->first();

    // total dana masuk (valid)
    // total dana masuk (valid)
    $validDonation = ['settlement','capture','success','paid'];
    $totalRaised = (int) Donation::where('program_id', $program->id)
        ->whereIn('status', $validDonation)
        ->sum('amount');

    // ✅ sudah benar-benar cair
    $totalDisbursed = (int) DisbursementRequest::where('program_id', $program->id)
        ->whereIn('status', ['paid'])
        ->sum('amount');

    // ✅ menunggu / diproses (belum cair)
    $totalPendingDisbursement = (int) DisbursementRequest::where('program_id', $program->id)
        ->whereIn('status', ['requested','approved'])
        ->sum('amount');

    // ✅ dana terkunci = pending + paid
    $totalLocked = $totalPendingDisbursement + $totalDisbursed;

    $available = max(0, $totalRaised - $totalLocked);



    // riwayat disbursement untuk program ini + items
    $disbursements = DisbursementRequest::where('program_id', $program->id)
        ->with(['items'])
        ->latest()
        ->paginate(10);

    return view('dashboard.disbursements.create', compact(
        'program',
        'kyc',
        'totalRaised',
        'totalDisbursed',
        'totalPendingDisbursement',      
        'available',
        'disbursements'
    ));
    }


    public function donationsIndex()
    {
        $userId = auth()->id();
        $valid  = ['success', 'settlement', 'capture', 'paid'];

        // ===== 1) DONASI DB =====
        $dbDonations = Donation::query()
            ->where('user_id', $userId)
            ->whereIn('status', $valid)
            ->with(['program:id,title,slug,image'])
            ->latest()
            ->get()
            ->map(function ($d) {
                // biar mirip dengan seed object
                $d->source = 'db';
                return $d;
            });

        // ===== 2) DONASI SEED (SESSION) =====
        $seed = collect(session('seed_donations', []))
            ->filter(fn($x) => ($x['user_id'] ?? null) == $userId)
            ->map(function ($x) {
                // bikin object mirip Donation
                $o = (object) [
                    'source'     => 'seed',
                    'status'     => $x['status'] ?? 'success',
                    'amount'     => (int) ($x['amount'] ?? 0),
                    'created_at' => \Carbon\Carbon::parse($x['created_at'] ?? now()),
                    'order_id'   => $x['order_id'] ?? null,
                    'program'    => (object) [
                        'title' => $this->seedProgramTitle($x['program_slug'] ?? null),
                        'slug'  => $x['program_slug'] ?? null,
                        'image' => $this->seedProgramImage($x['program_slug'] ?? null),
                    ],
                ];
                return $o;
            });

        // ===== 3) GABUNG + SORT =====
        $all = $dbDonations
            ->concat($seed)
            ->sortByDesc(fn($d) => $d->created_at)
            ->values();

        // ===== 4) PAGINATE MANUAL =====
        $perPage = 10;
        $page = request()->integer('page', 1);

        $paged = new LengthAwarePaginator(
            $all->slice(($page - 1) * $perPage, $perPage)->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // ===== 5) TOTAL DONASI (DB + SEED) =====
        $totalDonasiDb = Donation::where('user_id', $userId)
            ->whereIn('status', $valid)
            ->sum('amount');

        $totalDonasiSeed = collect(session('seed_donations', []))
            ->filter(fn($x) => ($x['user_id'] ?? null) == $userId)
            ->whereIn('status', $valid)
            ->sum('amount');

        $totalDonasi = (int) $totalDonasiDb + (int) $totalDonasiSeed;

        return view('dashboard.donations.index', [
            'donations' => $paged,
            'totalDonasi' => $totalDonasi,
        ]);
    }

    private function seedProgramData(?string $slug): ?array
{
    if (!$slug) return null;

    $pc = app(\App\Http\Controllers\ProgramController::class);
    $p = $pc->findProgram($slug); // dari seed ProgramController

    return $p ?: null;
}

private function seedProgramTitle(?string $slug): string
{
    return $this->seedProgramData($slug)['title'] ?? 'Campaign (Seed)';
}

private function seedProgramImage(?string $slug): string
{
    $p = $this->seedProgramData($slug);
    $img = $p['image'] ?? null;

    // image seed biasanya sudah asset('images/xxx')
    return $img ?: asset('images/placeholder-campaign.jpg');
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
