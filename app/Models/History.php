<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    public function users(){
        return $this->hasMany(User::class);
    }
    public function driver(){
        return $this->belongsTo(Driver::class);
    }
    public function routes(){
        return $this->hasMany(Route::class);
    }
}
