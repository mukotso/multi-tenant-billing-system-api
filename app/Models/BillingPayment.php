<?php

namespace App\Models;


use App\Traits\PaginationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingPayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginationsTrait;


    protected $fillable = [
        'tenant_id',
        'billing_id',
        'amount',
        'payment_method_id',
        'date',
        'status',
        'note',
        'payment_ref',
        'created_by',
        'updated_by',
        'deleted_by',
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
     * Get the tenant that owns the billing
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the billing that owns the billing payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function billings()
    {
        return $this->belongsTo(Billing::class);
    }
    
    /**
     * Get the payment method associated with the billing payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

}