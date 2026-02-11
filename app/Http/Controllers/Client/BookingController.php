<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreBookingRequest;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Showing;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, BookingService $service)
    {
        $bookings = $service->bookSeats(
            (int)$request->input('showing_id'),
            $request->input('seat_ids'),
            $request->input('customer_email'),
            $request->input('customer_phone'),
        );

        return redirect()->route('client.payment', ['code' => $bookings[0]->code]);
    }

    public function hall(Showing $showing)
    {
        $showing->load(['hall.seats', 'movie']);

        $busySeatIds = BookingSeat::query()
                                  ->join('bookings', 'bookings.id', '=', 'booking_seats.booking_id')
                                  ->where('booking_seats.showing_id', $showing->id)
                                  ->whereIn('bookings.status', ['reserved','paid'])
                                  ->pluck('booking_seats.seat_id')
                                  ->all();

        $seatMap = $showing->hall->seats->keyBy(fn($s) => $s->row.'-'.$s->col);

        return view('client.hall', [
            'showing' => $showing,
            'hall' => $showing->hall,
            'busySeatIds' => $busySeatIds,
            'seatMap' => $seatMap,
        ]);
    }

    public function reserve(Request $request, Showing $showing)
    {
        $data = $request->validate([
           'seat_ids' => ['required','array','min:1'],
           'seat_ids.*' => ['integer'],
           'email' => ['nullable','email','max:180'],
       ]);

        $showing->load(['hall.seats', 'movie']);

        $seatIds = array_values(array_unique($data['seat_ids']));
        $seats = $showing->hall->seats->whereIn('id', $seatIds);

        if ($seats->count() !== count($seatIds)) {
            return back()->withErrors(['seat_ids' => 'Некоторые места не найдены.'])->withInput();
        }

        foreach ($seats as $seat) {
            if (!$seat->is_enabled) {
                return back()->withErrors(['seat_ids' => 'Среди выбранных есть недоступные места.'])->withInput();
            }
        }

        $priceStandard = (int)($showing->hall->price_standard ?? 0);
        $priceVip = (int)($showing->hall->price_vip ?? 0);

        $code = Str::upper(Str::random(10));

        try {
            $booking = DB::transaction(function () use ($showing, $seats, $data, $priceStandard, $priceVip, $code) {
                $total = 0;

                $booking = Booking::create([
                   'code' => $code,
                   'showing_id' => $showing->id,
                   'customer_email' => $data['email'] ?? null,
                   'total' => 0,
                   'status' => 'booked',
                ]);

                foreach ($seats as $seat) {
                    $seatType = $seat->type === 'vip' ? 'vip' : 'standard';
                    $price = $seatType === 'vip' ? $priceVip : $priceStandard;
                    $total += $price;

                    BookingSeat::create([
                        'booking_id' => $booking->id,
                        'showing_id' => $showing->id,
                        'seat_id' => $seat->id,
                        'seat_type' => $seatType,
                        'price' => $price,
                    ]);
                }

                $booking->update(['total' => $total]);

                return $booking;
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['seat_ids' => 'Не удалось забронировать: место(а) уже заняты.'])->withInput();
        }

        return redirect()->route('client.payment', ['code' => $booking->code]);
    }

    public function payment(string $code)
    {
        $booking = Booking::query()
                          ->where('code', $code)
                          ->with(['showing.movie', 'showing.hall', 'seats.seat'])
                          ->firstOrFail();

        return view('client.payment', compact('booking'));
    }

    public function pay(string $code)
    {
        $booking = Booking::where('code', $code)->firstOrFail();

        if ($booking->status !== 'paid') {
            $booking->update(['status' => 'paid']);
        }

        return redirect()->route('client.ticket', ['code' => $code]);
    }

    public function ticket(string $code)
    {
        $booking = Booking::query()
                          ->where('code', $code)
                          ->with(['showing.movie', 'showing.hall', 'seats.seat'])
                          ->firstOrFail();

        abort_if($booking->status !== 'paid', 403, 'Бронь не оплачена.');

        return view('client.ticket', compact('booking'));
    }
}
