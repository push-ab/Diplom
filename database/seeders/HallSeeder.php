<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hall;
use App\Models\Seat;

class HallSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $halls = [
                ['title' => 'Зал 1', 'rows' => 8, 'cols' => 10, 'is_active' => true],
                ['title' => 'Зал 2', 'rows' => 6, 'cols' => 8,  'is_active' => true],
            ];

            foreach ($halls as $data) {
                $hall = Hall::create($data);

                for ($r = 1; $r <= $hall->rows; $r++) {
                    for ($c = 1; $c <= $hall->cols; $c++) {
                        $isVip = ($r >= $hall->rows - 1); // последние 2 ряда VIP (можешь поменять)
                        Seat::create([
                            'hall_id' => $hall->id,
                            'row' => $r,
                            'col' => $c,
                            'type' => $isVip ? 'vip' : 'standard',
                            'is_enabled' => true,
                        ]);
                    }
                }
            }
        });
    }
}
