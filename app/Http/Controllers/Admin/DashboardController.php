<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Showing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $halls = Hall::orderBy('id')->get();
        $movies = Movie::orderBy('id')->get();
        $showings = Showing::with(['movie','hall'])->orderBy('start_time')->get();

        $selectedHall = \App\Models\Hall::with('seats')->find(request('hall_id'))
            ?? \App\Models\Hall::with('seats')->first();

        return view('admin.index', compact('halls','movies','showings','selectedHall'));
    }
}
