<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycSubmission;
use Illuminate\Http\Request;

class AdminKycController extends Controller
{
    public function index()
    {
        $kycs = KycSubmission::with('user')->latest()->get();

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
