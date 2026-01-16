<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CampaignUpdate;


class ProgramController extends Controller
{
    public function index()
    {
        // pakai data yang sudah didekorasi (ada days_left)
        $programs = $this->allPrograms();

        return view('programs.index', compact('programs'));
    }

    private function dbPrograms(): array
    {
    return \App\Models\Program::query()
        ->whereIn('status', ['approved', 'running'])
        ->get()
        ->map(function ($p) {

            // hitung raised dari DB
            $raised = \App\Models\Donation::where('program_id', $p->id)
                ->whereIn('status', ['success','settlement','capture','paid'])
                ->sum('amount');

            return [
                'id'        => $p->id,
                'slug'      => $p->slug,
                'title'     => $p->title,
                'category'  => $p->category,
                'image'     => $p->image ? asset('storage/'.$p->image) : asset('images/placeholder-campaign.jpg'),
                'banner'    => $p->image ? asset('storage/'.$p->image) : asset('images/placeholder-campaign.jpg'),
                'raised'    => (int) $raised,
                'target'    => (int) ($p->target ?? 0),
                'deadline'  => $p->deadline,
            ];
        })
        ->toArray();
    }


    public function show($idOrSlug)
    {

        $programModel = \App\Models\Program::query()
            ->with('user')
            ->whereIn('status', [\App\Models\Program::STATUS_APPROVED, \App\Models\Program::STATUS_RUNNING])
            ->where(function ($q) use ($idOrSlug) {
                $q->where('slug', $idOrSlug)->orWhere('id', $idOrSlug);
            })
            ->first();

        if ($programModel) {
            // hitung raised dari donations (kalau mau konsisten)
            $raised = \App\Models\Donation::query()
                ->where('program_id', $programModel->id)
                ->whereIn('status', ['success', 'settlement', 'capture'])
                ->sum('amount');

            $disbursements = \App\Models\DisbursementRequest::query()
            ->where('program_id', $programModel->id)
            ->whereIn('status', ['requested','approved','paid']) // biar kelihatan juga yang menunggu
            ->latest()
            ->get();

            $kyc = \App\Models\KycSubmission::where('user_id', $programModel->user_id)
            ->where('status', 'approved')
            ->latest()
            ->first();

            $authorName = $kyc
            ? ($kyc->account_type === 'organisasi'
                ? ($kyc->entity_name ?? $kyc->full_name)
                : $kyc->full_name)
            : null;


        $totalDisbursed = $disbursements
            ->where('status', 'paid')
            ->sum('amount');
 

        $imageUrl  = $programModel->image
            ? asset('storage/' . $programModel->image)
            : asset('images/placeholder-campaign.jpg');

        // kalau kamu belum punya kolom banner di DB, ya pakai image aja untuk banner
        $bannerUrl = $programModel->banner
            ? (str_starts_with($programModel->banner, 'http') ? $programModel->banner : asset('storage/' . $programModel->banner))
            : $imageUrl;        

            $program = [
                'id' => $programModel->id,
                'slug' => $programModel->slug,
                'title' => $programModel->title,
                'category' => $programModel->category,
                'image' => $imageUrl,
                'banner' => $bannerUrl,
                'description' => $programModel->description,
                'short_description' => $programModel->short_description ?? null,
                'target' => (int) ($programModel->target ?? 0),
                'raised' => (int) $raised,
                'deadline' => $programModel->deadline ?? null,
                'author_name' => $authorName
                    ?? $programModel->user?->name
                    ?? 'Donasikuy',

            ];

            $program = $this->decorateProgram($program);

            $updates = CampaignUpdate::query()
            ->where('program_id', $programModel->id)
            ->latest()
            ->get()
            ->map(function ($u) {
                return [
                    'title' => $u->title,
                    'date'  => $u->created_at?->translatedFormat('d F Y'),
                    'body'  => preg_split("/\r\n|\n|\r/", (string) $u->body), // biar cocok sama view kamu yang expect array
                    'images'=> $u->image ? [asset('storage/' . $u->image)] : [],
                ];
            })
            ->toArray();

            // Ambil donasi program ini
            $donations = \App\Models\Donation::query()
                ->where('program_id', $program['id'])
                ->orderByDesc('created_at')
                ->get();


            return view('programs.show', [
                'program' => $program,
                'updates' => $updates,
                'donations' => $donations,
                'disbursements' => $disbursements,
                'totalDisbursed' => $totalDisbursed,
            ]);
        }

        $all = $this->seed();
        $program = null;

        if (isset($all[$idOrSlug])) {
            $program = $all[$idOrSlug];
        } else {
            foreach ($all as $p) {
                if (isset($p['slug']) && $p['slug'] === $idOrSlug) {
                    $program = $p;
                    break;
                }
            }
        }

        abort_unless($program, 404);
        $program = $this->decorateProgram($program);

        // Ambil semua donasi untuk program ini (NO FILTER STATUS dulu)
        $donations = Donation::query()
            ->where('program_id', $program['id'])
            ->orderByDesc('created_at')
            ->get();

        $updates = [
            [
                'title' => 'Update Bantuan Gempa Terkini',
                'date' => '6 November 2025',
                'body' => [
                    'Tim relawan berhasil menyalurkan bantuan sembako kepada 120 keluarga terdampak.',
                    'Penggalangan dana masih dibuka untuk tahap kedua.',
                ],
                'images' => [
                    asset('images/update1-a.jpg'),
                    asset('images/update1-b.jpg'),
                ],
            ],
        ];


        return view('programs.show', [
            'program' => $program,
            'updates' => $updates,
            'donations' => $donations,
        ]);
    }

