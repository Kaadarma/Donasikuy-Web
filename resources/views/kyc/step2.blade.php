@extends('layouts.app')

@section('authpage', true)

@section('title', 'Verifikasi Akun - Bagian 2')

@section('content')
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-5xl mx-auto">

            {{-- Progress bar --}}
            <div class="mb-3 text-xs font-medium text-slate-600">
                50%
            </div>

            <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden mb-6">
                <div class="h-full w-[50%] bg-gradient-to-r from-[#04A777] to-[#A4FF3C]"></div>
            </div>
            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
                <div class="px-10 pt-6 pb-4 text-center border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">
                        Bagian 2
                    </h2>
                    <div class="mt-2 inline-flex items-center gap-3 text-[11px] uppercase tracking-[0.25em] text-slate-500">
                        <div class="h-px w-8 bg-slate-200"></div>
                        <span>2. Informasi Tambahan</span>
                        <div class="h-px w-8 bg-slate-200"></div>
                    </div>
                </div>

                <div class="px-10 py-8">
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- SEMENTARA: isi sederhana dulu, nanti bisa kamu ganti sesuai kebutuhan --}}
                    <form action="{{ route('kyc.step2.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Nama Penanggung Jawab (opsional)
                            </label>
                            <input type="text" name="pic_name" value="{{ old('pic_role', $kyc['pic_role'] ?? '') }}"
                                class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm
                                      text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Jabatan / Peran di Organisasi (opsional)
                            </label>
                            <input type="text" name="pic_role" value="{{ old('pic_role', $kyc['pic_role'] ?? '') }}"
                                class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm
                                      text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Tombol --}}
                        <div class="pt-4 border-t border-slate-200 space-y-3">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-full
           text-sm font-semibold text-white
           bg-gradient-to-r from-[#04A777] to-[#A4FF3C]
           hover:opacity-90 transition
           focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#04A777]">
                                Lanjut
                            </button>

                            <a href="{{ route('kyc.step1') }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg
                                  border border-slate-300 bg-white text-sm font-medium text-slate-700
                                  hover:bg-slate-50">
                                Balik Bagian 1
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
