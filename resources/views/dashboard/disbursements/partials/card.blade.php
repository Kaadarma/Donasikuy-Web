@props([
    'overrideCardUrl' => null,
    'overridePrimaryCta' => null,
])

@php
    use Carbon\Carbon;

    // ======================
    // STATUS
    // ======================
    $status = $p->status ?? 'draft';

    $isExpiredByDeadline =
        in_array($status, ['approved', 'running'])
        && !empty($p->deadline)
        && Carbon::parse($p->deadline)->startOfDay()->lt(now()->startOfDay());

    $statusForBadge = $isExpiredByDeadline ? 'completed' : $status;

    $isDraft     = $statusForBadge === 'draft';
    $isPending   = $statusForBadge === 'pending';
    $isRejected  = $statusForBadge === 'rejected';
    $isRunning   = in_array($status, ['approved', 'running']) && !$isExpiredByDeadline;
    $isCompleted = $isExpiredByDeadline || in_array($status, ['completed', 'expired']);

    // ======================
    // BADGE
    // ======================
    $statusBadge = match ($statusForBadge) {
        'draft'     => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>'Draft'],
        'pending'   => ['bg'=>'bg-amber-100','text'=>'text-amber-700','label'=>'Menunggu Review'],
        'rejected'  => ['bg'=>'bg-red-100','text'=>'text-red-700','label'=>'Ditolak'],
        'approved'  => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Disetujui'],
        'running'   => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Sedang Berjalan'],
        'completed' => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>'Selesai'],
        default     => ['bg'=>'bg-slate-100','text'=>'text-slate-700','label'=>ucfirst($statusForBadge)],
    };

    // ======================
    // DATA
    // ======================
    $categoryLabel = $p->category
        ? ucfirst(str_replace('-', ' ', $p->category))
        : 'Tanpa Kategori';

    $imageUrl = $p->image
        ? asset('storage/' . $p->image)
        : asset('images/placeholder-campaign.jpg');

    $displayOwner = $kyc->account_type === 'organization'
        ? ($kyc->entity_name ?? '—')
        : ($kyc->full_name ?? '—');

    $target   = (int) ($p->target ?? 0);
    $raised   = (int) ($p->raised_sum ?? $p->raised ?? 0);
    $progress = $target > 0 ? min(100, round(($raised / $target) * 100)) : 0;

    $deadlineText = $p->deadline
        ? Carbon::parse($p->deadline)->translatedFormat('d M Y')
        : null;

    // ======================
    // ROUTE
    // ======================
    $cardUrl = match (true) {
        $isDraft     => route('dashboard.campaigns.edit', $p->id),
        $isRejected  => route('dashboard.campaigns.show', $p->id),
        $isRunning   => route('dashboard.campaigns.manage', $p->id),
        $isCompleted => route('dashboard.campaigns.history.show', $p->id),
        default      => '#',
    };

    if ($overrideCardUrl) {
        $cardUrl = $overrideCardUrl;
    }
@endphp

<div class="rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition">
    <a href="{{ $cardUrl }}" class="{{ $cardUrl === '#' ? 'pointer-events-none' : 'block' }}">
        <div class="relative h-44 bg-slate-100">
            <img src="{{ $imageUrl }}" class="w-full h-full object-cover">

            <div class="absolute top-0 inset-x-0 p-4 flex justify-between">
                <span class="text-xs font-semibold bg-white/90 px-3 py-1.5 rounded-full">
                    {{ $categoryLabel }}
                </span>

                <span class="text-xs font-semibold {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} px-3 py-1.5 rounded-full">
                    {{ $statusBadge['label'] }}
                </span>
            </div>
        </div>
    </a>

    <div class="p-5">
        <h3 class="text-lg font-bold text-slate-900">{{ $p->title }}</h3>
        <p class="text-xs text-slate-500 mt-1">
            oleh <span class="font-semibold">{{ $displayOwner }}</span>
        </p>

        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-slate-500">Dana Terkumpul</div>
                <div class="font-bold">Rp {{ number_format($raised, 0, ',', '.') }}</div>
            </div>
            <div class="text-right">
                <div class="text-slate-500">Deadline</div>
                <div class="font-bold">{{ $deadlineText ?? '—' }}</div>
            </div>
        </div>

        @if($target > 0)
            <div class="mt-4">
                <div class="flex justify-between text-xs mb-1">
                    <span>{{ $progress }}%</span>
                    <span>Rp {{ number_format($target, 0, ',', '.') }}</span>
                </div>
                <div class="h-2 bg-slate-100 rounded-full">
                    <div class="h-full bg-emerald-600 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        @endif

        {{-- CTA --}}
        @if($overridePrimaryCta)
            <a href="{{ $overridePrimaryCta['url'] }}"
               class="mt-5 block text-center rounded-full bg-emerald-600 text-white py-2.5 text-sm font-semibold hover:bg-emerald-700">
                {{ $overridePrimaryCta['label'] }}
            </a>
        @endif
    </div>
</div>
