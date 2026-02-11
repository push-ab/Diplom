<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShowingRequest;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Showing;
use Carbon\Carbon;

class ShowingController extends Controller
{
    public function index()
    {
        $showings = Showing::query()->with(['movie','hall'])->orderBy('start_time')->get();
        return view('admin.showings.index', compact('showings'));
    }

    public function store(StoreShowingRequest $request)
    {
        $data = $request->validated();

        $movie = Movie::findOrFail($data['movie_id']);
        $hall  = Hall::findOrFail($data['hall_id']);

        $starts = Carbon::parse($data['start_time']);
        $ends   = $starts->copy()->addMinutes((int)$movie->duration_minutes);

        $conflict = Showing::query()
                           ->where('hall_id', $hall->id)
                           ->where(function ($q) use ($starts, $ends) {
                               $q->where('start_time', '<', $ends)
                                 ->where('end_time', '>', $starts);
                           })
                           ->exists();

        if ($conflict) {
            return back()->withErrors([
              'start_time' => 'В этом зале уже есть сеанс, пересекающийся по времени.',
            ])->withInput();
        }

        Showing::create([
            'hall_id' => $hall->id,
            'movie_id' => $movie->id,
            'start_time' => $starts,
            'end_time' => $ends,
        ]);

        return back()->with('ok', 'Сеанс добавлен.');
    }

    public function destroy(\App\Models\Showing $showing)
    {
        $showing->delete();
        return back()->with('ok', 'Сеанс снят.');
    }
}
