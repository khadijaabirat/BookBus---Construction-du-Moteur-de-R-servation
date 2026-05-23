<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'cin',
        'phone',
        'email'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
