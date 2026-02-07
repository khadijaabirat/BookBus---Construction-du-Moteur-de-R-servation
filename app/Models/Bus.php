<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = ['matricule', 'capacite', 'statut'];
    public function segment(){
        return $this->hasMany(Segment::class);

    }
}
