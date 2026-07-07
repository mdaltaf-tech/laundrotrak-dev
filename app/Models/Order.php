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
        'tags_printed_at'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'delivery_date' => 'datetime',
        'tags_printed_at' => 'datetime',
    ];

    const PAYMENT_UNPAID = 0;
    const PAYMENT_PARTIAL = 1;
    const PAYMENT_PAID = 2;
    const PAYMENT_CREDIT = 3;

    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_READY = 2;
    const STATUS_DELIVERED = 3;
    const STATUS_RETURNED = 4;

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

    public function refreshPaymentStatus()
    {
        $paidAmount = Payment::active()
            ->where(
                'order_id',
                $this->id
            )
            ->sum('received_amount');

        $balanceAmount = max(
            0,
            $this->total - $paidAmount
        );

        if ($balanceAmount <= 0) {

            $paymentStatus = self::PAYMENT_PAID;

        } elseif (
            $balanceAmount > 0
            &&
            $this->was_delivered_on_credit
            &&
            $this->status == self::STATUS_DELIVERED
        ) {

            $paymentStatus = self::PAYMENT_CREDIT;

        } elseif ($paidAmount > 0) {

            $paymentStatus = self::PAYMENT_PARTIAL;

        } else {

            $paymentStatus = self::PAYMENT_UNPAID;
        }

        $this->update([
            'paid_amount' => $paidAmount,
            'balance_amount' => $balanceAmount,
            'payment_status' => $paymentStatus,
        ]);
    }

    public function additionalCharges()
    {
        return $this->hasMany(OrderAdditionalCharge::class)
            ->where('is_deleted', false);
    }
}
