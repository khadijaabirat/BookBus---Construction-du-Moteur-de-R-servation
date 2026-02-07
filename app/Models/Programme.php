<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $fillable = ['jour_depart', 'heure_depart', 'heure_arrivee', 'route_id'];
        public function route(){
        return $this->belongsTo(Route::class);
     }
    public function segments(){
        return $this->hasMany(Segment::class);
    } 
    public function isActive()
    {
        return true; 
    }
}
