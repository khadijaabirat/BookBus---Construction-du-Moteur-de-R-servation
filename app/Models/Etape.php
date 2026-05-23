<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    protected $fillable = [
        'ordre',
        'heure_passage',
        'route_id',
        'gare_id'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function gare()
    {
        return $this->belongsTo(Gare::class);
    }

    public function segmentsDepart()
    {
        return $this->hasMany(Segment::class, 'etape_depart_id');
    }

    public function segmentsArrivee()
    {
        return $this->hasMany(Segment::class, 'etape_arrivee_id');
    }
}
