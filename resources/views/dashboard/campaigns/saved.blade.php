@extends('layouts.dashboard')
@section('title', 'Draft Tersimpan')
@section('page_title', 'Campaign')

@section('content')
<div class="max-w-4xl mx-auto px-4 md:px-8 py-10">

    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl">
                ✅
            </div>

            <div class="flex-1">
                <h1 class="text-xl md:text-2xl font-bold text-slate-900">
                    Draft galang dana kamu sudah tersimpan
                </h1>

                <p class="mt-2 text-slate-600">
                    Campaign: <span class="font-semibold text-slate-900">{{ $program->title }}</span>
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Kamu bisa lanjut edit dulu, atau kalau sudah yakin langsung kirim ke admin untuk direview.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('dashboard.campaigns.index') }}"
                       class="w-full sm:w-auto inline-flex justify-center rounded-full border border-slate-200 bg-white px-6 py-3
                              text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Simpan sebagai Draft
                    </a>

                    <form method="POST" action="{{ route('dashboard.campaigns.submit', $program->id) }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-full bg-emerald-600 px-6 py-3
                                   text-sm font-semibold text-white hover:bg-emerald-700 active:bg-emerald-800
                                   shadow-md shadow-emerald-600/30 transition">
                            Kirim ke Admin untuk Review
                        </button>
                    </form>
                </div>

                <p class="mt-4 text-xs text-slate-500">
                    Setelah dikirim, status berubah menjadi <span class="font-semibold">Menunggu Review</span> dan campaign tidak bisa diedit sampai ada keputusan admin.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
