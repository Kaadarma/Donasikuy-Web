<?php

namespace App\Http\Controllers;
use App\Models\Program;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class GalangDanaController extends Controller
{
    public function create()
    {
        return view('galang.create');
    }

    public function kategori(Program $program)
    {
        // pastikan draft ini milik user yang login
        abort_unless($program->user_id === auth()->id(), 403);

        // batasi hanya boleh pilih kategori saat status masih draft / rejected
        abort_unless(
            in_array($program->status, [
                Program::STATUS_DRAFT,
                Program::STATUS_REJECTED,
            ]),
            403
        );

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

        return view('galang.kategori', compact('categories', 'program'));
    }

    public function storeKategori(Request $request, Program $program)
    {
    abort_unless($program->user_id === auth()->id(), 403);

    $data = $request->validate([
        'category' => ['required', 'string', 'max:50'],
    ]);

    $program->update([
        'category' => $data['category'],
    ]);

    // next step: detail / upload image / deskripsi panjang
    return redirect()->route('galang.create'); // ganti ke step berikutnya kalau sudah ada
    }


    public function storeDraft(Request $request)
    {
    $data = $request->validate([
        'title' => ['required', 'string', 'max:150'],
        'short_description' => ['nullable', 'string', 'max:255'],
        'target' => ['nullable', 'integer', 'min:0'],
        'deadline' => ['nullable', 'date'],
    ]);

    $program = Program::create([
        'user_id' => auth()->id(),
        'title' => $data['title'],
        'short_description' => $data['short_description'] ?? null,
        'target' => $data['target'] ?? 0,
        'deadline' => $data['deadline'] ?? null,
        'status' => Program::STATUS_DRAFT, // atau 'draft'
        'slug' => Str::slug($data['title']) . '-' . Str::lower(Str::random(6)),
        'is_active' => true,
    ]);

    return redirect()->route('galang.kategori', $program->id);
    }



    public function form(Request $request)
    {
        $jenis = $request->query('jenis');

        // validasi jenis dari query
        abort_unless(in_array($jenis, ['medis', 'lainnya']), 404);

        // kategori kamu (buat dropdown/opsi kalau jenis = lainnya)
        $categories = [
            ['icon' => '🎓', 'name' => 'Pendidikan', 'slug' => 'pendidikan'],
            ['icon' => '🌋', 'name' => 'Bencana Alam', 'slug' => 'bencana-alam'],
            ['icon' => '🤝', 'name' => 'Kemanusiaan', 'slug' => 'kemanusiaan'],
            ['icon' => '👶', 'name' => 'Panti Asuhan', 'slug' => 'panti-asuhan'],
            ['icon' => '🌱', 'name' => 'Lingkungan', 'slug' => 'lingkungan'],
            ['icon' => '💚', 'name' => 'Sedekah', 'slug' => 'sedekah'],
        ];

        return view('galang.form', compact('jenis', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis' => ['required', 'in:medis,lainnya'],
            'title' => ['required', 'string', 'max:150'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target' => ['nullable', 'integer', 'min:0'],
            'deadline' => ['nullable', 'date'],
            'category' => ['nullable', 'string', 'max:50'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($data['jenis'] === 'lainnya' && empty($data['category'])) {
            return back()
                ->withErrors(['category' => 'Kategori wajib dipilih untuk galang dana lainnya.'])
                ->withInput();
        }

        // simpan image
        $imagePath = $request->file('image')->store('programs', 'public');

        $program = Program::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'target' => $data['target'] ?? 0,
            'deadline' => $data['deadline'] ?? null,
            'category' => $data['jenis'] === 'medis' ? 'medis' : $data['category'],
            'image' => $imagePath,
            'status' => Program::STATUS_DRAFT,
            'slug' => Str::slug($data['title']) . '-' . Str::lower(Str::random(6)),
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.campaigns.saved', $program->id)
            ->with('success', 'Permintaan galang dana kamu sudah disimpan sebagai draft.');

    }
    





}

