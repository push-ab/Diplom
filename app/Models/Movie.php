<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = ['title','description','duration_minutes','poster','age_rating'];

    public function showings(): HasMany
    {
        return $this->hasMany(Showing::class);
    }
}
