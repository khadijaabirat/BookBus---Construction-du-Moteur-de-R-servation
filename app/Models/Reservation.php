<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model{
protected $fillable = ['date_reservation', 'statut', 'siege_numero', 'user_id', 'segment_id'];

public function user() {
    return $this->belongsTo(User::class);
}

public function segment() {
    return $this->belongsTo(Segment::class);
}
    //
}
