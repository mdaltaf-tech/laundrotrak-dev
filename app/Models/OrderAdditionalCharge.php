<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderAdditionalCharge extends Model
{
    protected $fillable = [
        'order_id',
        'charge_type_id',
        'amount',
        'remarks',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'is_deleted' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function chargeType()
    {
        return $this->belongsTo(AdditionalChargeType::class, 'charge_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query)
    {
        return $query->where('is_deleted', false);
    }
}
