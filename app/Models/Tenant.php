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
        'customer_id',
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

    /**
     * Get the customer that owns the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    
    /**
     * Get the payment methods for the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, 'tenant_id', 'id');
    }

    
    /**
     * Get the billings for the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billings()
    {
        return $this->hasMany(Billing::class, 'tenant_id', 'id');
    }

    
    /**
     * Get the billing payments for the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billingPayments()
    {
        return $this->hasMany(BillingPayment::class, 'tenant_id', 'id');
    }
}