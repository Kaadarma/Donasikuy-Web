<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Midtrans\Config;
use Midtrans\Snap;


class DonasiController extends Controller
{
    private function resolveImage(?string $path): string
    {
        $fallback = 'https://via.placeholder.com/1200x675?text=Program';

        // file dari storage public: programs/xxx.jpg
        if (Str::startsWith($path, 'programs/')) {
            return asset('storage/' . $path);
        }

        // kalau sudah keburu tersimpan "storage/programs/.."
        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
    }

        if (! $path) {
            return $fallback;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'public/images/')) {
            return asset(Str::after($path, 'public/'));
        }

        if (Str::startsWith($path, 'images/')) {
            return asset($path);
        }

        if (file_exists(public_path('images/' . $path))) {
            return asset('images/' . $path);
        }

        if (Str::startsWith($path, ['uploads/', 'img/'])) {
            return asset($path);
        }

        return $fallback;
    }

    private function isSeedProgram(array $program): bool
    {
    return (bool) ($program['is_seeder'] ?? false);
    }


    private function getProgram(string $slug): array
    {
        $pc = app(\App\Http\Controllers\ProgramController::class);

        $program = null;
        if (method_exists($pc, 'findProgram')) {
            $program = $pc->findProgram($slug); // ini sudah ada days_left & status kalau dari seed
        }

        if (! $program) {
            $p = Program::where('slug', $slug)->firstOrFail();

            // hitung raised konsisten dari donations
            $raisedDb = (int) Donation::where('program_id', $p->id)
                ->whereIn('status', ['success','settlement','capture','paid'])
                ->sum('amount');

            // hitung days_left + status dari deadline
            $deadline = $p->deadline; // date/null
            $daysLeft = null;
            $statusLabel = 'Tanpa Batas Waktu';

            if (!empty($deadline)) {
                $today = now()->startOfDay();
                $end   = \Carbon\Carbon::parse($deadline)->startOfDay();
                $diff  = $today->diffInDays($end, false);

                if ($diff > 0) { $daysLeft = $diff; $statusLabel = 'Sedang Berjalan'; }
                elseif ($diff === 0) { $daysLeft = 0; $statusLabel = 'Berakhir Hari Ini'; }
                else { $daysLeft = 0; $statusLabel = 'Selesai'; }
            }

            $program = [
                'id' => $p->id,
                'slug' => $p->slug,
                'title' => $p->title,
                'category' => $p->category ?? 'lainnya',
                'image' => $p->image,
                'banner' => $p->banner ?? $p->image,
                'target' => (int) ($p->target ?? 0),
                'raised' => $raisedDb,

                // ✅ ini yang kamu butuhin di nominal view
                'deadline'  => $deadline,
                'days_left' => $daysLeft,
                'status'    => $statusLabel,

                'description' => $p->description,
                'short_description' => $p->short_description,
            ];
        }

        $program['image']  = $this->resolveImage($program['image'] ?? null);
        $program['banner'] = $this->resolveImage($program['banner'] ?? ($program['image'] ?? null));

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

        $orderId = 'DON-' . ($program['id'] ?? 'X') . '-' . Str::random(8);

        $isSeed = $this->isSeedProgram($program);

        if ($isSeed) {
            // 1) update progress seed (yang kamu udah punya)
            $overrides = session('donasi_overrides', []);
            $overrides[$program['slug']] = ($overrides[$program['slug']] ?? 0) + (int) $data['nominal'];
            session(['donasi_overrides' => $overrides]);

            // 2) simpen riwayat buat profile
            $seedDonations = session('seed_donations', []);
            $seedDonations[] = [
                'user_id'     => auth()->id(), // penting biar bisa difilter per user
                'order_id'    => $orderId,
                'program_slug' => $program['slug'],
                'amount' => (int) $data['nominal'],
                'status' => 'success',
                'created_at' => now()->toDateTimeString(),
            ];
            session(['seed_donations' => $seedDonations]);

        } else {
            Donation::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'program_id' => $program['id'],
                'donor_name' => $data['nama'],
                'is_anonymous' => $isAnonymous ? 1 : 0,
                'amount' => (int) $data['nominal'],
                'message' => $data['pesan'] ?? null,
                'status' => 'success',
                // kalau kamu punya kolom order_id, masukin juga biar sukses page enak:
                // 'order_id' => $orderId,
            ]);

            Program::where('id', $program['id'])
                ->increment('raised', (int) $data['nominal']);
        }

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
                    'id' => $isSeed ? $program['slug'] : $program['id'],
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
      
        $orderId = $request->query('order_id');

        if ($orderId) {
            $donation = Donation::with('program')
                ->where('order_id', $orderId)
                ->firstOrFail();

            // program array kamu (biar konsisten sama getProgram)
            $program = $donation->program ? [
                'id' => $donation->program->id,
                'slug' => $donation->program->slug,
                'title' => $donation->program->title,
                'image' => $this->resolveImage($donation->program->image),
                'target' => (int) ($donation->program->target ?? 0),
                'raised' => (int) ($donation->program->raised ?? 0),
            ] : null;

            $nominal = (int) $donation->amount;

            return view('donasi.sukses', [
                'donation' => $donation,
                'program' => $program,
                'nominal' => $nominal,
                'orderId' => $orderId,
            ]);
        }

        // fallback lama kalau order_id gak ada
        $slug = $request->query('slug');
        $nominal = (int) $request->query('nominal', 0);

        $program = $slug ? $this->getProgram($slug) : null;

        return view('donasi.sukses', compact('program', 'nominal', 'slug'));
    }

    protected function setMidtransConfig(): void
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    
}
