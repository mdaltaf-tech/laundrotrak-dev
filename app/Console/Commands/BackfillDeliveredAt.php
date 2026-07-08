<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderArticle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillDeliveredAt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:backfill-delivered-at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill delivered_at for Orders and Order Articles';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting delivered_at backfill...');

        /*
        |--------------------------------------------------------------------------
        | Step 1 - Backfill Orders
        |--------------------------------------------------------------------------
        */

        $orderCount = Order::where('status', Order::STATUS_DELIVERED)
            ->whereNull('delivered_at')
            ->update([
                'delivered_at' => DB::raw('updated_at'),
            ]);

        $this->info("Orders updated   : {$orderCount}");

        /*
        |--------------------------------------------------------------------------
        | Step 2 - Backfill Order Articles
        |--------------------------------------------------------------------------
        */

        $articleCount = 0;

        Order::with('articles')
            ->whereNotNull('delivered_at')
            ->chunkById(100, function ($orders) use (&$articleCount) {

                foreach ($orders as $order) {

                    $updated = $order->articles()
                        ->whereNull('delivered_at')
                        ->update([
                            'status'       => OrderArticle::STATUS_DELIVERED,
                            'delivered_at' => $order->delivered_at,
                        ]);

                    $articleCount += $updated;
                }
            });

        $this->info("Articles updated : {$articleCount}");

        $this->info('Delivered_at backfill completed successfully.');

        return self::SUCCESS;
    }
}
