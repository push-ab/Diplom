<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHallRequest;
use App\Models\Hall;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index()
    {
        $halls = Hall::query()->orderBy('id','desc')->get();
        return view('admin.halls.index', compact('halls'));
    }

    public function store(StoreHallRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $hall = Hall::create($data);

            // Генерируем сетку мест N×M
            for ($r = 1; $r <= $hall->rows; $r++) {
                for ($c = 1; $c <= $hall->cols; $c++) {
                    Seat::create([
                        'hall_id' => $hall->id,
                        'row' => $r,
                        'col' => $c,
                        'type' => 'standard',
                        'is_enabled' => true,
                    ]);
                }
            }
        });

        return back()->with('ok', 'Зал создан.');
    }

    public function toggleSales(Hall $hall)
    {
        $hall->update(['is_active' => !$hall->is_active]);
        return back()->with('ok', 'Статус продажи билетов изменён.');
    }

    public function updateConfig(Request $request, Hall $hall)
    {
        $data = $request->validate([
           'rows' => ['required','integer','min:1','max:30'],
           'cols' => ['required','integer','min:1','max:30'],
           'seats_json' => ['nullable','string'], // JSON со схемой
        ]);

        DB::transaction(function () use ($hall, $data) {
            $rows = (int)$data['rows'];
            $cols = (int)$data['cols'];

            $sizeChanged = ($hall->rows !== $rows) || ($hall->cols !== $cols);

            $hall->update(['rows' => $rows, 'cols' => $cols]);

            if ($sizeChanged) {
                $hall->seats()->delete();

                for ($r = 1; $r <= $rows; $r++) {
                    for ($c = 1; $c <= $cols; $c++) {
                        Seat::create([
                             'hall_id' => $hall->id,
                             'row' => $r,
                             'col' => $c,
                             'type' => 'standard',
                             'is_enabled' => true,
                        ]);
                    }
                }
            }

            if (!empty($data['seats_json'])) {
                $items = json_decode($data['seats_json'], true);

                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (!isset($item['id'], $item['type'])) continue;

                        $seat = Seat::where('hall_id', $hall->id)
                                    ->where('id', (int)$item['id'])
                                    ->first();

                        if (!$seat) continue;

                        $type = $item['type'];

                        if ($type === 'disabled') {
                            $seat->update([
                              'is_enabled' => false,
                              'type' => 'standard',
                            ]);
                        } else {
                            $seat->update([
                              'is_enabled' => true,
                              'type' => $type,
                            ]);
                        }
                    }
                }
            }
        });

        return back()->with('ok', 'Конфигурация зала сохранена');
    }

    public function updatePrices(Request $request, Hall $hall)
    {
        $data = $request->validate([
           'price_standard' => ['required','integer','min:0','max:1000000'],
           'price_vip' => ['required','integer','min:0','max:1000000'],
        ]);

        $hall->update($data);

        return back()->with('ok', 'Цены сохранены');
    }

    public function destroy(\App\Models\Hall $hall)
    {
        $hall->delete();
        return back()->with('ok', 'Зал удалён.');
    }
}
