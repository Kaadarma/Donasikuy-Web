@php
    use Carbon\Carbon;

    $p = $row->program;

    // ambil aman untuk object/array
    $title    = data_get($p, 'title', '-');
    $slug     = data_get($p, 'slug');
    $id       = data_get($p, 'id');
    $category = data_get($p, 'category');
    $image    = data_get($p, 'image');
    $target   = (int) data_get($p, 'target', 0);
    $deadline = data_get($p, 'deadline');

    $authorName = data_get($p, 'author_name')
        ?? data_get($p, 'user.name')
        ?? 'Donasikuy';

    

    // image url (support: storage path / full url / null)
    if ($image && str_starts_with($image, 'http')) {
        $imageUrl = $image;
    } elseif ($image) {
        $imageUrl = asset('storage/' . ltrim($image, '/'));
    } else {
        $imageUrl = asset('images/placeholder-campaign.jpg');
    }

    $categoryLabel = $category
        ? ucfirst(str_replace('-', ' ', $category))
        : 'Program';

    // raised: DB program -> hitung dari donations DB
    // seed program -> pakai field raised dari program seed (kalau ada)
    if ($id) {
        $raised = (int) \App\Models\Donation::query()
            ->where('program_id', $id)
            ->whereIn('status', ['success','settlement','paid'])
            ->sum('amount');
    } else {
        $raised = (int) data_get($p, 'raised', 0);
    }

    if (($target <= 0 || empty($deadline)) && !empty($slug)) {
        $seed = app(\App\Http\Controllers\ProgramController::class)->findProgram($slug);

        if ($seed) {
            $target   = (int) ($seed['target'] ?? $target);
            $deadline = $seed['deadline'] ?? $deadline;

            // kalau raised seed mau ikut juga (optional)
            $raised = (int) ($seed['raised'] ?? $raised);
        }
    }

    $progress = $target > 0
        ? (int) min(100, round(($raised / max(1, $target)) * 100))
        : null;

    $deadlineText = $deadline
        ? Carbon::parse($deadline)->translatedFormat('d M Y')
        : '—';

    $lastDonated = $row->last_donated_at
        ? Carbon::parse($row->last_donated_at)->translatedFormat('d M Y')
        : '—';

    $publicUrl = $slug ? route('programs.show', $slug) : '#';
@endphp

<a href="{{ $publicUrl }}"
   class="block rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition">
    {{-- IMAGE --}}
    <div class="relative h-44 bg-slate-100">
        <img src="{{ $imageUrl }}" class="h-full w-full object-cover" alt="">

        <div class="absolute inset-x-0 top-0 p-4 flex items-center justify-between gap-3">
            <div class="text-sm font-semibold text-emerald-700 bg-white/90 backdrop-blur px-3 py-1.5 rounded-full">
                {{ $categoryLabel }}
            </div>

            @if(!is_null($progress))
                <div class="text-xs font-semibold bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-full">
                    {{ $progress }}%
                </div>
            @endif
        </div>
    </div>

    {{-- BODY --}}
    <div class="p-5">
        <h3 class="text-base md:text-lg font-bold text-slate-900 leading-snug line-clamp-2">
            {{ $title }}
        </h3>

        <p class="mt-1 text-xs text-slate-500">
            oleh <span class="font-semibold text-slate-700">{{ $authorName }}</span>
        </p>

        {{-- info donasi user --}}
        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-slate-500">Total Donasi Kamu</div>
                <div class="mt-1 font-bold text-slate-900">
                    Rp {{ number_format((int)$row->user_total_amount, 0, ',', '.') }}
                </div>
            </div>
            <div class="text-right">
                <div class="text-slate-500">Terakhir Donasi</div>
                <div class="mt-1 font-bold text-slate-900">
                    {{ $lastDonated }}
                </div>
            </div>
        </div>

        {{-- progress keseluruhan campaign --}}
        <div class="mt-4">
            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                <span>
                    Rp {{ number_format($raised, 0, ',', '.') }}
                    <span class="text-slate-400">dari</span>
                    {{ $target > 0 ? 'Rp '.number_format($target, 0, ',', '.') : 'Unlimited' }}
                </span>

                @if(!is_null($progress))
                    <span class="font-semibold text-emerald-700">{{ $progress }}%</span>
                @endif
            </div>

            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full bg-emerald-600" style="width: {{ $progress ?? 25 }}%"></div>
            </div>

            <div class="mt-2 text-[11px] text-slate-500">
                Deadline: <span class="font-medium text-slate-700">{{ $deadlineText }}</span>
            </div>
        </div>
    </div>
</a>
