<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdditionalChargeType extends Model
{
    protected $fillable = [
        'charge_name',
        'slug',
        'default_amount',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function orderAdditionalCharges()
    {
        return $this->hasMany(OrderAdditionalCharge::class, 'charge_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query)
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('charge_name');
    }
}
