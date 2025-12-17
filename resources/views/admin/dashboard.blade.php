@extends('layouts.admin-dashboard')

@section('title', 'Dashboard Admin')

@section('content')

{{-- SUMMARY CARDS --}}
<div class="grid gap-4 md:grid-cols-2 mb-6">

    <div class="rounded-xl bg-white border p-5 flex items-center gap-4">
        <div class="h-12 w-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
            <i class="bi bi-person-exclamation text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500">KYC Pending</p>
            <p class="text-xl font-semibold">{{ $kycPending }}</p>
        </div>
    </div>

    <div class="rounded-xl bg-white border p-5 flex items-center gap-4">
        <div class="h-12 w-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
            <i class="bi bi-collection-play text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500">Program Pending</p>
            <p class="text-xl font-semibold">{{ $programPending }}</p>
        </div>
    </div>

</div>

<div class="rounded-lg border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
    Silakan lakukan verifikasi KYC dan Program sebelum dipublikasikan.
</div>

@endsection
