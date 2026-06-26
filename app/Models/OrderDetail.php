<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'service_id',
        'service_type_id',
        'service_name',
        'service_price',
        'service_quantity',
        'service_detail_total',
        'color_code',
        'is_deleted'
    ];

    public function articles()
    {
        return $this->hasMany(
            \App\Models\OrderArticle::class,
            'order_detail_id',
            'id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where(
            'is_deleted',
            0
        );
    }

    public function serviceType()
    {
        return $this->belongsTo(
            ServiceType::class,
            'service_type_id'
        );
    }
}
