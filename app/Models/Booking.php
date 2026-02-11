<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['code','showing_id','email','total','status'];

    public function showing() {
        return $this->belongsTo(Showing::class);
    }

    public function seats() {
        return $this->hasMany(BookingSeat::class);
    }
}