    public function search(Request $request)
    {
        $q = trim((string) ($request->q ?? ''));

        if ($q === '') {
            return redirect()->route('landing');
        }

        $keyword = mb_strtolower($q);

        $all = $this->allPrograms();

        // Filter + scoring (relevansi)
        $filtered = array_filter($all, function ($p) use ($keyword) {
            $title = mb_strtolower($p['title'] ?? '');
            $category = mb_strtolower($p['category'] ?? '');
            $slug = mb_strtolower($p['slug'] ?? '');
            $short = mb_strtolower($p['short_description'] ?? '');
            $desc = mb_strtolower($p['description'] ?? '');

            return str_contains($title, $keyword)
                || str_contains($category, $keyword)
                || str_contains($slug, $keyword)
                || str_contains($short, $keyword)
                || str_contains($desc, $keyword);
        });

        // Sort by relevance (title > category > slug > description)
        $collection = collect($filtered)->map(function ($p) use ($keyword) {
            $title = mb_strtolower($p['title'] ?? '');
            $category = mb_strtolower($p['category'] ?? '');
            $slug = mb_strtolower($p['slug'] ?? '');
            $short = mb_strtolower($p['short_description'] ?? '');
            $desc = mb_strtolower($p['description'] ?? '');

            $score = 0;
            if (str_contains($title, $keyword)) $score += 100;
            if (str_contains($category, $keyword)) $score += 60;
            if (str_contains($slug, $keyword)) $score += 40;
            if (str_contains($short, $keyword)) $score += 25;
            if (str_contains($desc, $keyword)) $score += 10;

            // bonus kalau keyword ada di awal judul
            if ($keyword !== '' && str_starts_with($title, $keyword)) $score += 25;

            $p['_score'] = $score;
            return $p;
        })->sortByDesc('_score')->values();

        $perPage = 9;
        $page = (int) $request->input('page', 1);

        $items = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $programs = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('programs.search_result', [
            'programs' => $programs,
            'keyword' => $q,
        ]);
    }


    public function allPrograms(): array
    {
        // 1. seed (dummy)
        $seedPrograms = $this->seed();

        // 2. program dari database (approved & running)
        $dbPrograms = $this->dbPrograms();

        // 3. gabungkan
        $merged = array_merge($seedPrograms, $dbPrograms);

        // 4. decorate (days_left, status)
        return array_values(array_map(
            fn ($p) => $this->decorateProgram($p),
            $merged
        ));
    }


    public function findProgram(string $idOrSlug): ?array
    {
        $all = $this->seed();

        // coba sebagai id (key array)
        if (isset($all[$idOrSlug])) {
            return $this->decorateProgram($all[$idOrSlug]);
        }

        // kalau tidak ketemu, coba cari slug
        foreach ($all as $p) {
            if (isset($p['slug']) && $p['slug'] === $idOrSlug) {
                return $this->decorateProgram($p);
            }
        }

        return null;
    }

