<?php

namespace App\Http\Controllers;

class NewsController extends Controller
{
    public function inspirasi()
    {
        $items = $this->seed();            // daftar artikel untuk halaman Inspirasi
        return view('inspirasi.index', compact('items'));
    }

   public function show(string $slug)
{
    $items   = $this->seed();
    $article = collect($items)->firstWhere('slug', $slug);
    abort_unless($article, 404);

    // related by category
    $related = collect($items)
        ->where('slug', '!=', $slug)
        ->where('category', $article['category'] ?? null)
        ->take(3)->values()->all();

    // prev/next (berdasar urutan array)
    $idx  = collect($items)->search(fn($a) => $a['slug'] === $slug);
    $prev = $idx !== false && $idx > 0              ? $items[$idx-1] : null;
    $next = $idx !== false && $idx < count($items)-1 ? $items[$idx+1] : null;

    return view('inspirasi.show', compact('article', 'related', 'prev', 'next'));
}


    /** Dummy data (ganti ke DB kapanpun) */
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
                'avatar' => asset('images/humans.jpg')
            ],
            'gallery'      => [
                asset('images/bencana.jpg'),
                asset('images/bencana1.jpg'),
                asset('images/bencana.jpg'),
            ],
            'content'      => [
                'Hujan deras tidak menghalangi semangat para relawan dari Komunitas BantuYuk untuk menyalurkan bantuan sembako kepada warga di daerah bantaran Sungai Badung, Denpasar. Dengan perahu kecil, mereka menyusuri jalur yang tergenang air, membawa paket bantuan berisi beras, minyak goreng, mie instan, dan kebutuhan pokok lainnya.',
                
                'Program ini merupakan hasil dari penggalangan dana yang dibuka selama tiga minggu terakhir, yang berhasil mengumpulkan lebih dari Rp 150 juta. Dana tersebut digunakan untuk membeli 1.000 paket sembako yang ditargetkan untuk keluarga kurang mampu dan para lansia di sekitar lokasi terdampak banjir musiman.',
                
                'Koordinator lapangan, I Made Wira, mengatakan bahwa penyaluran dilakukan secara bertahap agar semua bantuan dapat tepat sasaran. “Kami berusaha memastikan setiap paket diterima langsung oleh penerima manfaat, bukan lewat perantara. Dengan cara ini, kami ingin menjaga transparansi dan kepercayaan para donatur,” ujarnya.',
                
                'Selain sembako, tim relawan juga membawa beberapa kebutuhan tambahan seperti selimut, obat-obatan ringan, serta mainan untuk anak-anak. Keceriaan terlihat di wajah warga ketika menerima bantuan tersebut, terutama para orang tua yang mengaku sangat terbantu di tengah kondisi sulit.',
                
                'Salah satu penerima bantuan, Ni Ketut Sari (62), mengungkapkan rasa syukurnya. “Kami tidak tahu harus bagaimana lagi. Air naik sejak kemarin malam, kami hanya bisa bertahan. Terima kasih banyak untuk anak-anak muda yang sudah datang membantu,” ujarnya dengan mata berkaca-kaca.',
                
                'Kegiatan ini juga menjadi momentum untuk mempererat solidaritas masyarakat. Banyak relawan baru yang bergabung setelah melihat postingan kegiatan di media sosial. Mereka datang bukan hanya dari Denpasar, tetapi juga dari Gianyar, Tabanan, hingga Singaraja.',
                
                'Ke depan, tim BantuYuk berencana mengadakan program lanjutan berupa “Dapur Umum Keliling” untuk membantu warga terdampak bencana di daerah lain. Program ini diharapkan dapat terus menjadi inspirasi dan bukti nyata bahwa kepedulian kecil bisa membawa perubahan besar.',
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
                'Setiap Minggu pagi, belasan anak-anak yatim dan dhuafa berkumpul di Balai Banjar Pemogan untuk mengikuti kelas belajar gratis yang diadakan oleh relawan BantuYuk. Mereka diajarkan membaca, menulis, dan berhitung dengan cara yang menyenangkan.',
                'Tidak hanya akademik, kegiatan juga melibatkan pelatihan soft skill seperti kerja sama tim dan empati. “Kami ingin anak-anak tumbuh dengan rasa percaya diri dan semangat belajar tinggi,” ujar salah satu relawan pengajar, Kak Nanda.',
                'Program ini telah berjalan selama enam bulan dan berhasil membantu 40 anak mendapatkan nilai lebih baik di sekolah.',
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
                'Di Pulau Serangan, akses terhadap air bersih menjadi permasalahan utama bagi ratusan kepala keluarga. Melalui program donasi “Air untuk Kehidupan”, tim BantuYuk menyalurkan bantuan berupa tandon air, pompa tenaga surya, dan filter air portabel.',
                'Berkat bantuan para donatur, kini warga tidak perlu lagi menyeberang jauh untuk mendapatkan air bersih. Anak-anak bisa kembali bersekolah tepat waktu, dan ibu-ibu bisa beraktivitas tanpa harus antre air sejak subuh.',
                'Program ini menjadi contoh nyata bagaimana teknologi sederhana dapat memberi dampak besar pada kehidupan masyarakat pesisir.',
            ],
        ],
    ];
}

}
