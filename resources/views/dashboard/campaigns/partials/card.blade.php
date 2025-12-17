@php
    $status = $p->status ?? 'draft';

    $statusBadge = match ($status) {
        'draft'     => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'label' => 'Draft'],
        'pending'   => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'label' => 'Menunggu Review'],
        'rejected'  => ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'label' => 'Ditolak'],
        'approved'  => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Disetujui'],
        'running'   => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Sedang Berjalan'],
        'completed' => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'label' => 'Selesai'],
        'expired'   => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'label' => 'Berakhir'],
        default     => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'label' => ucfirst($status)],
    };

    $categoryLabel = $p->category ? ucfirst(str_replace('-', ' ', $p->category)) : 'Tanpa Kategori';

    $target   = (int) ($p->target ?? 0);
    $raised   = (int) ($p->raised ?? 0); // accessor Program::getRaisedAttribute()
    $progress = ($target > 0) ? (int) min(100, round(($raised / max(1, $target)) * 100)) : 0;

    $deadlineText = $p->deadline ? \Carbon\Carbon::parse($p->deadline)->translatedFormat('d M Y') : null;

    $imageUrl = $p->image
        ? asset('storage/' . $p->image)
        : asset('images/placeholder-campaign.jpg');

    // nama pemilik campaign (dari KYC kalau ada)
    $displayOwner = null;
    if (isset($kyc) && $kyc) {
        $displayOwner = $kyc->account_type === 'organization'
            ? ($kyc->entity_name ?? null)
            : ($kyc->full_name ?? null);
    }
    $displayOwner = $displayOwner ?: (auth()->user()->name ?? '—');

    // pending/review: card klik -> detail, TANPA tombol
    $isReviewOnly = ($status === 'pending');

    // route detail (sementara boleh kamu ganti nanti)
    $detailUrl = '#';
@endphp

<div class="rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition">
    {{-- Image --}}
    <a href="{{ $detailUrl }}" class="{{ $isReviewOnly ? 'block' : 'pointer-events-none' }}">
        <div class="relative h-44 sm:h-48 bg-slate-100">
            <img src="{{ $imageUrl }}" alt="Campaign Image" class="h-full w-full object-cover">

            {{-- overlay --}}
            <div class="absolute inset-x-0 top-0 p-4 flex items-center justify-between gap-3">
                <div class="text-sm font-semibold text-emerald-700 bg-white/90 backdrop-blur px-3 py-1.5 rounded-full">
                    {{ $categoryLabel }}
                </div>

                <div class="text-xs font-semibold {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} px-3 py-1.5 rounded-full">
                    {{ $statusBadge['label'] }}
                </div>
            </div>
        </div>
    </a>

    {{-- Body --}}
    <div class="p-5">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <h3 class="text-lg font-bold text-slate-900 leading-snug">
                    {{ $p->title }}
                </h3>
                <p class="mt-1 text-xs text-slate-500">
                    oleh <span class="font-semibold text-slate-700">{{ $displayOwner }}</span>
                </p>
            </div>
        </div>

        @if(!empty($p->short_description))
            <p class="mt-3 text-sm text-slate-600">
                {{ $p->short_description }}
            </p>
        @endif

        {{-- Stats --}}
        <div class="mt-5 grid grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-slate-500">Dana Terkumpul</div>
                <div class="mt-1 font-bold text-slate-900">Rp {{ number_format($raised, 0, ',', '.') }}</div>
            </div>
            <div class="text-right">
                <div class="text-slate-500">{{ $deadlineText ? 'Deadline' : 'Tanpa batas waktu' }}</div>
                <div class="mt-1 font-bold text-slate-900">{{ $deadlineText ?? '—' }}</div>
            </div>
        </div>

        {{-- Progress --}}
        <div class="mt-4">
            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                <span>
                    Rp {{ number_format($raised, 0, ',', '.') }}
                    dari
                    {{ $target > 0 ? 'Rp ' . number_format($target, 0, ',', '.') : 'Unlimited' }}
                </span>
                @if($target > 0)
                    <span class="font-semibold text-emerald-700">{{ $progress }}%</span>
                @endif
            </div>

            @php
                // unlimited jangan full 100% biar ga misleading
                $barWidth = $target > 0 ? $progress : 25;
            @endphp

            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full bg-emerald-600" style="width: {{ $barWidth }}%"></div>
            </div>
        </div>

        {{-- Actions --}}
        @php
            $isPending  = ($status === 'pending');
            $isRejected = ($status === 'rejected');
            $isDraft    = ($status === 'draft');

            $canEdit   = in_array($status, ['draft','rejected']);
            $canSubmit = in_array($status, ['draft','rejected']); // draft/rejected boleh ajukan ulang
        @endphp

        {{-- Actions --}}
        @if($isPending)
            {{-- pending: NO BUTTON (card klik aja) --}}
        @else
            <div class="mt-5 flex gap-3">

                {{-- tombol kiri --}}
                @if($isRejected)
                    <a href="{{ $detailUrl }}"
                    class="flex-1 inline-flex justify-center rounded-full bg-amber-50 px-5 py-2.5 text-sm font-semibold
                            text-amber-700 border border-amber-200 hover:bg-amber-100 transition">
                        Lihat Detail Review
                    </a>
                @else
                    <a href="{{ $editUrl ?? '#' }}"
                    class="flex-1 inline-flex justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5
                            text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Edit
                    </a>
                @endif

                {{-- tombol kanan --}}
                @if($canSubmit)
                    <form method="POST" action="{{ $submitUrl ?? '#' }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-full bg-emerald-600 px-5 py-2.5
                                text-sm font-semibold text-white hover:bg-emerald-700 active:bg-emerald-800 transition">
                            Ajukan
                        </button>
                    </form>
                @else
                    <a href="{{ route('programs.show', $p->slug) }}"
                    class="flex-1 inline-flex justify-center rounded-full bg-emerald-600 px-5 py-2.5
                            text-sm font-semibold text-white hover:bg-emerald-700 active:bg-emerald-800 transition">
                        Lihat Halaman
                    </a>
                @endif

            </div>
        @endif
    </div>
</div>
