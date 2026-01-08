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

        $valid = ['success','settlement','paid'];

        $donationQuery = Donation::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['success', 'settlement', 'paid']);

        // =========================
        // 1) DONASI DB
        // =========================
        $dbTotal = (int) Donation::where('user_id', $user->id)
            ->whereIn('status', $valid)
            ->sum('amount');

        $dbCount = (int) Donation::where('user_id', $user->id)
            ->whereIn('status', $valid)
            ->count();

        // =========================
        // 2) DONASI SEED (SESSION)
        // =========================
        $seedRows = collect(session('seed_donations', []))
            ->filter(fn ($d) =>
                ($d['user_id'] ?? null) == $user->id
                && in_array(($d['status'] ?? null), $valid)
            );

        $seedTotal = (int) $seedRows->sum('amount');
        $seedCount = (int) $seedRows->count();

        // =========================
        // 3) SUMMARY FINAL
        // =========================
        $summary = [
            'total_donasi' => $dbTotal + $seedTotal,
            'frekuensi'    => $dbCount + $seedCount,
        ];

        

        $donatedPrograms = Donation::query()
            ->where('user_id', $user->id)
            ->whereIn('status', $valid)
            ->selectRaw('program_id,
                SUM(amount) as user_total_amount,
                MAX(created_at) as last_donated_at
            ')
            ->groupBy('program_id')
            ->orderByDesc('last_donated_at')
            ->with(['program' => function ($q) {
                $q->select('id','title','slug','image','category','target','deadline','status','user_id');
            }, 'program.user:id,name'])
            ->get()
            ->filter(fn($row) => $row->program) // safety kalau program kehapus
            ->values();

        $seedDonations = collect(session('seed_donations', []))
        ->filter(fn($d) => ($d['status'] ?? null) && in_array($d['status'], $valid))
        ->groupBy('program_slug')
        ->map(function ($rows, $slug) {
            $total = $rows->sum('amount');
            $last  = $rows->max('created_at');

            // ambil data program seed dari ProgramController
            $pc = app(\App\Http\Controllers\ProgramController::class);
            $p  = $pc->findProgram($slug);

            if (!$p) return null;

            return (object) [
                'program_id' => 'seed:' . $slug,
                'user_total_amount' => $total,
                'last_donated_at' => $last,
                'program' => (object) [
                    'id' => null,
                    'slug' => $p['slug'],
                    'title' => $p['title'],
                    'image' => $p['image'], // ini sudah asset(...) dari seed
                    'category' => $p['category'] ?? null,
                ],
            ];
        })
        ->filter()
        ->values();

        $donatedPrograms = $donatedPrograms
            ->concat($seedDonations)
            ->sortByDesc('last_donated_at')
            ->values();



        return view('profile.index', compact('user', 'summary', 'donatedPrograms'));

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
