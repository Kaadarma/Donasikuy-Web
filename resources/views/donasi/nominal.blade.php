@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;

    $title     = data_get($program, 'title', 'Program Donasi');
    $slug      = data_get($program, 'slug');
    $category  = data_get($program, 'category', 'Program Kebaikan');
    $daysLeft  = data_get($program, 'days_left', null);

    $raised  = (int) data_get($program, 'raised', 0);
    $target  = (int) data_get($program, 'target', 0);
    $percent = $target > 0 ? min(100, round(($raised / $target) * 100)) : 0;

   
    $imageUrl = data_get($program, 'banner')
        ?: data_get($program, 'image')
        ?: 'https://via.placeholder.com/600x400?text=Program';
@endphp


<div class="min-h-screen bg-gradient-to-b from-slate-50 via-slate-100 to-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4">

        {{-- Breadcrumb --}}
        <div class="mb-6">
            <a href="{{ route('programs.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 hover:text-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke program lain
            </a>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.1fr,1.1fr] items-start">

            {{-- INFO PROGRAM --}}
            <div class="space-y-4">
                <div class="flex items-start gap-4">

                    <div class="w-32 h-32 rounded-2xl overflow-hidden shadow-md bg-slate-200 flex-shrink-0">
                        <img src="{{ $imageUrl }}"
                             alt="{{ $title }}"
                             class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1">
                        <p class="inline-flex items-center text-[11px] font-semibold uppercase tracking-[0.15em]
                                  text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full mb-2">
                            {{ $category }}
                        </p>

                        <h1 class="text-2xl md:text-3xl font-semibold text-slate-900 leading-snug">
                            {{ $title }}
                        </h1>

                        <div class="mt-3 flex items-center gap-2 text-sm text-slate-600">
                            <span>DonasiKuy</span>
                            <span class="w-1 h-1 rounded-full bg-slate-400"></span>

                            @if (is_null($daysLeft))
                                <span>Tanpa batas waktu</span>
                            @elseif ($daysLeft === 0)
                                <span>Berakhir hari ini</span>
                            @else
                                <span>Sisa {{ $daysLeft }} hari</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PROGRESS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-4">
                    <div class="flex justify-between text-sm">
                        <div>
                            <p class="text-slate-500">Terkumpul</p>
                            <p class="font-semibold text-emerald-600">
                                Rp {{ number_format($raised, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-500">Target</p>
                            <p class="font-semibold text-slate-900">
                                Rp {{ number_format($target, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-500 via-emerald-400 to-emerald-300"
                             style="width: {{ $percent }}%"></div>
                    </div>

                    <p class="text-xs text-slate-500">
                        Setiap rupiah yang kamu titipkan membantu mereka yang membutuhkan.
                    </p>
                </div>
            </div>

            {{-- CARD DONASI --}}
            <div>
                <div class="bg-white rounded-[28px] shadow-lg border border-slate-100 px-7 py-8 md:px-9 md:py-9">

                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-slate-900">Pilih nominal donasi</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            Besar kecilnya donasi tetap sangat berarti.
                        </p>
                    </div>

                    @php $presets = [30000, 50000, 75000, 100000]; @endphp
                    <div class="flex flex-wrap gap-3 mb-7">
                        @foreach ($presets as $amount)
                            <button type="button"
                                    data-amount="{{ $amount }}"
                                    class="preset-btn px-6 py-2.5 rounded-full border border-slate-200
                                           bg-slate-50 text-sm font-medium text-slate-700
                                           hover:bg-emerald-50 hover:border-emerald-400 hover:text-emerald-700 transition">
                                Rp {{ number_format($amount, 0, ',', '.') }}
                            </button>
                        @endforeach
                    </div>

                    <form id="donasiForm"
                          action="{{ route('donasi.dataDiri', $slug) }}"
                          method="GET"
                          class="space-y-6">

                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-1.5">
                                Nominal lainnya
                            </label>

                            <div class="flex rounded-xl border border-slate-300 bg-white overflow-hidden">
                                <span class="px-4 py-2.5 text-sm text-slate-500 border-r bg-slate-50">Rp</span>
                                <input id="nominal"
                                       type="text"
                                       name="nominal"
                                       value="{{ old('nominal', $nominal ?? 50000) }}"
                                       class="w-full px-3 py-2.5 text-sm outline-none">
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full py-3.5 rounded-2xl text-sm font-semibold
                                       bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400
                                       text-white shadow-lg hover:brightness-105 transition">
                            Selanjutnya
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const nominalInput = document.getElementById('nominal');

    function formatRupiah(val) {
        return val.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            nominalInput.value = formatRupiah(btn.dataset.amount);
        });
    });

    nominalInput.addEventListener('input', () => {
        nominalInput.value = formatRupiah(nominalInput.value);
    });

    document.getElementById('donasiForm').addEventListener('submit', () => {
        nominalInput.value = nominalInput.value.replace(/\./g, '');
    });
</script>
@endsection
