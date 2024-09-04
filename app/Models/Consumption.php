<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PaginationsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consumption extends Model
{
    use HasFactory;
    use PaginationsTrait;
    use SoftDeletes;

    protected $fillable = ['meter_id','tenant_id','consumption_date','total_consumption','rate','status'];


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
    
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function meter()
    {
        return $this->belongsTo(Meter::class);
    }

}