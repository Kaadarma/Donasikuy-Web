@extends('layouts.app')

@section('authpage', true)

@section('title', 'Verifikasi Akun - Bagian 4')

@section('content')
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-5xl mx-auto">

            {{-- Progress bar --}}
            <div class="mb-3 text-xs font-medium text-slate-600">
                100%
            </div>
            <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden mb-6">
                <div class="h-full w-[100%] bg-gradient-to-r from-[#04A777] to-[#A4FF3C]"></div>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
                <div class="px-10 pt-6 pb-4 text-center border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">
                        Bagian 4
                    </h2>

                    <div class="mt-2 inline-flex items-center gap-3 text-[11px] uppercase tracking-[0.25em] text-slate-500">
                        <div class="h-px w-8 bg-slate-200"></div>
                        <span>4. Informasi Pencairan Dana</span>
                        <div class="h-px w-8 bg-slate-200"></div>
                    </div>
                </div>

                <div class="px-10 py-8">
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('kyc.step4.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        {{-- Bank --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Bank <span class="text-red-500">*</span>
                            </label>
                            <select name="bank_name"
                                class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm
                                       text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Pilih Bank</option>
                                <option value="BRI" {{ ($kyc['bank_name'] ?? '') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="BCA" {{ ($kyc['bank_name'] ?? '') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="BNI" {{ ($kyc['bank_name'] ?? '') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                <option value="MANDIRI" {{ ($kyc['bank_name'] ?? '') == 'MANDIRI' ? 'selected' : '' }}>MANDIRI</option>
                                {{-- tambahkan lainnya kalau perlu --}}
                            </select>
                        </div>

                        {{-- No Rekening & Nama Rekening --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Nomor Rekening <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="account_number" value="{{ old('account_number') }}"
                                    class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm
                                          text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Nama Rekening <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="account_name" value="{{ old('account_name') }}"
                                    class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm
                                          text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        {{-- Scan Buku Tabungan --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Scan Foto Buku Tabungan <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="book_photo"
                                class="block w-full text-sm text-slate-700 file:mr-4 file:py-2.5 file:px-4
                                      file:rounded-lg file:border-0 file:text-sm file:font-medium
                                      file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            <p class="mt-1 text-xs text-slate-500">
                                Maks. 1MB (.jpg/.jpeg/.png)
                            </p>
                        </div>

                        {{-- Tombol --}}
                        <div class="pt-4 border-t border-slate-200 space-y-3">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-full
           text-sm font-semibold text-white
           bg-gradient-to-r from-[#04A777] to-[#A4FF3C]
           hover:opacity-90 transition
           focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#04A777]">
                                Selesai
                            </button>


                            <a href="{{ route('kyc.step3') }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg
                                  border border-slate-300 bg-white text-sm font-medium text-slate-700
                                  hover:bg-slate-50">
                                Balik Bagian 3
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
