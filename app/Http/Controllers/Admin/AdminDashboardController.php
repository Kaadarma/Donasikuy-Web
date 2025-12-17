<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycSubmission;
use App\Models\Program;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $kycPending = KycSubmission::where('status', 'pending')->count();

        $programPending = Program::where('is_active', 0)->count();
        // kalau is_active = 0 artinya pending

        return view('admin.dashboard', compact(
            'kycPending',
            'programPending'
        ));
    }
}
