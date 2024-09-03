<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\PaginationsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Authenticatable 
{
    use HasFactory, Notifiable, PaginationsTrait , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'customer_id',
    ];


    
}