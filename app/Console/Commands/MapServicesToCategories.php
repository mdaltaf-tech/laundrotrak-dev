<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;

class MapServicesToCategories extends Command
{
    protected $signature = 'faeblo:map-service-categories';

    protected $description = 'Map existing services to categories';

    public function handle()
    {
        $this->info('Starting service mapping...');

        // Men Wear
        Service::whereIn('id', [
            4, 6, 13, 16, 21, 22, 23, 24, 29, 30, 35, 39, 57, 71
        ])->update(['category_id' => 1]);

        // Women Wear
        Service::whereIn('id', [
            11, 32, 33, 34, 43, 50, 52, 63, 64, 65, 66
        ])->update(['category_id' => 2]);

        // Winter Wear
        Service::whereIn('id', [
            20, 31, 47, 48, 53, 58
        ])->update(['category_id' => 3]);

        // Household
        Service::whereIn('id', [
            28, 36, 37, 46, 54, 55, 61, 68, 72, 73
        ])->update(['category_id' => 4]);

        // Footwear
        Service::whereIn('id', [
            38, 62, 69, 74
        ])->update(['category_id' => 5]);

        // Accessories
        Service::whereIn('id', [
            49, 56, 59, 60, 70
        ])->update(['category_id' => 6]);

        // Laundry Services
        Service::whereIn('id', [
            8, 51, 67
        ])->update(['category_id' => 7]);

        // Steam Iron
        Service::whereIn('id', [
            40, 41
        ])->update(['category_id' => 8]);

        $this->info('Service mapping completed successfully.');
    }
}
