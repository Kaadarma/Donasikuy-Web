<?php

namespace App\Http\Controllers;

class ProgramController extends Controller
{
    public function index()
    {
        // kirim daftar program ke view list
        $programs = array_values($this->seed()); // buang key numerik, jadikan array biasa
        return view('programs.index', compact('programs'));
    }

    // Halaman detail: terima ID atau slug
    public function show($idOrSlug)
    {
        $all = $this->seed();

        // Cari by ID
        $program = $all[$idOrSlug] ?? null;

        // Jika tidak ketemu by ID, coba by slug
        if (!$program) {
            foreach ($all as $p) {
                if ($p['slug'] === $idOrSlug) {
                    $program = $p;
                    break;
                }
            }
        }

        abort_unless($program, 404);

        // Kabar Terbaru (untuk halaman detail)
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

    // Dummy data program
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
