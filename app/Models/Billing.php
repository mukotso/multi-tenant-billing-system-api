<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PaginationsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    use HasFactory;
    use PaginationsTrait;
    use SoftDeletes;



    protected $fillable = [
        'previous_r',
        'tenant_id',
        'meter_id',
        'current_r',
        'usage',
        'amount_paid',
        'subtotal',
        'rate',
        'discount_percent',
        'tax_amount',
        'grand_total',
        'balance',
        'old_balance',
        'status',
        'transaction_date',
        'discount_type',
        'bill_date',
        'bill_month',
        'bill_year',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected $dates = [
        'transaction_date',
        'refund_date',
        'deleted_at',
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
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the meter that owns the billing
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meter(): BelongsTo
    {
        return $this->belongsTo(Meter::class);
    }

    /**
     * Get all of the billing payments for the billing
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billingPayments(): HasMany
    {
        return $this->hasMany(BillingPayment::class);
    }

    
}
