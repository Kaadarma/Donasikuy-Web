<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program; // sesuaikan dengan model punyamu

class DanaPuniaController extends Controller
{
    public function index(Request $request)
    {
        // contoh filter sederhana (search + urutkan)
        $search = $request->query('q');
        $sort   = $request->query('sort', 'terbaru');

        $query = Program::query()
            ->where('category', 'Dana Punia'); // sesuaikan nama kolom category-nya

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($sort === 'terkumpul_terbanyak') {
            $query->orderByDesc('raised');
        } else {
            // default: terbaru
            $query->orderByDesc('created_at');
        }

        $programs = $query->paginate(9)->withQueryString();

        return view('danapunia.index', compact('programs', 'search', 'sort'));
    }
}
