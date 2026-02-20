<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Showing;
use Carbon\Carbon;

class ShowingSeeder extends Seeder
{
    public function run(): void
    {
        $hall1 = Hall::where('title', 'Зал 1')->firstOrFail();
        $hall2 = Hall::where('title', 'Зал 2')->firstOrFail();

        $movies = Movie::all();

        $today = Carbon::today();

        foreach ($movies as $i => $movie) {
            // Зал 1
            $start1 = $today->copy()->setTime(10 + $i * 2, 20);
            Showing::create([
                'hall_id' => $hall1->id,
                'movie_id' => $movie->id,
                'start_time' => $start1,
                'end_time' => $start1->copy()->addMinutes($movie->duration_minutes),
            ]);

            $start2 = $today->copy()->setTime(18 + $i * 1, 40);
            Showing::create([
                'hall_id' => $hall1->id,
                'movie_id' => $movie->id,
                'start_time' => $start2,
                'end_time' => $start2->copy()->addMinutes($movie->duration_minutes),
            ]);

            // Зал 2
            $start3 = $today->copy()->setTime(11 + $i * 2, 15);
            Showing::create([
                'hall_id' => $hall2->id,
                'movie_id' => $movie->id,
                'start_time' => $start3,
                'end_time' => $start3->copy()->addMinutes($movie->duration_minutes),
            ]);
        }
    }
}
