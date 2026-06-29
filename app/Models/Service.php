<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceDetail;
use App\Models\ServiceCategory;

class Service extends Model
{
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

    public function serviceDetails()
    {
        return $this->hasMany(
            ServiceDetail::class,
            'service_id'
        );
    }
}
