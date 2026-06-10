<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;

class RecalculateOrderFinancials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recalculate:financials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate paid amount, balance amount and payment status for all orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting financial recalculation...');

        $orders = Order::active()->get();

        $bar = $this->output->createProgressBar(
            $orders->count()
        );

        $bar->start();

        foreach ($orders as $order) {

            $paidAmount = Payment::active()
                ->where(
                    'order_id',
                    $order->id
                )
                ->sum(
                    'received_amount'
                );

            $balanceAmount = max(
                0,
                $order->total - $paidAmount
            );

            if ($paidAmount <= 0) {

                $paymentStatus = Order::PAYMENT_UNPAID;

            } elseif ($balanceAmount > 0) {

                $paymentStatus = Order::PAYMENT_PARTIAL;

            } else {

                $paymentStatus = Order::PAYMENT_PAID;

            }

            $order->update([
                'paid_amount' => $paidAmount,
                'balance_amount' => $balanceAmount,
                'payment_status' => $paymentStatus,
            ]);

            $bar->advance();
        }

        $bar->finish();

        $this->newLine(2);

        $this->info(
            'Financial recalculation completed successfully.'
        );

        return Command::SUCCESS;
    }
}
