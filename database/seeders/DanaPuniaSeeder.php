<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class DanaPuniaSeeder extends Seeder
{
    public function run(): void
    {
        Program::updateOrCreate(
            ['slug' => 'galangan-palinggih-di-pura-rambut-siwi-tabanan'],
            [
                'title' => 'Galangan Palinggih di Pura Rambut Siwi Tabanan',
                'category' => 'pura',
                'image' => 'images/bale1.jpeg',
                'banner' => 'images/bale1.jpeg',
                'description' => 'Mendukung perbaikan fasilitas pura agar lebih layak untuk umat.',
                'target' => 0,
                'is_active' => true,
            ]
        );

        Program::updateOrCreate(
            ['slug' => 'banjir-sekolah-manggis-karangasem'],
            [
                'title' => 'Sekolah di Manggis, Karangasem Terendam Banjir hingga Sisakan Lumpur',
                'category' => 'sekolah',
                'image' => 'images/sekolah1.jpeg',
                'banner' => 'images/sekolah.jpeg',
                'description' => 'Mendukung kebutuhan sarana upacara dan gotong royong warga.',
                'target' => 0,
                'is_active' => true,
            ]
        );

        Program::updateOrCreate(
            ['slug' => 'punia-Bencana-Alam-Pohon-Tumbang-di-areal-Pura-Pucak-Bukit-Sinunggal-Desa-Adat-Tajun'],
            [
                'title' => 'Punia Bencana Alam Pohon Tumbang di areal Pura Pucak Bukit Sinunggal Desa Adat Tajun',
                'category' => 'pura',
                'image' => 'images/bale2.jpg',
                'banner' => 'images/bale2.jpg',
                'description' => 'Mendukung pembelajaran dan kegiatan pasraman untuk generasi muda.',
                'target' => 2000000,
                'is_active' => true,
            ]
        );

        Program::updateOrCreate(
            ['slug' => 'palinggih-di-pura-pasek-bendesa-batur-terbakar'],
            [
                'title' => 'Punia Palinggih di Pura Pasek Bendesa Batur Terbakar',
                'category' => 'pura',
                'image' => 'images/pura1.jpeg',
                'banner' => 'images/pura1.jpeg',
                'description' => 'Mendukung pembelajaran dan kegiatan pasraman untuk generasi muda.',
                'target' => 10000000,
                'is_active' => true,
            ]
        );
    }
}
