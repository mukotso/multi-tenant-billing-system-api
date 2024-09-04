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

    protected $fillable = ['code','format', 'status'];

    
    public function meters()
    {
        return $this->hasMany(Meter::class);
    }

}