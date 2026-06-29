<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'category_name',
        'category_icon',
        'category_color',
        'sort_order',
        'is_active'
    ];

    public function services()
    {
        return $this->hasMany(
            Service::class,
            'category_id'
        );
    }
}
