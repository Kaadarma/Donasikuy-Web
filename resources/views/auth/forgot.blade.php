@extends('layouts.app')

@section('authpage', true)

@section('title', 'Lupa Password')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center bg-slate-50 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border">
        <h2 class="text-2xl font-semibold text-center text-emerald-700 mb-6">
            Lupa Password?
        </h2>

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" required autofocus
                       class="w-full mt-1 border border-slate-300 rounded-md py-2.5 px-3 text-sm 
                              focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Masukkan email anda">
            </div>

            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white 
                           rounded-md py-2.5 font-medium shadow-sm transition">
                Kirim Link Reset Password
            </button>
        </form>

        <p class="text-sm text-center mt-6 text-slate-600">
            <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                ← Kembali ke Login
            </a>
        </p>
    </div>
</div>
@endsection
