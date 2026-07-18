<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessDayClosure extends Model
{
    protected $fillable = [
        'cash_register_id',
        'business_date',

        'opening_cash',
        'cash_collection',
        'upi_collection',
        'card_collection',
        'wallet_collection',
        'other_collection',

        'expense_amount',
        'withdraw_amount',

        'expected_cash',
        'counted_cash',
        'difference_amount',

        'difference_reason',
        'remarks',

        'closed_by',
        'closed_at',
    ];

    protected $casts = [
        'business_date'      => 'date',
        'closed_at'          => 'datetime',

        'opening_cash'       => 'decimal:2',
        'cash_collection'    => 'decimal:2',
        'upi_collection'     => 'decimal:2',
        'card_collection'    => 'decimal:2',
        'wallet_collection'  => 'decimal:2',
        'other_collection'   => 'decimal:2',
        'expense_amount'     => 'decimal:2',
        'withdraw_amount'    => 'decimal:2',
        'expected_cash'      => 'decimal:2',
        'counted_cash'       => 'decimal:2',
        'difference_amount'  => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isBalanced(): bool
    {
        return (float) $this->difference_amount === 0.0;
    }

    public function isShort(): bool
    {
        return (float) $this->difference_amount < 0;
    }

    public function isExcess(): bool
    {
        return (float) $this->difference_amount > 0;
    }

    public function getStatusAttribute(): string
    {
        if ($this->isBalanced()) {
            return 'BALANCED';
        }

        return $this->isShort()
            ? 'DRAWER_SHORT'
            : 'DRAWER_EXCESS';
    }
}
