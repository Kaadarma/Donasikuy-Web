<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class UserEventController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('dashboard.events.index', compact('events'));
    }
}
