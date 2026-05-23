<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $fillable = [
        'jour_depart',
        'heure_depart',
        'heure_arrivee',
        'route_id'
    ];

    protected $casts = [
        'jour_depart' => 'date',
        // heure_depart and heure_arrivee are times
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function segments()
    {
        return $this->hasMany(Segment::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function isActive()
    {
        // Simple check: departure date is today or in the future
        return $this->jour_depart >= now()->toDateString();
    }
}
