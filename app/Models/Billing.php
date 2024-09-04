<?php

namespace App\Models;

use App\Traits\BranchAccessTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PaginationsTrait;
use App\Traits\ZoneAccessTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    use HasFactory;
    use PaginationsTrait;
    //use BranchAccessTrait;
    //use ZoneAccessTrait;
    use SoftDeletes;

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

    protected $fillable = [
        'previous_r',
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
        'notes',
        'refund_date',
        'business_id',
        'branch_id',
        'zone_id',
        'site_id',
        'house_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected $dates = [
        'transaction_date',
        'refund_date',
        'deleted_at',
    ];


    /**
     * Get the business that owns the billing
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function businesses(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function branches(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function zones(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function sites(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the business that owns the house
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function houses()
    {
        return $this->belongsTo(House::class);
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

    public function users()
    {
        return $this->belongsToMany(User::class, 'billings_users');
    }
}
