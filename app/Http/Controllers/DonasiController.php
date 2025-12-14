<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Program;

class DonasiController extends Controller
{
    private function getProgram(string $slug): array
    {
        // 1) coba ambil dari seed() via ProgramController (yang kamu udah punya)
        $pc = app(\App\Http\Controllers\ProgramController::class);

        $program = null;
        if (method_exists($pc, 'findProgram')) {
            $program = $pc->findProgram($slug);
        }

        // 2) kalau tidak ketemu (biasanya program Dana Punia dari DB), ambil dari DATABASE
        if (! $program) {
            $p = Program::where('slug', $slug)->firstOrFail();

            $program = [
                'id' => $p->id,
                'slug' => $p->slug,
                'title' => $p->title,
                'category' => $p->category ?? 'lainnya',
                'image' => $p->image,
                'banner' => $p->banner ?? $p->image,
                'target' => (int) ($p->target ?? 0),
                'raised' => (int) ($p->raised ?? 0),
                'description' => $p->description,
                'short_description' => $p->short_description,
            ];
        }

        // 3) terakhir: kalau tetap kosong ya 404
        abort_unless($program, 404);

        return $program;
    }

    public function nominal(Request $request, string $slug)
    {
        $program = $this->getProgram($slug);
        $nominal = $request->input('nominal', 50000);

        return view('donasi.nominal', compact('program', 'nominal'));
    }

    public function dataDiri(Request $request, string $slug)
    {
        $program = $this->getProgram($slug);
        $nominal = $request->input('nominal', 50000);

        return view('donasi.data-diri', compact('program', 'nominal'));
    }

    public function proses(Request $request, string $slug)
    {
        $program = $this->getProgram($slug);

        $data = $request->validate([
            'nominal' => ['required', 'integer', 'min:10000'],
            'payment_method' => ['required', 'string'],
            'voucher_code' => ['nullable', 'string'],
            'nama' => ['required', 'string', 'max:100'],
            'telepon' => ['required', 'string', 'min:8'],
            'email' => ['nullable', 'email'],
            'is_anonymous' => ['required', 'in:0,1'],
            'pesan' => ['nullable', 'string', 'max:500'],
        ]);

        $isAnonymous = ((int) $data['is_anonymous']) === 1;
        $displayName = $isAnonymous ? 'Siapa Ya?' : $data['nama'];

        $orderId = 'DON-'.($program['id'] ?? 'X').'-'.Str::random(8);

        Donation::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'program_id' => $program['id'],
            'donor_name' => $data['nama'],
            'is_anonymous' => $isAnonymous ? 1 : 0,
            'amount' => (int) $data['nominal'],
            'message' => $data['pesan'] ?? null,
            'status' => 'success',
        ]);

        $this->setMidtransConfig();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $data['nominal'],
            ],
            'customer_details' => [
                'first_name' => $displayName,
                'email' => $data['email'] ?? null,
                'phone' => $data['telepon'],
            ],
            'item_details' => [
                [
                    'id' => $program['id'],
                    'price' => $data['nominal'],
                    'quantity' => 1,
                    'name' => substr($program['title'], 0, 50),
                ],
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('donasi.bayar', compact(
            'program',
            'data',
            'orderId',
            'snapToken',
            'displayName'
        ));
    }

    /**
     * ❗ FIX 2: method ini sekarang AMAN
     * Mengambil data dari session
     */
    public function pembayaran()
    {
        $program = session('donasi_program');
        $data = session('donasi_data');
        $snapToken = session('donasi_snap_token');
        $orderId = session('donasi_order_id');

        return view('donasi.pembayaran', compact(
            'program',
            'data',
            'snapToken',
            'orderId'
        ));
    }

    public function sukses(Request $request)
    {
        $slug = $request->query('slug');
        $nominal = (int) $request->query('nominal', 0);

        if ($slug && $nominal > 0) {
            $overrides = session('donasi_overrides', []);
            $overrides[$slug] = ($overrides[$slug] ?? 0) + $nominal;
            session(['donasi_overrides' => $overrides]);
        }

        return view('donasi.sukses', compact('slug', 'nominal'));
    }

    protected function setMidtransConfig(): void
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }
}
