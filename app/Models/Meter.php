<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meter extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'meters';

   
    protected $fillable = [
        'name',
        'meter_type_id',
        'timezone',
        'current_reading',
        'user_id',
    ];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function($query) {
            $query->created_by = auth()->user()->id ?? null;
        });

        static::updating(function($query) {
            $query->created_by = auth()->user()->id ?? null;
        });

    }

    public function user()
{
    return $this->belongsTo(User::class);
}

    
    public function meterType()
    {
        return $this->belongsTo(MeterType::class,'meter_type_id');
    }
    
}

