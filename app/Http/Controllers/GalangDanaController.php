<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GalangDanaController extends Controller
{
    public function create()
    {
        return view('galang.create');
    }

    // Step 2: Pilih Kategori Galang Dana
    public function kategori()
    {
        $categories = [
            [
                'icon' => '🎓',
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
            ],
            [
                'icon' => '🌋',
                'name' => 'Bencana Alam',
                'slug' => 'bencana-alam',
            ],
            [
                'icon' => '🤝',
                'name' => 'Kemanusiaan',
                'slug' => 'kemanusiaan',
            ],
            [
                'icon' => '👶',
                'name' => 'Panti Asuhan',
                'slug' => 'panti-asuhan',
            ],
            [
                'icon' => '🌱',
                'name' => 'Lingkungan',
                'slug' => 'lingkungan',
            ],
            [
                'icon' => '💚',
                'name' => 'Sedekah',
                'slug' => 'sedekah',
            ],
        ];

        return view('galang.kategori', compact('categories'));
    }
}

