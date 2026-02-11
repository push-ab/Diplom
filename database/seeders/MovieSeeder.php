<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        Movie::create([
            'title' => 'Звёздные войны XXIII: Атака клонированных клонов',
            'description' => 'Две сотни лет назад малороссийские хутора разоряла шайка...',
            'duration_minutes' => 130,
            'poster' => 'posters/poster1.jpg', // положи файл сюда
            'age_rating' => '12+',
        ]);

        Movie::create([
            'title' => 'Дикий робот',
            'description' => 'Невероятные приключения робота в дикой природе.',
            'duration_minutes' => 105,
            'poster' => 'posters/poster2.jpg',
            'age_rating' => '6+',
        ]);
    }
}
