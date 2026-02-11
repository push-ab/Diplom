<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hall extends Model
{
    protected $fillable = ['title','rows','cols','is_active','price_standard','price_vip'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function showings(): HasMany
    {
        return $this->hasMany(Showing::class);
    }
}
