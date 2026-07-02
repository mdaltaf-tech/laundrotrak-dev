<?php

namespace Database\Seeders;

use App\Models\AdditionalChargeType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdditionalChargeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chargeTypes = [
            [
                'charge_name'    => 'Express Service',
                'default_amount' => 0,
                'sort_order'     => 1,
            ],
            [
                'charge_name'    => 'Pickup Charge',
                'default_amount' => 0,
                'sort_order'     => 2,
            ],
            [
                'charge_name'    => 'Delivery Charge',
                'default_amount' => 0,
                'sort_order'     => 3,
            ],
            [
                'charge_name'    => 'Stain Removal',
                'default_amount' => 0,
                'sort_order'     => 4,
            ],
            [
                'charge_name'    => 'Accessories',
                'default_amount' => 0,
                'sort_order'     => 5,
            ],
            [
                'charge_name'    => 'Packing Charge',
                'default_amount' => 0,
                'sort_order'     => 6,
            ],
        ];

        foreach ($chargeTypes as $chargeType) {

            AdditionalChargeType::updateOrCreate(
                [
                    'slug' => Str::slug($chargeType['charge_name']),
                ],
                [
                    'charge_name'    => $chargeType['charge_name'],
                    'slug'           => Str::slug($chargeType['charge_name']),
                    'default_amount' => $chargeType['default_amount'],
                    'sort_order'     => $chargeType['sort_order'],
                    'is_active'      => true,
                ]
            );
        }
    }
}
