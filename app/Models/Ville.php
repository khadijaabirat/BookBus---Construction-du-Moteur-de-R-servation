<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    protected $fillable = ['name'];

    public function gares()
    {
        return $this->hasMany(Gare::class);
    }
}
