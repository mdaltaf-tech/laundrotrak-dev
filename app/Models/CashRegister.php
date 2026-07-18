<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_date',
        'receipt_no',
        'withdraw_amount',
        'closing_cash',
        'reconciled_at',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'business_date'   => 'date',
        'withdraw_amount' => 'decimal:2',
        'closing_cash'    => 'decimal:2',
        'reconciled_at'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusAttribute(): string
    {
        if (!$this->closing_cash) {
            return 'OPEN';
        }

        return 'CLOSED';
    }

    public function closure(): HasOne
    {
        return $this->hasOne(BusinessDayClosure::class);
    }
}
