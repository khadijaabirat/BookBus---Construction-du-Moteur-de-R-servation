<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $fillable = [
        'tarif',
        'duree_estimee',
        'distance_km',
        'bus_id',
        'programme_id',
        'etape_depart_id',
        'etape_arrivee_id'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function depart()
    {
        return $this->belongsTo(Etape::class, 'etape_depart_id');
    }

    public function arrivee()
    {
        return $this->belongsTo(Etape::class, 'etape_arrivee_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeAvailable($query)
    {
        return $query->whereHas('programme', function($q) {
            $q->where('jour_depart', '>=', now()->toDateString());
        });
    }
}
