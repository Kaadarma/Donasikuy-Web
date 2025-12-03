<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | DonasiKuy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border">

        <h2 class="text-2xl font-semibold text-center text-emerald-700 mb-6">
            Reset Password
        </h2>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" required
                        value="{{ request('email') }}"
                        class="w-full mt-1 border border-slate-300 rounded-md py-2.5 px-3 text-sm
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        readonly>
            </div>

            {{-- PASSWORD BARU --}}
            <div>
                <label class="text-sm font-medium text-slate-700">Password Baru</label>
                <div class="relative mt-1 pw-field">
                    <input type="password" name="password" required
                        data-pw="input"
                        class="w-full border border-slate-300 rounded-md py-2.5 px-3 pr-10 text-sm
                                focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <button type="button"
                            data-pw="toggle"
                            class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                        {{-- icon open --}}
                        <svg data-pw="icon-open" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                        </svg>
                        {{-- icon closed --}}
                        <svg data-pw="icon-closed" class="h-4 w-4 hidden" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18M10.477 10.477A3 3 0 0115 12m-3-7c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.253 2.548M9.88 9.88C9.34 10.54 9 11.37 9 12a3 3 0 003 3c.63 0 1.46-.34 2.12-.88" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div>
                <label class="text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required
                    class="w-full mt-1 border border-slate-300 rounded-md py-2.5 px-3 text-sm
                            focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            @if ($errors->any())
                <div class="text-red-600 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white 
                       rounded-md py-2.5 font-medium shadow-sm transition">
                Reset Password
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-slate-600">
            <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                ← Kembali ke Login
            </a>
        </p>
    </div>

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

</body>
</html>
