<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderArticle;

class GenerateArticlesForOldOrders extends Command
{
    protected $signature =
        'articles:backfill';

    protected $description =
        'Generate article records for old orders';

    public function handle()
    {
        $this->info(
            'Starting article generation...'
        );

        $orders = Order::active()
            ->whereDoesntHave(
                'articles'
            )
            ->with('details')
            ->get();

        $createdCount = 0;

        foreach ($orders as $order)
        {
            $this->line(
                "Processing "
                .$order->order_number
            );

            foreach (
                $order->details as $detail
            )
            {
                for(
                    $i=1;
                    $i<=$detail->service_quantity;
                    $i++
                )
                {
                    OrderArticle::create([

                        'order_id'=>
                        $order->id,

                        'order_detail_id'=>
                        $detail->id,

                        'tag_number'=>
                        $this->generateTag(
                            $order->order_number
                        ),

                        'article_name'=>
                        $detail->service_name,

                        'service_name'=>
                        $detail->service_name,

                        'color_code'=>
                        $detail->color_code,

                        'status'=>
                        OrderArticle::STATUS_RECEIVED,

                        'created_by'=>
                        $order->created_by
                    ]);

                    $createdCount++;
                }
            }
        }

        $this->info(
            "Completed. {$createdCount} articles created."
        );

        return Command::SUCCESS;
    }

    private function generateTag(
        $orderNumber
    )
    {
        $orderNo =
        preg_replace(
            '/[^0-9]/',
            '',
            $orderNumber
        );

        $lastTag =
        OrderArticle::where(
            'tag_number',
            'like',
            'FBL'.$orderNo.'-%'
        )
        ->latest('id')
        ->first();

        $next = 1;

        if($lastTag)
        {
            preg_match(
                '/(\d+)$/',
                $lastTag->tag_number,
                $match
            );

            $next =
            ((int)$match[1]) + 1;
        }

        return 'FBL'
            .$orderNo
            .'-'
            .str_pad(
                $next,
                3,
                '0',
                STR_PAD_LEFT
            );
    }
}
