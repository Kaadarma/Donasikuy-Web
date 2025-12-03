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
                       class="w-full mt-1 border border-slate-300 rounded-md py-2.5 px-3 text-sm
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Masukkan email anda">
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Password Baru</label>
                <input type="password" name="password" required
                       class="w-full mt-1 border border-slate-300 rounded-md py-2.5 px-3 text-sm
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

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
</body>
</html>
