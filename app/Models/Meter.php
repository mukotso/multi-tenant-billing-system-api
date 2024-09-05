<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\PaginationsTrait;

class Meter extends Model
{
    use HasFactory, SoftDeletes, PaginationsTrait;


    protected $table = 'meters';

   
    protected $fillable = [
        'name',
        'tenant_id',
        'meter_type_id',
        'timezone',
        'current_reading',
        'previous_reading',
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


    
    public function meterType()
    {
        return $this->belongsTo(MeterType::class,'meter_type_id');
    }

    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class,'tenant_id');
    }
    
    
}

