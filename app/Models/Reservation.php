<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'reference',
        'date_reservation',
        'statut',
        'siege_numero',
        'user_id',
        'passenger_id',
        'segment_id',
        'snack_box',
        'insurance',
        'promo_code',
        'base_price',
        'extras_price',
        'total_price',
        'cancelled_at',
        'refund_amount',
        'payment_method'
    ];

    protected $casts = [
        'date_reservation' => 'date',
        'snack_box' => 'boolean',
        'insurance' => 'boolean',
        'base_price' => 'decimal:2',
        'extras_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'refund_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
}
