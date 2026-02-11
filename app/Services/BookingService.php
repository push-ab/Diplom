<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\Showing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * @return Booking[] созданные брони
     */
    public function bookSeats(
        int $showingId,
        array $seatIds,
        ?string $email = null,
        ?string $phone = null
    ): array {
        return DB::transaction(function () use ($showingId, $seatIds, $email, $phone) {

            /** @var Showing $showing */
            $showing = Showing::query()
                              ->with('hall')
                              ->lockForUpdate()
                              ->findOrFail($showingId);

            if (!$showing->hall->is_active) {
                throw ValidationException::withMessages([
                    'showing_id' => 'Продажа билетов в этот зал отключена.',
                ]);
            }

            // Проверяем, что места принадлежат залу сеанса и активны
            $seats = Seat::query()
                         ->whereIn('id', $seatIds)
                         ->lockForUpdate()
                         ->get();

            if ($seats->count() !== count($seatIds)) {
                throw ValidationException::withMessages([
                    'seat_ids' => 'Некоторые места не найдены.',
                ]);
            }

            foreach ($seats as $seat) {
                if ($seat->hall_id !== $showing->hall_id) {
                    throw ValidationException::withMessages([
                        'seat_ids' => 'Выбраны места не из этого зала.',
                    ]);
                }
                if (!$seat->is_enabled) {
                    throw ValidationException::withMessages([
                        'seat_ids' => 'Одно из выбранных мест недоступно.',
                    ]);
                }
            }

            $alreadyBooked = Booking::query()
                                    ->where('showing_id', $showingId)
                                    ->whereIn('seat_id', $seatIds)
                                    ->exists();

            if ($alreadyBooked) {
                throw ValidationException::withMessages([
                    'seat_ids' => 'Некоторые места уже забронированы.',
                ]);
            }

            $created = [];
            foreach ($seatIds as $seatId) {
                $created[] = Booking::create([
                    'showing_id' => $showingId,
                    'seat_id' => $seatId,
                    'code' => $this->generateCode(),
                    'status' => 'booked',
                    'customer_email' => $email,
                    'customer_phone' => $phone,
                ]);
            }

            return $created;
        });
    }

    private function generateCode(): string
    {
        return Str::upper(Str::random(12)) . '-' . now()->format('YmdHis');
    }
}
