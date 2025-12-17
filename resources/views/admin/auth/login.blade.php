<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-slate-200">

        {{-- HEADER --}}
        <div class="px-8 pt-8 text-center">
            <h1 class="text-xl font-semibold text-slate-800">
                Admin DonasiKuy
            </h1>

        </div>

        {{-- BODY --}}
        <div class="px-8 py-6">

            {{-- ERROR MESSAGE --}}
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Email Admin
                    </label>
                    <input
                        type="email"
                        name="email_admin"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="admin@donasikuy.com"
                    >
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="••••••••"
                    >
                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="w-full mt-2 bg-emerald-600 text-white py-2.5 rounded-lg
                           font-medium hover:bg-emerald-700 transition">
                    Login
                </button>
            </form>
        </div>

        {{-- FOOTER --}}
        <div class="px-8 pb-6 text-center text-xs text-slate-400">
            © {{ now()->year }} DonasiKuy — Admin Panel
        </div>

    </div>

</body>
</html>
