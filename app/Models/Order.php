<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_name',
        'phone_number',
        'order_date',
        'delivery_date',
        'sub_total',
        'addon_total',
        'discount',
        'tax_percentage',
        'tax_amount',
        'total',
        'note',
        'status',
        'order_type',
        'created_by',
        'financial_year_id',
        'is_deleted',
        'payment_status',
        'paid_amount',
        'balance_amount',
    ];

    const PAYMENT_UNPAID = 0;
    const PAYMENT_PARTIAL = 1;
    const PAYMENT_PAID = 2;
    const PAYMENT_CREDIT = 3;

    /* user relation */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /* user relation */
    public function details()
    {
        return $this->hasMany(
            OrderDetail::class
        )->where(
            'is_deleted',
            0
        );
    }

    public function addons()
    {
        return $this->hasMany(
            \App\Models\OrderAddonDetail::class,
            'order_id',
            'id'
        )->active();
    }

    public function articles()
    {
        return $this->hasMany(
            OrderArticle::class,
            'order_id',
            'id'
        )->active();
    }

    public function payments()
    {
        return $this->hasMany(
            Payment::class,
            'order_id',
            'id'
        )->active();
    }

    public function getTotalItemsAttribute()
    {
        return $this->articles()->count();
    }

    public function scopeActive($query)
    {
        return $query->where(
            'is_deleted',
            0
        );
    }
}
