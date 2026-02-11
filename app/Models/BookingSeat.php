<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    protected $fillable = ['booking_id','showing_id','seat_id','seat_type','price'];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function seat() {
        return $this->belongsTo(Seat::class);
    }
}
