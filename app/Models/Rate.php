<?php

namespace App\Models;

use App\Traits\PaginationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory;
    use PaginationsTrait;
    use SoftDeletes;
    protected $fillable = [
        'note',
        'name',
        'tenant_id',
        'meter_type_id',
        'to',
        'from',
        'cost',
        'created',
        'deleted',
        'updated',
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
  
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function meter_type()
    {
        return $this->belongsTo(MeterType::class);
    }




}
