<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\PreRegistration;
use App\Mail\PreRegisterVerifyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PreRegisterVerifyNotification;





class AuthController extends Controller
{
    // SHOW LOGIN
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    $request->session()->regenerate();

    // kalau kamu masih punya logic verified utk kasus lain, ini boleh
    if (is_null(Auth::user()->email_verified_at)) {
        Auth::logout();
        return back()->withErrors(['email' => 'Email belum diverifikasi.'])->onlyInput('email');
    }

    return redirect()->intended('/')->with('success', 'Berhasil masuk!');
    }

    // SHOW REGISTER
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // PROSES REGISTER
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email', 'unique:pre_registrations,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'agree' => ['accepted'],
        ]);

        // bersihin request lama yang expired (opsional tapi bagus)
        PreRegistration::where('expires_at', '<', now())->delete();

        $rawToken = Str::random(64);
        $tokenHash = hash('sha256', $rawToken);

        PreRegistration::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password_hash' => Hash::make($data['password']),
                'token_hash' => $tokenHash,
                'expires_at' => now()->addMinutes(30),
            ]
        );

        $verifyUrl = route('preregister.verify', ['token' => $rawToken]);

        Notification::route('mail', $data['email'])
            ->notify(new PreRegisterVerifyNotification(
                $data['name'],
                $verifyUrl
            ));


        return redirect()->route('register.notice')
            ->with('success', 'Link verifikasi sudah dikirim ke email kamu.')
            ->with('preregister_email', $data['email']);

    }


    //LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Berhasil keluar.');
    }

    // forgot password
        public function showForgotForm()
    {
        return view('auth.forgot');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim. Silakan cek inbox atau folder spam email kamu.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
    
    public function showResetForm($token)
    {
        return view('auth.reset', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = $password;
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil diubah!')
            : back()->withErrors(['email' => [__($status)]]);
    }

   // Verifikasi waktu daftar
    public function verifyPreRegister(string $token)
    {
    $tokenHash = hash('sha256', $token);

    $pre = PreRegistration::where('token_hash', $tokenHash)->first();

    if (!$pre) {
        return redirect()->route('register')->withErrors(['email' => 'Link verifikasi tidak valid atau sudah digunakan.']);
    }

    if (now()->greaterThan($pre->expires_at)) {
        $pre->delete();
        return redirect()->route('register')->withErrors(['email' => 'Link verifikasi sudah kadaluarsa. Silakan daftar ulang.']);
    }

    // final check kalau email udah keburu ada di users (misal race condition)
    if (User::where('email', $pre->email)->exists()) {
        $pre->delete();
        return redirect()->route('login')->with('success', 'Email sudah terdaftar, silakan login.');
    }

    $user = User::create([
        'name' => $pre->name,
        'email' => $pre->email,
        'password' => $pre->password_hash, // ini sudah hash
        'email_verified_at' => now(),
    ]);

    $pre->delete();

    Auth::login($user);
    request()->session()->regenerate();

    return redirect()->route('landing')->with('success', 'Email terverifikasi! Akun kamu sudah dibuat dan kamu sudah login.');
    }

    // Resend Email
    public function resendPreRegister(Request $request)
    {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $pre = \App\Models\PreRegistration::where('email', $request->email)->first();

    if (!$pre) {
        return back()->withErrors(['Email tidak ditemukan atau sudah diverifikasi.']);
    }

    if (now()->greaterThan($pre->expires_at)) {
        $pre->delete();
        return back()->withErrors(['Link sebelumnya sudah kadaluarsa. Silakan daftar ulang.']);
    }

    $rawToken = \Illuminate\Support\Str::random(64);
    $pre->update([
        'token_hash' => hash('sha256', $rawToken),
        'expires_at' => now()->addMinutes(30),
    ]);

    $verifyUrl = route('preregister.verify', ['token' => $rawToken]);

    Notification::route('mail', $pre->email)
        ->notify(new PreRegisterVerifyNotification(
            $pre->name,
            $verifyUrl
        ));


    return back()->with('success', 'Link verifikasi berhasil dikirim ulang.')
                 ->with('preregister_email', $pre->email);
    }



}
