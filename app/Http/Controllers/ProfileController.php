<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $donationQuery = Donation::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['success', 'settlement', 'paid']);

        $summary = [
            'total_donasi' => (int) $donationQuery->sum('amount'),
            'frekuensi' => (int) $donationQuery->count(),
        ];

        return view('profile.index', compact('user', 'summary'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->name = $data['name'];

        if ($request->hasFile('avatar')) {
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
