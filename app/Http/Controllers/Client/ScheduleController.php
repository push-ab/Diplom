<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Showing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $selected = $request->filled('date')
            ? Carbon::parse($request->query('date'))->startOfDay()
            : now()->startOfDay();

        $navStart = $selected->copy();
        $navDays = collect(range(0, 5))->map(fn($i) => $navStart->copy()->addDays($i));

        $from = $selected->copy()->startOfDay();
        $to   = $selected->copy()->endOfDay();

        $showings = Showing::query()
                           ->with(['movie', 'hall'])
                           ->whereBetween('start_time', [$from, $to])
                           ->orderBy('start_time')
                           ->get();

        return view('client.index', [
            'showings' => $showings,
            'selectedDate' => $selected,
            'navDays' => $navDays,
            'nextDate' => $navStart->copy()->addDays(6),
            'prevDate' => $navStart->copy()->subDays(6),
        ]);
    }
}
