<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;   // <-- WAJIB
use App\Models\Program;        // <-- WAJIB
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Laravel\Socialite\Facades\Socialite;


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

        $program = $all[$idOrSlug] ?? null;

        if (!$program) {
            foreach ($all as $p) {
                if ($p['slug'] === $idOrSlug) {
                    $program = $p;
                    break;
                }
            }
        }

        abort_unless($program, 404);

        $updates = [
            [
                'title' => 'Update Bantuan Gempa Terkini',
                'date'  => '6 November 2025',
                'body'  => [
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
                'date'  => '1 November 2025',
                'body'  => [
                    'Program bantuan beasiswa kini menjangkau 3 sekolah di Denpasar.',
                ],
                'images' => [],
            ],
        ];

        return view('programs.show', compact('program', 'updates'));
    }

    public function search(Request $request)
    {
        $keyword = strtolower($request->q);

        $all = $this->seed();

        $filtered = array_filter($all, function ($p) use ($keyword) {
            return str_contains(strtolower($p['title']), $keyword)
                || str_contains(strtolower($p['category']), $keyword)
                || str_contains(strtolower($p['slug']), $keyword);
        });

        $collection = collect($filtered);

        $perPage = 9;
        $page = request()->input('page', 1);

        // penting → values() biar foreach tidak error key offset
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
            'keyword'  => $request->q
        ]);
    }



    private function seed(): array
    {
        return [
            1 => [
                'id'        => 1,
                'slug'      => 'sedekah-beras',
                'category'  => 'Sedekah',
                'title'     => 'Sedekah Beras',
                'image'     => asset('images/bencana.jpg'),
                'banner'    => asset('images/bencana.jpg'),
                'raised'    => 0,
                'target'    => 50_000_000,
                'days_left' => 64,
            ],
            2 => [
                'id'        => 2,
                'slug'      => 'bantu-bencana-gempa-dengan-kebutuhan-pokok',
                'category'  => 'Kemanusiaan',
                'title'     => 'Bantu Bencana Gempa dengan Kebutuhan Pokok',
                'image'     => asset('images/p2.jpg'),
                'banner'    => asset('images/p2.jpg'),
                'raised'    => 500_000_124,
                'target'    => 700_000_000,
                'days_left' => 2,
            ],
            3 => [
                'id'        => 3,
                'slug'      => 'bantuan-anak-yatim-dan-dhuafa',
                'category'  => 'Pendidikan',
                'title'     => 'Penyaluran Bantuan untuk Anak Yatim dan Dhuafa',
                'image'     => asset('images/p3.jpg'),
                'banner'    => asset('images/p3.jpg'),
                'raised'    => 235_366_942,
                'target'    => 300_000_000,
                'days_left' => 25,
            ],
        ];
    }
}
