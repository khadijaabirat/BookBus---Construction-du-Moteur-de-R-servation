<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'matricule',
        'capacite',
        'statut',
        'type',
        'amenities'
    ];

    protected $casts = [
        'amenities' => 'array',
    ];

    public function segments()
    {
        return $this->hasMany(Segment::class);
    }
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
