@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-slate-50 via-slate-100 to-slate-50 py-10">
        <div class="max-w-6xl mx-auto px-4">

            {{-- Breadcrumb / kembali --}}
            <div class="mb-6">
                <a href="{{ route('programs.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 hover:text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke program lain
                </a>
            </div>

            <div class="grid gap-8 lg:grid-cols-[1.1fr,1.1fr] items-start">

                {{-- Info program --}}
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-32 h-32 rounded-2xl overflow-hidden shadow-md bg-slate-200 flex-shrink-0">
                            <img src="{{ $program->image_url ?? 'https://via.placeholder.com/300x300' }}"
                                alt="{{ $program->title ?? 'Program Donasi' }}" class="w-full h-full object-cover">
                        </div>

                        <div class="flex-1">
                            <p
                                class="inline-flex items-center text-[11px] font-semibold uppercase tracking-[0.15em] text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full mb-2">
                                Program Kebaikan
                            </p>

                            <h1 class="text-2xl md:text-3xl font-semibold text-slate-900 leading-snug">
                                {{ $program->title ?? 'Update Bantuan Gempa Terkini' }}
                            </h1>

                            <div class="mt-3 flex items-center gap-2 text-sm text-slate-600">
                                <span>{{ $program->organizer ?? 'odesaindonesia' }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                                <span>{{ $program->date ?? 'Hari ini' }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-sky-500" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.01 13.09-3.28-3.28a.75.75 0 0 1 1.06-1.06l2.22 2.22 4.47-4.47a.75.75 0 1 1 1.06 1.06l-5 5a.75.75 0 0 1-1.06 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Progress / motivation --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-4">
                        <div class="flex justify-between text-sm">
                            <div>
                                <p class="text-slate-500">Terkumpul</p>
                                <p class="font-semibold text-emerald-600">
                                    {{ $program->collected ?? 'Rp 201.323.211' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-slate-500">Target</p>
                                <p class="font-semibold text-slate-900">
                                    {{ $program->target ?? 'Rp 500.000.000' }}
                                </p>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-emerald-400 to-emerald-300 w-[45%]">
                            </div>
                        </div>

                        <p class="text-xs text-slate-500">
                            Setiap rupiah yang kamu titipkan membantu keluarga yang sedang bangkit dari bencana. 💚
                        </p>
                    </div>

                    @if (!empty($program->body))
                        <div class="text-sm text-slate-600 bg-white/60 rounded-2xl p-4 border border-slate-100">
                            {{ is_array($program->body) ? $program->body[0] : $program->body }}
                        </div>
                    @endif
                </div>

                {{-- Card donasi --}}
                <div>
                    <div class="bg-white rounded-[28px] shadow-lg border border-slate-100 px-7 py-8 md:px-9 md:py-9">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-slate-900">Pilih nominal donasi</h2>
                            <p class="mt-1 text-sm text-slate-600">
                                Kamu bebas memilih nominal yang paling nyaman, besar kecilnya tetap sangat berarti.
                            </p>
                        </div>

                        {{-- Tombol preset --}}
                        @php
                            $presets = [30000, 50000, 75000, 100000];
                        @endphp
                        <div class="flex flex-wrap gap-3 mb-7">
                            @foreach ($presets as $amount)
                                <button type="button" data-amount="{{ $amount }}"
                                    class="preset-btn px-6 py-2.5 rounded-full border border-slate-200
                                           bg-slate-50 text-sm font-medium text-slate-700
                                           hover:bg-emerald-50 hover:border-emerald-400 hover:text-emerald-700
                                           transition shadow-[0_1px_4px_rgba(15,23,42,0.06)]">
                                    Rp{{ number_format($amount, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Form nominal lainnya --}}
                        <form action="{{ route('datadiri') }}" method="GET" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-1.5">
                                    Nominal lainnya
                                </label>
                                <div class="flex rounded-xl border border-slate-300 bg-white overflow-hidden shadow-sm">
                                    <span class="px-4 py-2.5 text-sm text-slate-500 border-r border-slate-200 bg-slate-50">
                                        Rp
                                    </span>
                                    <input id="nominal" type="number" name="nominal"
                                        value="{{ old('nominal', $nominal ?? 50000) }}" min="10000" step="1000"
                                        class="w-full px-3 py-2.5 text-sm outline-none text-slate-800 placeholder:text-slate-400">
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="w-full py-3.5 rounded-2xl text-sm font-semibold
                       bg-gradient-to-r from-emerald-600 via-green-500 to-lime-400
                       text-white shadow-lg shadow-emerald-500/30
                       hover:brightness-105 active:scale-[0.99] transition">
                                    Selanjutnya
                                </button>
                            </div>
                        </form>

                        {{-- Catatan kecil --}}
                        <p class="mt-4 text-[11px] text-slate-400 text-center">
                            Dengan menekan “Selanjutnya”, kamu setuju bahwa donasi ini adalah amanah yang kamu titipkan
                            untuk disalurkan kepada penerima manfaat yang berhak.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script kecil untuk sinkron tombol nominal preset ke input --}}
    <script>
        document.querySelectorAll('.preset-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const amount = this.dataset.amount;
                const input = document.getElementById('nominal');

                // set value
                input.value = amount;

                // highlight tombol yang aktif
                document.querySelectorAll('.preset-btn').forEach(b => {
                    b.classList.remove('ring-2', 'ring-emerald-400', 'bg-emerald-50',
                        'border-emerald-400', 'text-emerald-700');
                });
                this.classList.add('ring-2', 'ring-emerald-400', 'bg-emerald-50', 'border-emerald-400',
                    'text-emerald-700');
            });
        });
    </script>
@endsection
