@extends('layouts.app')

@section('authpage', true)

@section('title', 'Verifikasi Akun')

@section('content')
    @php
        // Biar nggak error kalau $kyc belum dikirim dari controller
        $kyc = $kyc ?? [];
    @endphp

    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-5xl mx-auto">

            {{-- Header & Progress --}}
            <div class="mb-6">
                <p class="text-xs text-slate-500 font-medium uppercase tracking-[0.18em]">
                    Ringkasan
                </p>
                <h1 class="text-2xl font-semibold text-slate-900">
                    Verifikasi Akun
                </h1>

                <div class="mt-4 flex items-center gap-4">
                    <span class="text-xs text-slate-500 font-medium">25%</span>
                    <div class="flex-1 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                        {{-- Progress step 1 (25%) --}}
                        <div class="h-full w-[25%] bg-gradient-to-r from-[#04A777] to-[#A4FF3C]"></div>
                    </div>
                    <span class="text-xs text-slate-500">Langkah 1 dari 3</span>
                </div>
            </div>

            {{-- Card Utama --}}
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">

                {{-- Header Card --}}
                <div class="px-10 pt-8 pb-6 border-b border-slate-200 text-center">
                    <h2 class="text-2xl font-semibold text-slate-900">
                        Selamat Datang di DonasiKuy!
                    </h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Cuma butuh waktu beberapa menit untuk menyelesaikan verifikasi akun.
                    </p>

                    <div class="mt-6 inline-flex items-center gap-3 text-[11px] uppercase tracking-[0.25em] text-slate-500">
                        <div class="h-px w-8 bg-slate-200"></div>
                        <span>1. Informasi Dasar</span>
                        <div class="h-px w-8 bg-slate-200"></div>
                    </div>
                </div>

                {{-- Body Form --}}
                <div class="px-10 py-8">
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('kyc.step1.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Jenis Akun --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Jenis Akun <span class="text-red-500">*</span>
                                </label>
                                <select name="account_type"
                                    class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-800
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    @php
                                        $accountType = old('account_type', $kyc['account_type'] ?? '');
                                    @endphp
                                    <option value="" {{ $accountType === '' ? 'selected' : '' }}>Pilih jenis akun
                                    </option>
                                    <option value="individu" {{ $accountType === 'individu' ? 'selected' : '' }}>Individu
                                    </option>
                                    <option value="organisasi" {{ $accountType === 'organisasi' ? 'selected' : '' }}>
                                        Individu/Organisasi/Yayasan/Komunitas
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- Nama & Email --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Nama Individu/Organisasi/Yayasan/Komunitas <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="entity_name"
                                    value="{{ old('entity_name', $kyc['entity_name'] ?? '') }}"
                                    class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-800
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="contoh: BantuKita">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Email Individu/Organisasi/Yayasan/Komunitas <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="entity_email"
                                    value="{{ old('entity_email', $kyc['entity_email'] ?? (auth()->user()->email ?? '')) }}"
                                    class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-800
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="nama@email.com">
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Alamat Individu/Organisasi/Yayasan/Komunitas <span class="text-red-500">*</span>
                            </label>
                            <textarea name="entity_address" rows="3"
                                class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none"
                                placeholder="contoh: Jl. Nangka No. 123, Denpasar, Bali">{{ old('entity_address', $kyc['entity_address'] ?? '') }}</textarea>
                        </div>

                        
                        {{-- Tombol Lanjut --}}
                        <div class="pt-4 border-t border-slate-200 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-10 py-2.5 rounded-full 
                                       text-sm font-semibold text-white
                                       bg-gradient-to-r from-[#04A777] to-[#A4FF3C]
                                       hover:opacity-90 transition
                                       focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#04A777]">
                                Lanjut
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
