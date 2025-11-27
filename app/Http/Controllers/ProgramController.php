<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = array_values($this->seed());

        return view('programs.index', compact('programs'));
    }

    public function show($idOrSlug)
    {
        $all = $this->seed();

        // Coba ambil langsung dengan key numerik (id sebagai key array)
        $program = $all[$idOrSlug] ?? null;

        // Kalau belum ketemu, coba cari dengan slug (aman pakai isset)
        if (! $program) {
            foreach ($all as $p) {
                if (isset($p['slug']) && $p['slug'] === $idOrSlug) {
                    $program = $p;
                    break;
                }
            }
        }

        // Kalau tetap tidak ketemu, 404
        abort_unless($program, 404);

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
            [
                'title' => 'Program Pendidikan Yatim Diperluas',
                'date' => '1 November 2025',
                'body' => [
                    'Program bantuan beasiswa kini menjangkau 3 sekolah di Denpasar.',
                ],
                'images' => [],
            ],
        ];

        return view('programs.show', compact('program', 'updates'));
    }

    public function search(Request $request)
    {
        $keyword = strtolower($request->q ?? '');

        $all = $this->seed();

        // Amankan akses slug/category/title pakai null coalesce
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
        $page = request()->input('page', 1);

        // values() supaya index-nya rapi 0..N
        $items = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $programs = new LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('programs.search_result', [
            'programs' => $programs,
            'keyword' => $request->q,
        ]);
    }

    private function seed(): array
    {
        return [
            1 => [
                'id' => 1,
                'slug' => 'sedekah-beras',
                'category' => 'Sedekah',
                'title' => 'Sedekah Beras',
                'image' => asset('images/bencana.jpg'),
                'banner' => asset('images/bencana.jpg'),
                'raised' => 0,
                'target' => 50_000_000,
                'days_left' => 64,
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
                'days_left' => 2,
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
                'days_left' => 25,
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
                'days_left' => 25,
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
                'days_left' => 18,
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
                'days_left' => 40,
            ],
        ];
    }
}
