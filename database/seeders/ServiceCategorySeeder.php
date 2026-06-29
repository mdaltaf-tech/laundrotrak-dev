<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Men Wear',         'sort_order' => 1],
            ['category_name' => 'Women Wear',       'sort_order' => 2],
            ['category_name' => 'Winter Wear',      'sort_order' => 3],
            ['category_name' => 'Household',        'sort_order' => 4],
            ['category_name' => 'Footwear',         'sort_order' => 5],
            ['category_name' => 'Accessories',      'sort_order' => 6],
            ['category_name' => 'Laundry Services', 'sort_order' => 7],
            ['category_name' => 'Steam Iron',       'sort_order' => 8],
        ];

        foreach ($categories as $category) {
            ServiceCategory::updateOrCreate(
                ['category_name' => $category['category_name']],
                [
                    'sort_order' => $category['sort_order'],
                    'is_active'  => 1,
                ]
            );
        }
    }
}
