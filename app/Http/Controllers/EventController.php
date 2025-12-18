<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Donation;
use Midtrans\Snap;
use Midtrans\Config;


class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'approved')
            ->latest()
            ->paginate(9); // ← INI PENTING

        return view('events.index', compact('events'));
    }


    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function donate(Event $event)
    {
        return view('events.donasi', compact('event'));
    }

    public function processDonation(Request $request, Event $event)
    {
        $data = $request->validate([
            'nominal' => 'required|integer|min:10000',
            'nama' => 'required|string',
            'telepon' => 'required',
            'is_anonymous' => 'nullable|boolean',
            'pesan' => 'nullable|string',
        ]);

        // 1. Simpan donasi ke DB (PENDING)
        $donation = Donation::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id, // ⬅️ PENTING
            'amount' => $data['nominal'],
            'donor_name' => $data['nama'],
            'is_anonymous' => $data['is_anonymous'] ?? 0,
            'message' => $data['pesan'] ?? null,
            'status' => 'pending',
        ]);

        // 2. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 3. Buat payload Midtrans
        $payload = [
            'transaction_details' => [
                'order_id' => 'EVENT-' . $donation->id . '-' . time(),
                'gross_amount' => $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'phone' => $data['telepon'],
            ],
        ];

        $snapToken = Snap::getSnapToken($payload);

        // 4. Kirim ke halaman bayar
        return view('events.bayar', [
            'event' => $event,
            'donation' => $donation,
            'snapToken' => $snapToken,
        ]);
    }
}
