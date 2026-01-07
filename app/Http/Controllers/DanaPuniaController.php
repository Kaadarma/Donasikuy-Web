<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class DanaPuniaController extends Controller
{
    public function index(Request $request)
    {
        $q    = $request->query('q');
        $sort = $request->query('sort', 'terbaru');

        $programs = Program::query()
            ->whereIn('status', [Program::STATUS_APPROVED, Program::STATUS_RUNNING])
            ->where('category', 'punia')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                       ->orWhere('short_description', 'like', "%{$q}%");
                });
            })
            ->when($sort === 'terkumpul_terbanyak', function ($query) {
                $query->withSum(['donations as raised_sum' => function ($q) {
                    $q->whereIn('status', ['settlement', 'capture']);
                }], 'amount')->orderByDesc('raised_sum');
            }, function ($query) {
                $query->latest();
            })
            ->paginate(9)
            ->withQueryString();

        return view('danapunia.index', compact('programs'));
    }
}
