<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gare extends Model
{
    public function ville(){
        return $this->belongsTo(Ville::class);
     }
    public function etapes(){
        return $this->hasMany(Etape::class);
    } 
}
