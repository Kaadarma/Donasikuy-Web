<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    
    public function index()
    {
        // pakai data yang sudah didekorasi (ada days_left)
        $programs = $this->allPrograms();

        return view('programs.index', compact('programs'));
    }

    

    public function show($idOrSlug)
    {
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

        $keyword = strtolower($q);

        $allRaw = $this->seed();
        $all = array_map(fn ($p) => $this->decorateProgram($p), $allRaw);

        $filtered = array_filter($all, function ($p) use ($keyword) {
            $title = strtolower($p['title'] ?? '');
            $category = strtolower($p['category'] ?? '');
            $slug = strtolower($p['slug'] ?? '');

            return str_contains($title, $keyword)
                || str_contains($category, $keyword)
                || str_contains($slug, $keyword);
        });

        $collection = collect($filtered);

        $perPage = 9;
        $page = (int) $request->input('page', 1);

        $items = $collection
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

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
        $raw = $this->seed();

        // ambil info donasi terakhir dari session
        $lastSlug = session('last_donasi_slug');
        $lastNominal = session('last_donasi_nominal', 0);

        // dekorasi semua program + timpa raised untuk program yang barusan didonasi
        $decorated = array_map(function ($p) use ($lastSlug, $lastNominal) {
            // kalau slug di session cocok dengan slug program ini → naikkan raised-nya
            if ($lastSlug && isset($p['slug']) && $p['slug'] === $lastSlug) {
                $p['raised'] = ($p['raised'] ?? 0) + $lastNominal;
            }

            return $this->decorateProgram($p);
        }, $raw);

        // array_values supaya index 0..N
        return array_values($decorated);
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

    $p['raised'] = $raisedDb;
    $p['days_left'] = null;
    $p['status'] = 'Tanpa Batas Waktu';

    if (! empty($p['deadline'])) {
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
}
