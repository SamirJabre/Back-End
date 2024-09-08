<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'from',
        'to',
        'date',
        'departure_time',
        'arrival_time',
        'price',
        'bus_id',
        'routes',
    ];
    public function users(){
        return $this->hasMany(User::class);
    }
    public function driver(){
        return $this->belongsTo(Driver::class);
    }
}
