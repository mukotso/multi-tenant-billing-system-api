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

    protected $fillable = ['meter_id','tenant_id','consumption_period','total_consumption'];

    
   

}