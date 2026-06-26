<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderDetail;
use App\Models\ServiceDetail;

class BackfillOrderDetailServiceTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:backfill-service-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate service_type_id for historical order details';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updated = 0;
        $notFound = 0;
        $ambiguous = 0;

        OrderDetail::whereNull('service_type_id')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use (&$updated, &$notFound, &$ambiguous) {

                foreach ($rows as $row) {
                    $matches = ServiceDetail::where(
                        'service_id',
                        $row->service_id
                    )
                    ->whereHas(
                        'serviceType',
                        fn($q) =>
                        $q->where(
                            'service_type_name',
                            $row->service_name
                        )
                    )
                    ->get();

                    if ($matches->count() === 1) {

                        $row->update([
                            'service_type_id' =>
                                $matches->first()->service_type_id
                        ]);

                        $updated++;

                    }
                    elseif ($matches->count() === 0) {

                        $notFound++;

                        $this->warn(
                            "No Match #{$row->id} | {$row->service_name}"
                        );
                    }
                    else {

                        $ambiguous++;

                        $this->warn(
                            "Ambiguous #{$row->id} | {$row->service_name}"
                        );
                    }
                }
            });

        $this->info("Updated: {$updated}");
        $this->info("Not Found: {$notFound}");
        $this->info("Ambiguous: {$ambiguous}");
        $this->info('Completed');
    }
}
