<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class AdminCampaignController extends Controller
{
    public function index()
    {
        $campaigns = Program::where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function show(Program $program)
    {
        return view('admin.campaigns.show', compact('program'));
    }

    public function approve(Program $program)
    {
        $program->update([
            'status' => Program::STATUS_APPROVED
        ]);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil disetujui.');
    }

    public function reject(Request $request, Program $program)
    {
        $request->validate([
            'note' => ['required', 'string']
        ]);

        $program->update([
            'status' => Program::STATUS_REJECTED,
            'note'   => $request->note
        ]);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil ditolak.');
    }
}

