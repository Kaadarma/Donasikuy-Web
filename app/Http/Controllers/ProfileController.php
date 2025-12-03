<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Halaman profil utama
    public function index()
    {
        $user = Auth::user();

        // sementara dummy, nanti bisa diganti data beneran dari tabel donasi
        $summary = [
            'total_donasi'   => 300000,
            'frekuensi'      => 6,
        ];

        return view('profile.index', compact('user', 'summary'));
    }

    // Form edit profil
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    // Proses update profil (nama + avatar dulu)
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'   => ['required', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        $user->name = $data['name'];

        if ($request->hasFile('avatar')) {
            // hapus foto lama kalau ada
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->foto_profil = $path;
        }

        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