    private function decorateProgram(array $p): array
    {
        $programId = $p['id'] ?? null;
        $raisedDb = 0;

        if ($programId) {
            $raisedDb = (int) Donation::query()
                ->where('program_id', $programId)
                ->whereIn('status', ['success', 'settlement', 'paid'])
                ->sum('amount');
        }

            // 👉 TAMBAHKAN INI
            $seedOverrides = session('donasi_overrides', []);
            $seedRaised = !empty($p['slug']) && isset($seedOverrides[$p['slug']])
                ? (int) $seedOverrides[$p['slug']]
                : 0;

            // 🔥 INI YANG DIPAKAI VIEW
            $p['raised'] = $raisedDb + $seedRaised;

        // DEFAULT WAJIB
        $p['days_left'] = $p['days_left'] ?? 0;
        $p['status'] = $p['status'] ?? 'Tanpa Batas Waktu';

        if (! empty($p['deadline'] ?? null)) {
            $today = \Carbon\Carbon::now()->startOfDay();
            $endDate = \Carbon\Carbon::parse($p['deadline'])->startOfDay();

            $diff = $today->diffInDays($endDate, false);

            if ($diff > 0) {
                $p['days_left'] = $diff;
                $p['status'] = 'Sedang Berjalan';
            } elseif ($diff === 0) {
                $p['days_left'] = 0;
                $p['status'] = 'Berakhir Hari Ini';
            } else {
                $p['days_left'] = 0;
                $p['status'] = 'Selesai';
            }
        }

        return $p;
    }

    public function seed(): array
    {
        // DATA DASAR (SEPERTI SEBELUMNYA)
        $programs = [
            1 => [
                'id' => 1,
                'slug' => 'sedekah-beras',
                'category' => 'Sedekah',
                'title' => 'Sedekah Beras',
                'image' => asset('images/bencana.jpg'),
                'banner' => asset('images/bencana.jpg'),
                'raised' => 0,
                'target' => 50_000_000,
                'deadline' => Carbon::now()->addDays(64)->toDateString(),
                'is_seeder' => true,
            ],
            2 => [
                'id' => 2,
                'slug' => 'bantu-bencana-gempa-dengan-kebutuhan-pokok',
                'category' => 'Kemanusiaan',
                'title' => 'Bantu Bencana Gempa dengan Kebutuhan Pokok',
                'image' => asset('images/bencana1.jpg'),
                'banner' => asset('images/bencana1.jpg'),
                'raised' => 500_000_124,
                'target' => 700_000_000,
                'deadline' => Carbon::now()->addDays(2)->toDateString(),
                'is_seeder' => true,
            ],
            3 => [
                'id' => 3,
                'slug' => 'bantuan-anak-yatim-dan-dhuafa',
                'category' => 'Pendidikan',
                'title' => 'Penyaluran Bantuan untuk Anak Yatim dan Dhuafa',
                'image' => asset('images/yatim1.jpg'),
                'banner' => asset('images/yatim1.jpg'),
                'raised' => 235_366_942,
                'target' => 300_000_000,
                'deadline' => Carbon::now()->addDays(25)->toDateString(),
                'is_seeder' => true,
            ],
            4 => [
                'id' => 4,
                'slug' => 'bantuan-a',
                'category' => 'Pendidikan',
                'title' => 'Penyaluran Bantuan untuk Anak Yatim dan Dhuafa',
                'image' => asset('images/yatim1.jpg'),
                'banner' => asset('images/yatim1.jpg'),
                'raised' => 235_366_942,
                'target' => 300_000_000,
                'deadline' => Carbon::now()->addDays(25)->toDateString(),
                'is_seeder' => true,
            ],
            5 => [
                'id' => 5,
                'slug' => 'gempa-sumedang',
                'category' => 'Bencana Alam',
                'title' => 'Gempa Bumi di Sumedang – Rumah Warga Rusak Berat',
                'image' => asset('images/gempa1.jpeg'),
                'banner' => asset('images/gempa1.jpeg'),
                'raised' => 32_000_000,
                'target' => 250_000_000,
                'deadline' => Carbon::now()->addDays(18)->toDateString(),
                'is_seeder' => true,
            ],
            6 => [
                'id' => 6,
                'slug' => 'kekeringan-ntt',
                'category' => 'Kemanusiaan',
                'title' => 'Bantu Air Bersih untuk Warga Terdampak Kekeringan di NTT',
                'image' => asset('images/airbersih.jpeg'),
                'banner' => asset('images/airbersih.jpeg'),
                'raised' => 15_900_000,
                'target' => 120_000_000,
                'deadline' => Carbon::now()->addDays(40)->toDateString(),
                'is_seeder' => true,
            ],
        ];

        // AMBIL DELTA RAISED DARI SESSION
        $overrides = session('donasi_overrides', []);

        // TAMBAHKAN DELTA KE RAISED BERDASARKAN SLUG
        foreach ($programs as &$p) {
            if (! empty($p['slug']) && isset($overrides[$p['slug']])) {
                $p['raised'] = ($p['raised'] ?? 0) + $overrides[$p['slug']];
            }
        }
        unset($p); // safety

        return $programs;
    }

    public function isSeedSlug(string $slug): bool
    {
    $seed = $this->seed();
    foreach ($seed as $p) {
        if (($p['slug'] ?? null) === $slug) return true;
    }
    return false;
    }

}
