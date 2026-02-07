<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    public function bus() {
    return $this->belongsTo(Bus::class);
}

public function depart() {
    return $this->belongsTo(Etape::class, 'etape_depart_id');
}

public function arrivee() {
    return $this->belongsTo(Etape::class, 'etape_arrivee_id');
}
public function reservations() {
    return $this->hasMany(Reservation::class);
}
public function programme() {
    return $this->belongsTo(Programme::class);
}

public function scopeAvailable($query) {
    return $query->where('date_voyage', '>=', now());
}
}
