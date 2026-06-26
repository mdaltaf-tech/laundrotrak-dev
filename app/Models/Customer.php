<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'tax_number',
        'address',
        'billing_type',
        'is_active',
        'created_by'
    ];

    const BILLING_STANDARD = 0;
    const BILLING_CREDIT = 1;
}
