<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class DanaPuniaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', '');
        $sort = (string) $request->query('sort', 'terbaru');

        $query = Program::query()
            ->leftJoin('donations', function ($join) {
                $join->on('donations.program_id', '=', 'programs.id')
                    ->whereIn('donations.status', ['success', 'settlement', 'capture']);
            })
            ->select([
                'programs.id',
                'programs.slug',
                'programs.title',
                'programs.category',
                'programs.image',
                'programs.banner',
                'programs.target',
                'programs.created_at',
            ])
            ->selectRaw('COALESCE(SUM(donations.amount),0) as raised_calc')
            ->groupBy([
                'programs.id',
                'programs.slug',
                'programs.title',
                'programs.category',
                'programs.image',
                'programs.banner',
                'programs.target',
                'programs.created_at',
            ]);

        // filter kategori
        if ($kategori !== '') {
            $query->where('programs.category', $kategori);
        }

       
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('programs.title', 'like', "%{$q}%")
                    ->orWhere('programs.description', 'like', "%{$q}%");
            });
        }

        // sort
        if ($sort === 'terkumpul_terbanyak') {
            $query->orderByDesc('raised_calc');
        } else {
            $query->orderByDesc('programs.created_at');
        }

        $paginator = $query->paginate(9)->withQueryString();

        // ubah koleksi jadi array biar blade tetap $program['...']
        $mapped = $paginator->getCollection()->map(function ($p) {
            return [
                'id' => $p->id,
                'slug' => $p->slug,
                'title' => $p->title,
                'category' => $p->category ?? 'lainnya',
                'image' => $p->image ? $p->image : 'images/placeholder.jpg',
                'banner' => $p->banner ? $p->banner : ($p->image ? $p->image : 'images/placeholder-banner.jpg'),
                'target' => (int) ($p->target ?? 0),
                'raised' => (int) ($p->raised_calc ?? 0),
            ];
        });

        $paginator->setCollection($mapped);

        return view('danapunia.index', [
            'programs' => $paginator,
        ]);
    }
}
