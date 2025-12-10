<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProgramController; // ← tambahin ini

class LandingController extends Controller
{
    public function index()
    {
        // Stats untuk stat cards
        $stats = [
            'total_donasi'  => 11289569,
            'total_donatur' => 45,
            'total_program' => 6,
        ];

        // 🔥 GANTI DI SINI:
        // Dulu: $programs = [ ... array manual ... ];
        // Sekarang: ambil dari ProgramController supaya ada id & slug
        $programs = app(ProgramController::class)->allPrograms();
        // Kalau mau batasi misalnya cuma 6:
        // $programs = array_slice(app(ProgramController::class)->allPrograms(), 0, 6);

        // Banner/hero slider
        $banners = [
            [
                'image' => asset('images/bencana.jpg'),
                'title' => 'Bantuan Sosial untuk Masyarakat Menengah di masa PPKM',
                'cta'   => route('landing') . '#program',
            ],
        ];

        // Inspirasi (posts)
        $posts = [
            ['title' => 'The More Important the Work, the More Important the Rest', 'image' => asset('images/bencana.jpg'), 'date' => '28 Juli 2021', 'url' => '#'],
            ['title' => 'Maecenas dapibus augue eu magna placerat, eget volutpat urna aliquam.', 'image' => asset('images/bencana1.jpg'), 'date' => '5 Juli 2021', 'url' => '#'],
            ['title' => 'How To Build Your Personal Resilience', 'image' => asset('images/bencana.jpg'), 'date' => '17 Juni 2021', 'url' => '#'],
            ['title' => 'Tips Menggalang Donasi Online dengan Efektif', 'image' => asset('images/bencana1.jpg'), 'date' => '10 Agustus 2021', 'url' => '#'],
            ['title' => 'Kisah Relawan di Tengah Bencana Alam', 'image' => asset('images/bencana1.jpg'), 'date' => '25 September 2021', 'url' => '#'],
            ['title' => 'Kisah Relawan di Tengah Bencana Alam', 'image' => asset('images/bencana.jpg'), 'date' => '25 September 2021', 'url' => '#'],
        ];

        return view('landing', compact('stats', 'programs', 'banners', 'posts'));
    }
}
