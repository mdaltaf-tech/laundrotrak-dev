<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_name',
        'category_id',
        'icon',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(
            ServiceCategory::class,
            'category_id'
        );
    }
}
