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

        Service::whereIn('id', [4,21,6,16,22,13,39,35,30,23,24,29,57])
            ->update(['category_id' => 1]);

        Service::whereIn('id', [11,32,33,34,43,65,66,64,63,52,50])
            ->update(['category_id' => 2]);

        Service::whereIn('id', [31,20,47,48,58,53])
            ->update(['category_id' => 3]);

        Service::whereIn('id', [28,61,36,37,46,55,68,54])
            ->update(['category_id' => 4]);

        Service::whereIn('id', [49,70,59,60])
            ->update(['category_id' => 5]);

        Service::whereIn('id', [38,62,69])
            ->update(['category_id' => 6]);

        Service::whereIn('id', [8,51,56,67])
            ->update(['category_id' => 7]);

        Service::whereIn('id', [40,41])
            ->update(['category_id' => 8]);

        $this->info('Service mapping completed.');
    }
}
