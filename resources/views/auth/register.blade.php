@extends('layouts.app')

@section('authpage', true)
@section('title', 'Buat Akun')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="min-h-screen grid md:grid-cols-2">

        {{-- Sisi gambar kiri --}}
        <aside class="hidden md:block relative">
            <div class="absolute inset-0 bg-[url('{{ asset('images/login.png') }}')] bg-cover bg-center"></div>
            <div class="absolute inset-0 bg-black/35"></div>

            <div class="relative z-10 h-full flex items-center justify-center text-center px-8">
                <div>
                    <h1 class="text-white text-3xl font-semibold mb-2">Buat Akun di Donasikuy</h1>
                    <p class="text-slate-200 text-sm">Masukan Data Anda untuk Masuk ke Donasikuy</p>
                </div>
            </div>
        </aside>

        {{-- Form kanan --}}
        <main class="flex items-start md:items-center justify-center px-4 py-5">
            <div class="w-full max-w-md">
                <div class="mx-auto w-full max-w-md rounded-2xl bg-white shadow-lg border border-slate-200 p-6">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/logo.png') }}" class="h-20" alt="BantuYuk Logo">
                    </div>

                    <h2 class="text-center text-2xl font-semibold text-slate-900 mb-1">Daftar Akun</h2>

                    @if ($errors->any())
                        <div class="mt-4 rounded-md bg-red-50 text-red-700 text-sm px-3 py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.attempt') }}" class="mt-6 space-y-4">
                        @csrf

                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 12a4 4 0 10-8 0 4 4 0 008 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
                                    </svg>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full pl-9 pr-3 py-2.5 rounded-md border border-slate-300 text-sm
                                              placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500
                                              focus:border-emerald-500"
                                       placeholder="Nama Anda">
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Email</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 12H8m8-4H8m4 8H8M21 12c0 4.971-4.029 9-9 9s-9-4.029-9-9 4.029-9 9-9 9 4.029 9 9z" />
                                    </svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full pl-9 pr-3 py-2.5 rounded-md border border-slate-300 text-sm
                                              placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500
                                              focus:border-emerald-500"
                                       placeholder="email@contoh.com">
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password + eye + strength bar --}}
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
                                       class="w-full pl-9 pr-10 py-2.5 rounded-md border border-slate-300 text-sm
                                              placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500
                                              focus:border-emerald-500"
                                       placeholder="Minimal 8 karakter">
                                <button type="button" data-pw="toggle"
                                        class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <svg data-pw="icon-open" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                                    </svg>
                                    <svg data-pw="icon-closed" class="h-4 w-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 3l18 18M10.477 10.477A3 3 0 0115 12m-3-7c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.253 2.548M9.88 9.88C9.34 10.54 9 11.37 9 12a3 3 0 003 3c.63 0 1.46-.34 2.12-.88" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-2 h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                <div id="pwBar" class="h-full w-0 bg-emerald-600 transition-all duration-500"></div>
                            </div>
                            <p id="pwHint" class="mt-1 text-xs text-slate-500">
                                Gunakan huruf besar, angka, dan simbol untuk password lebih kuat.
                            </p>

                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm
                                          focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="Ulangi password">
                        </div>

                        {{-- Terms --}}
                        <label class="flex items-start gap-3 text-sm text-slate-600">
                            <input type="checkbox" name="agree"
                                   class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                   required>
                            <span>
                                Saya setuju dengan
                                <a href="#" class="text-emerald-600 hover:text-emerald-700 font-medium">
                                    Syarat & Kebijakan
                                </a>.
                            </span>
                        </label>

                        {{-- Submit --}}
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500
                                       hover:from-emerald-700 hover:to-emerald-600 text-white rounded-md
                                       py-2.5 font-medium shadow-sm transition-transform hover:-translate-y-0.5">
                            Buat Akun
                        </button>

                        {{-- Divider --}}
                        <div class="flex items-center gap-3 my-2">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <span class="text-xs text-slate-400 tracking-widest">ATAU</span>
                            <span class="h-px flex-1 bg-slate-200"></span>
                        </div>

                        {{-- Google --}}
                        <a href="{{ route('auth.google.redirect') }}"
                           class="w-full inline-flex items-center justify-center gap-2 border border-slate-300
                                  hover:bg-slate-50 py-2.5 rounded-md text-sm text-slate-700 font-medium transition">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-4 w-4" alt="Google Icon">
                            Daftar dengan Google
                        </a>

                        <p class="text-center text-sm text-slate-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                                Masuk
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // toggle show/hide password
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

    // password strength bar
    (function() {
        const pw = document.querySelector('[name="password"]');
        const bar = document.getElementById('pwBar');
        const hint = document.getElementById('pwHint');
        if (!pw || !bar) return;
        pw.addEventListener('input', () => {
            const v = pw.value || '';
            let score = 0;
            if (v.length >= 8) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;

            const widths = ['0%', '25%', '50%', '75%', '100%'];
            bar.style.width = widths[score];

            bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-emerald-600');
            if (score <= 1) bar.classList.add('bg-red-500');
            else if (score <= 2) bar.classList.add('bg-yellow-500');
            else bar.classList.add('bg-emerald-600');

            if (hint) {
                hint.textContent =
                    score <= 1 ? 'Password lemah — tambahkan angka & simbol.' :
                    score === 2 ? 'Lumayan — coba tambah huruf besar & simbol.' :
                    'Mantap! Password cukup kuat.';
            }
        });
    })();
</script>
@endsection
