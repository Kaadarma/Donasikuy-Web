<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class NewsController extends Controller
{
    public function index()
    {
        $items = $this->seed();

        return view('inspirasi.index', compact('items'));
    }

    public function show(string $slug)
    {
        $items = $this->seed();

        $article = collect($items)->firstWhere('slug', $slug);
        abort_unless($article, 404);

        $related = collect($items)
            ->where('slug', '!=', $slug)
            ->where('category', $article['category'] ?? null)
            ->take(3)
            ->values()
            ->all();

        $idx = collect($items)->search(fn ($a) => ($a['slug'] ?? null) === $slug);
        $prev = ($idx !== false && $idx > 0) ? $items[$idx - 1] : null;
        $next = ($idx !== false && $idx < count($items) - 1) ? $items[$idx + 1] : null;

        return view('inspirasi.show', compact('article', 'related', 'prev', 'next'));
    }

    public function home()
    {
        $posts = collect($this->seed())
            ->sortByDesc('published_at')
            ->take(3)
            ->map(fn ($a) => [
                'slug' => $a['slug'],
                'title' => $a['title'],
                'image' => $a['image'],
                'date' => Carbon::parse($a['published_at'])->translatedFormat('d M Y'),
                'excerpt' => $a['excerpt'] ?? '',
            ])
            ->values()
            ->all();

        return view('landing', compact('posts'));
    }

    private function seed(): array
    {
        return [
            [
                'slug' => 'kisah-relawan-bagi-sembako',
                'title' => 'Kisah Relawan Membagikan Sembako ke Warga',
                'image' => asset('images/bagi1.jpeg'),
                'published_at' => '2025-10-30',
                'excerpt' => 'Relawan menembus hujan demi menyalurkan sembako di wilayah bantaran sungai...',
                'category' => 'Kemanusiaan',
                'read_time' => 5,
                'tags' => ['relawan', 'donasi', 'kemanusiaan'],
                'author' => [
                    'name' => 'Putri Anggraeni',
                    'avatar' => asset('images/humans.jpg'),
                ],
                'gallery' => [
                    asset('images/bencana.jpg'),
                    asset('images/bencana1.jpg'),
                    asset('images/bencana.jpg'),
                ],
                'content' => [
                    'Hujan deras tidak menghalangi semangat para relawan dari Komunitas BantuYuk untuk menyalurkan bantuan sembako kepada warga di daerah bantaran Sungai Badung, Denpasar.',
                    'Program ini merupakan hasil dari penggalangan dana yang dibuka selama tiga minggu terakhir.',
                    'Koordinator lapangan, I Made Wira, mengatakan bahwa penyaluran dilakukan secara bertahap.',
                ],
            ],
            [
                'slug' => 'kelas-belajar-anak-yatim',
                'title' => 'Kelas Belajar Untuk Anak Yatim di Denpasar',
                'image' => asset('images/yatim1.jpg'),
                'published_at' => '2025-10-28',
                'excerpt' => 'Ruang belajar bersama yang hangat dan menyenangkan untuk anak-anak...',
                'category' => 'Pendidikan',
                'read_time' => 4,
                'tags' => ['pendidikan', 'anak-yatim', 'komunitas'],
                'content' => [
                    'Setiap Minggu pagi, belasan anak-anak yatim dan dhuafa berkumpul di Balai Banjar Pemogan.',
                    'Program ini telah berjalan selama enam bulan dan berhasil membantu 40 anak.',
                ],
            ],
            [
                'slug' => 'donasi-air-bersih-pulau',
                'title' => 'Donasi Air Bersih untuk Pulau Terpencil',
                'image' => asset('images/krisis.jpg'),
                'published_at' => '2025-10-25',
                'excerpt' => 'Pengadaan tandon dan filter air portabel bagi warga di pulau terpencil...',
                'category' => 'Lingkungan',
                'read_time' => 6,
                'tags' => ['air-bersih', 'lingkungan', 'donasi'],
                'content' => [
                    'Di Pulau Serangan, akses terhadap air bersih menjadi permasalahan utama.',
                    'Program ini menjadi contoh nyata bagaimana teknologi sederhana dapat memberi dampak besar.',
                ],
            ],
        ];
    }
}
