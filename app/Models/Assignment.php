<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'programme_id',
        'bus_id',
        'employee_id',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
