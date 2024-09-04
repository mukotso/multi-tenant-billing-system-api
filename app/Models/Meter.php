<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    use HasFactory;


    protected $table = 'meters';

   
    protected $fillable = [
        'name',
        'meter_type_id',
        'timezone',
        'current_reading',
        'user_id',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

    
    public function meterType()
    {
        return $this->belongsTo(MeterType::class,'meter_type_id');
    }
    
}

