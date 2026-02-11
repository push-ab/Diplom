<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    protected $fillable = ['hall_id', 'row', 'col', 'type', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }
}
