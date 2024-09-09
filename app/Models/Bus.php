<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = ['current_location'];
    
    public function driver(){
        return $this->belongsTo(Driver::class);
    }
}
