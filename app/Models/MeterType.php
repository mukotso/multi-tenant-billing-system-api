<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PaginationsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeterType extends Model
{
    use HasFactory;
    use PaginationsTrait;
    use SoftDeletes;

    protected $fillable = ['code','format', 'status','tenant_id'];


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
    
    public function meters()
    {
        return $this->hasMany(Meter::class);
    }

}