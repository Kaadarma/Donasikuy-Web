<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycSubmission;
use Illuminate\Http\Request;

class AdminKycController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $status = $request->status; // pending|approved|rejected|null

        $kycs = KycSubmission::query()
            ->with('user')
            ->when($status && $status !== 'all', fn($qq) => $qq->where('status', $status))
            ->when($q, function($qq) use ($q) {
                $qq->whereHas('user', function($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.kyc.index', compact('kycs'));
    }


    public function show($id)
    {
        $kyc = KycSubmission::findOrFail($id);

        return view('admin.kyc.show', compact('kyc'));
    }



    public function approve($id)
    {
        $kyc = KycSubmission::findOrFail($id);

        $kyc->update([
            'status' => 'approved',
            'note' => null, // bersihin catatan kalau sebelumnya pernah ditolak
        ]);

        return redirect()
            ->route('admin.kyc.index')
            ->with('success', 'KYC berhasil disetujui');
    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'note' => 'required'
        ]);

        $kyc = KycSubmission::findOrFail($id);

        $kyc->update([
            'status' => 'rejected',
            'note' => $request->note,
        ]);

        return redirect()
            ->route('admin.kyc.index')
            ->with('danger', 'KYC berhasil ditolak');
    }

}
