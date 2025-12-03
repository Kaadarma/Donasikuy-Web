@extends('layouts.app')

@section('authpage', true)

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex">
    <div class="hidden md:flex md:w-1/2 bg-cover bg-center"
        style="background-image: url('{{ asset('images/login.png') }}');">
        <div class="flex flex-col justify-center items-center w-full bg-black/50 text-white">
            <h1 class="text-3xl font-bold mb-3">Selamat Datang di Donasikuy</h1>
            <p class="max-w-md text-center text-sm opacity-90">Tempat Anda bisa berbagi kebaikan untuk sesama</p>
        </div>
    </div>

    <div class="flex-1 flex flex-col justify-center items-center p-8">
        <div class="w-full max-w-md">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" class="h-20" alt="BantuYuk Logo">
            </div>

            <h2 class="text-center text-2xl font-semibold mb-6">Masuk ke Akun Anda</h2>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form action="{{ route('login.attempt') }}" method="POST"
                class="space-y-6 bg-white p-6 rounded-2xl shadow-lg border border-slate-100">
                @csrf

                <!-- EMAIL -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
                            </svg>
                        </span>
                        <input type="email" name="email" required
                            class="w-full pl-9 pr-3 py-2.5 rounded-md border border-slate-300 text-sm placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                            placeholder="Masukkan Email Anda">
                    </div>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Password</label>
                    <div class="relative mt-1 pw-field">
                        <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c0-1.104.896-2 2-2h4a2 2 0 012 2v8a2 2 0 01-2 2h-4a2 2 0 01-2-2v-8z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 11V9a4 4 0 018 0v2" />
                            </svg>
                        </span>
                        <input data-pw="input" type="password" name="password" required
                            class="w-full pl-9 pr-10 py-2.5 rounded-md border border-slate-300 text-sm placeholder:text-slate-400
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Minimal 8 karakter">
                        <button type="button" data-pw="toggle"
                            class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg data-pw="icon-open" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                            </svg>
                            <svg data-pw="icon-closed" class="h-4 w-4 hidden" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3l18 18M10.477 10.477A3 3 0 0115 12m-3-7c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.253 2.548M9.88 9.88C9.34 10.54 9 11.37 9 12a3 3 0 003 3c.63 0 1.46-.34 2.12-.88" />
                            </svg>
                        </button>
                    </div>

                    <!-- OPSI -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-slate-600">
                            <input type="checkbox"
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            Ingat saya
                        </label>
                        <a href="{{ route('password.request') }}" class="text-emerald-600 hover:text-emerald-700 font-medium mt-5">Lupa
                            Password?</a>
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white rounded-md py-2.5 font-medium text-sm shadow-sm transition-transform hover:-translate-y-0.5 mt-5">
                        Masuk
                    </button>

                    <!-- GARIS PEMISAH -->
                    <div class="mt-6 flex items-center gap-3">
                        <span class="h-px flex-1 bg-slate-200"></span>
                        <span class="text-xs text-slate-400 tracking-widest">ATAU</span>
                        <span class="h-px flex-1 bg-slate-200"></span>
                    </div>

                    <!-- LOGIN GOOGLE -->
                    <a href="{{ route('auth.google.redirect') }}"
                        class="w-full inline-flex items-center justify-center gap-2 border border-slate-300 hover:bg-slate-50 py-2.5 rounded-md text-sm text-slate-700 font-medium transition mt-5">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-4 w-4" alt="Google Icon">
                        Masuk dengan Google
                    </a>

            </form>

            <p class="text-center text-sm text-slate-600 mt-6">
                Belum punya akun?
                <a href="/register" class="text-emerald-600 hover:text-emerald-700 font-medium">Daftar Sekarang</a>
            </p>

            <div class="text-center mt-6">
                <a href="/" class="text-xs text-slate-400 hover:text-slate-600">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
@endsection

    @section('scripts')
    <script>
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-pw="toggle"]');
            if (!btn) return;
            const wrap = btn.closest('.pw-field');
            const input = wrap?.querySelector('[data-pw="input"]');
            const openI = wrap?.querySelector('[data-pw="icon-open"]');
            const closeI = wrap?.querySelector('[data-pw="icon-closed"]');
            if (!input) return;
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            openI?.classList.toggle('hidden', show);
            closeI?.classList.toggle('hidden', !show);
        });
    </script>
    @endsection     

