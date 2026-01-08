<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProgramController;
use Carbon\Carbon;
use App\Models\Donation;
use App\Models\Program;
use App\Models\KycSubmission; 

class LandingController extends Controller
{
public function index()
{
    $validStatuses = ['settlement','capture','success','paid'];

    // 1) ambil programs dari seed (via ProgramController)
    $programs = app(ProgramController::class)->allPrograms(); // seed list (decorated)

    // setelah ambil $programs
    $programs = collect($programs)->map(function ($p) {

        // default fallback
        $authorName = 'Donasikuy';

        // hanya program DB yang punya id & user_id
        if (!empty($p['id'])) {

            $programModel = Program::find($p['id']);

            if ($programModel) {
                $kyc = KycSubmission::where('user_id', $programModel->user_id)
                    ->where('status', 'approved')
                    ->latest()
                    ->first();

                if ($kyc) {
                    $authorName = $kyc->account_type === 'organisasi'
                        ? ($kyc->entity_name ?? $kyc->full_name)
                        : $kyc->full_name;
                }
            }
        }

        // suntik author ke array program
        $p['author_name'] = $authorName;

        return $p;
    })->values()->all();

    // 2) total donasi dari seed = sum raised dari seed programs
    $seedTotalDonasi  = (int) collect($programs)->sum(fn($p) => (int)($p['raised'] ?? 0));

    $cardsTotalDonasi = (int) collect($programs)->sum(fn($p) => (int)($p['raised'] ?? 0));

    // DONATUR DB
    $dbTotalDonatur = (int) Donation::whereIn('status', $validStatuses)
        ->whereNotNull('user_id')
        ->distinct('user_id')
        ->count('user_id');

    // DONATUR SEED (SESSION)
    $seedTotalDonatur = (int) collect(session('seed_donations', []))
        ->filter(fn($d) =>
            !empty($d['user_id']) &&
            in_array(($d['status'] ?? null), $validStatuses)
        )
        ->pluck('user_id')
        ->unique()
        ->count();

    $totalDonatur = $dbTotalDonatur + $seedTotalDonatur;

    $totalProgram = (int) count($programs);

    $stats = [
        'total_donasi'  => $cardsTotalDonasi,
        'total_donatur' => $totalDonatur,
        'total_program' => $totalProgram,
    ];

    $banners = [
        [
            'image' => asset('images/bencana.jpg'),
            'title' => 'Bantuan Sosial untuk Masyarakat Menengah di masa PPKM',
            'cta'   => route('landing') . '#program',
        ],
    ];

    $items = $this->seed();

    $posts = collect($items)
        ->sortByDesc('published_at')
        ->take(6)
        ->map(fn ($a) => [
            'slug'    => $a['slug'],
            'title'   => $a['title'],
            'image'   => $a['image'],
            'date'    => Carbon::parse($a['published_at'])->translatedFormat('d M Y'),
            'excerpt' => $a['excerpt'] ?? '',
            'url'     => route('inspirasi.show', $a['slug']),
        ])
        ->values()
        ->all();

    return view('landing', compact('stats', 'programs', 'banners', 'posts'));
}


    private function seed(): array
    {
        return [
            [
                'slug'         => 'kisah-relawan-bagi-sembako',
                'title'        => 'Kisah Relawan Membagikan Sembako ke Warga',
                'image'        => asset('images/bagi1.jpeg'),
                'published_at' => '2025-10-30',
                'excerpt'      => 'Relawan menembus hujan demi menyalurkan sembako di wilayah bantaran sungai...',
                'category'     => 'Kemanusiaan',
                'read_time'    => 5,
                'tags'         => ['relawan', 'donasi', 'kemanusiaan'],
                'author'       => [
                    'name'   => 'Putri Anggraeni',
                    'avatar' => asset('images/humans.jpg'),
                ],
                'gallery'      => [
                    asset('images/bencana.jpg'),
                    asset('images/bencana1.jpg'),
                    asset('images/bencana.jpg'),
                ],
                'content'      => [
                    'Hujan deras tidak menghalangi semangat para relawan dari Komunitas BantuYuk untuk menyalurkan bantuan sembako.',
                ],
            ],
            [
                'slug'         => 'kelas-belajar-anak-yatim',
                'title'        => 'Kelas Belajar Untuk Anak Yatim di Denpasar',
                'image'        => asset('images/yatim1.jpg'),
                'published_at' => '2025-10-28',
                'excerpt'      => 'Ruang belajar bersama yang hangat dan menyenangkan untuk anak-anak...',
                'category'     => 'Pendidikan',
                'read_time'    => 4,
                'tags'         => ['pendidikan', 'anak-yatim', 'komunitas'],
                'content'      => [
                    'Setiap Minggu pagi, belasan anak-anak yatim dan dhuafa berkumpul untuk belajar.',
                ],
            ],
            [
                'slug'         => 'donasi-air-bersih-pulau',
                'title'        => 'Donasi Air Bersih untuk Pulau Terpencil',
                'image'        => asset('images/krisis.jpg'),
                'published_at' => '2025-10-25',
                'excerpt'      => 'Pengadaan tandon dan filter air portabel bagi warga di pulau terpencil...',
                'category'     => 'Lingkungan',
                'read_time'    => 6,
                'tags'         => ['air-bersih', 'lingkungan', 'donasi'],
                'content'      => [
                    'Di Pulau Serangan, akses terhadap air bersih menjadi permasalahan utama.',
                ],
            ],
        ];
    }
}