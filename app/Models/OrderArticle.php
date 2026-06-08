<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderArticle extends Model
{
    use HasFactory;

    const STATUS_RECEIVED = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_READY = 2;
    const STATUS_DELIVERED = 3;
    const STATUS_RETURNED = 4;
    const STATUS_CANCELLED = 9;

    protected $fillable = [
        'order_id',
        'order_detail_id',
        'tag_number',
        'article_name',
        'service_name',
        'color_code',
        'status',
        'created_by',
        'processing_at',
        'ready_at',
        'delivered_at'
    ];

    protected $casts = [

        'processing_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime'

    ];

    public function order()
    {
        return $this->belongsTo(
            Order::class
        );
    }

    public function detail()
    {
        return $this->belongsTo(
            OrderDetail::class,
            'order_detail_id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            '!=',
            self::STATUS_CANCELLED
        );
    }

    public function getStatusTextAttribute()
    {
        return match($this->status){

            self::STATUS_RECEIVED => 'Received',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_READY => 'Ready',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_RETURNED => 'Returned',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown'
        };
    }
}
