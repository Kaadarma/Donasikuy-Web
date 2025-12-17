<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\EmailChangeRequest;
use App\Notifications\VerifyNewEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

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
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar'=> ['nullable', 'image', 'max:2048'],
        ]);

        // update data yang boleh langsung
        $user->name = $data['name'];
        $user->phone = $data['phone'] ?? $user->phone;

        // avatar
        if ($request->hasFile('avatar')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->foto_profil = $path;
        }

        $user->save();

        // kalau email tidak berubah, selesai
        $newEmail = $data['email'];
        if ($newEmail === $user->email) {
            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
        }

        // ====== EMAIL BERUBAH: buat request verifikasi ======
        EmailChangeRequest::where('user_id', $user->id)->delete(); // biar 1 aktif saja

        // bersihin expired (opsional)
        EmailChangeRequest::where('expires_at', '<', now())->delete();

        $rawToken  = Str::random(64);
        $tokenHash = hash('sha256', $rawToken);

        EmailChangeRequest::create([
            'user_id'    => $user->id,
            'new_email'  => $newEmail,
            'token_hash' => $tokenHash,
            'expires_at' => now()->addMinutes(30),
        ]);

        $verifyUrl = route('profile.email.verify', ['token' => $rawToken]);

        Notification::route('mail', $newEmail)
            ->notify(new VerifyNewEmailNotification($user->name, $verifyUrl));

        return redirect()->route('profile.email.notice')
            ->with('success', 'Kami sudah mengirim link verifikasi ke email baru kamu. Silakan cek inbox/spam.')
            ->with('pending_new_email', $newEmail);
    }

    public function verifyNewEmail(string $token)
    {
        $user = Auth::user();
        $tokenHash = hash('sha256', $token);

        $req = EmailChangeRequest::where('user_id', $user->id)
            ->where('token_hash', $tokenHash)
            ->first();

        if (!$req) {
            return redirect()->route('profile')
                ->withErrors(['email' => 'Link verifikasi tidak valid atau sudah digunakan.']);
        }

        if (now()->greaterThan($req->expires_at)) {
            $req->delete();
            return redirect()->route('profile')
                ->withErrors(['email' => 'Link verifikasi sudah kadaluarsa. Silakan ubah email lagi.']);
        }

        // safety: kalau email sudah dipakai orang lain
        if (\App\Models\User::where('email', $req->new_email)->where('id', '!=', $user->id)->exists()) {
            $req->delete();
            return redirect()->route('profile')
                ->withErrors(['email' => 'Email tersebut sudah digunakan akun lain.']);
        }

        $user->email = $req->new_email;
        $user->email_verified_at = now();
        $user->save();

        $req->delete();

        return redirect()->route('profile')->with('success', 'Email baru berhasil diverifikasi & disimpan.');
    }

    public function resendNewEmail(Request $request)
    {
        $user = Auth::user();

        $req = EmailChangeRequest::where('user_id', $user->id)->first();

        if (!$req) {
            return redirect()->route('profile')->withErrors(['email' => 'Tidak ada permintaan perubahan email yang aktif.']);
        }

        // buat token baru + perpanjang masa berlaku
        $rawToken = Str::random(64);
        $req->update([
            'token_hash' => hash('sha256', $rawToken),
            'expires_at' => now()->addMinutes(30),
        ]);

        $verifyUrl = route('profile.email.verify', ['token' => $rawToken]);

        Notification::route('mail', $req->new_email)
            ->notify(new VerifyNewEmailNotification($user->name, $verifyUrl));

        return back()
            ->with('success', 'Link verifikasi berhasil dikirim ulang.')
            ->with('pending_new_email', $req->new_email);
    }



}
