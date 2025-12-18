@extends('layouts.app')

@section('title', 'Donasi Event')

@section('content')
<div class="max-w-md mx-auto py-20 px-4">

    <h1 class="text-xl font-semibold mb-4">
        Donasi untuk {{ $event->title }}
    </h1>

    <form method="POST" action="{{ route('events.donate.process', ['event' => $event->slug]) }}">
        @csrf

        <input type="number" name="amount"
               class="w-full border rounded-lg px-4 py-3 mb-4"
               placeholder="Nominal donasi" min='10000' required>

        <button type='submit 'class="w-full bg-emerald-600 text-white py-3 rounded-lg">
            Donasi Sekarang
        </button>
    </form>

</div>
@endsection
