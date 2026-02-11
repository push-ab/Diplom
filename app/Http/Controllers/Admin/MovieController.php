<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMovieRequest;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::query()->orderBy('id','desc')->get();
        return view('admin.movies.index', compact('movies'));
    }

    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Movie::create($data);

        return back()->with('ok', 'Фильм добавлен.');
    }

    public function destroy(\App\Models\Movie $movie)
    {
        $movie->delete();
        return back()->with('ok', 'Фильм удалён.');
    }
}
