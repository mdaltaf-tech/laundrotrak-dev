<?php

namespace App\Services;

use App\Models\DailyCashRegister;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

class BusinessReportService
{
    public function getMonthlyRegister(Carbon $month): array
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        $orders = Order::active()
            ->whereBetween('order_date', [$start, $end])
            ->selectRaw("
                DATE(order_date) as business_date,
                COUNT(*) as orders,
                SUM(total) as sales
            ")
            ->groupByRaw("DATE(order_date)")
            ->get()
            ->keyBy('business_date');

        /*
        |--------------------------------------------------------------------------
        | Payments
        |--------------------------------------------------------------------------
        */

        $payments = Payment::active()
            ->whereBetween('payment_date', [$start, $end])
            ->selectRaw("
                DATE(payment_date) as business_date,

                SUM(
                    CASE
                        WHEN payment_type = " . Payment::PAYMENT_CASH . "
                        THEN received_amount
                        ELSE 0
                    END
                ) as cash,

                SUM(
                    CASE
                        WHEN payment_type = " . Payment::PAYMENT_UPI . "
                        THEN received_amount
                        ELSE 0
                    END
                ) as upi
            ")
            ->groupByRaw("DATE(payment_date)")
            ->get()
            ->keyBy('business_date');

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::query()
            ->whereBetween('expense_date', [
                $start->toDateString(),
                $end->toDateString()
            ])
            ->selectRaw("
                expense_date as business_date,
                SUM(expense_amount) as expenses
            ")
            ->groupBy('expense_date')
            ->get()
            ->keyBy('business_date');

        /*
        |--------------------------------------------------------------------------
        | Manual Register Entries
        |--------------------------------------------------------------------------
        */

        $manual = DailyCashRegister::query()
            ->whereBetween('business_date', [
                $start->toDateString(),
                $end->toDateString()
            ])
            ->get()
            ->keyBy(function ($row) {
                return $row->business_date->format('Y-m-d');
            });

        /*
        |--------------------------------------------------------------------------
        | Build Daily Register
        |--------------------------------------------------------------------------
        */

        $rows = [];

        $openingCash = 0;

        while ($start <= $end) {

            $date = $start->format('Y-m-d');

            $order = $orders[$date] ?? null;
            $payment = $payments[$date] ?? null;
            $expense = $expenses[$date] ?? null;
            $entry = $manual[$date] ?? null;

            $cash = (float)($payment->cash ?? 0);
            $upi = (float)($payment->upi ?? 0);

            $sales = (float)($order->sales ?? 0);
            $orderCount = (int)($order->orders ?? 0);

            $expenseAmount = (float)($expense->expenses ?? 0);

            $withdraw = (float)($entry->withdraw_amount ?? 0);

            $expectedClosing =
                $openingCash
                + $cash
                - $expenseAmount
                - $withdraw;

            $closingCash = $entry?->closing_cash;

            $extraCash = 0;
            $lessCash = 0;

            if (!is_null($closingCash)) {

                if ($closingCash > $expectedClosing) {

                    $extraCash = $closingCash - $expectedClosing;

                } elseif ($closingCash < $expectedClosing) {

                    $lessCash = $expectedClosing - $closingCash;
                }

                $nextOpening = $closingCash;

            } else {

                $nextOpening = $expectedClosing;
            }

            $rows[] = [

                'date' => $date,

                'orders' => $orderCount,

                'sales' => round($sales, 2),

                'cash' => round($cash, 2),

                'upi' => round($upi, 2),

                'collection' => round($cash + $upi, 2),

                'expenses' => round($expenseAmount, 2),

                'withdraw' => round($withdraw, 2),

                'opening_cash' => round($openingCash, 2),

                'expected_closing_cash' => round($expectedClosing, 2),

                'closing_cash' => is_null($closingCash)
                    ? null
                    : round($closingCash, 2),

                'extra_cash' => round($extraCash, 2),

                'less_cash' => round($lessCash, 2),

                'remarks' => $entry->remarks ?? '',

            ];

            $openingCash = $nextOpening;

            $start->addDay();
        }

        return [

            'daily_register' => [

                'rows' => $rows,

                'totals' => [

                    'orders' => collect($rows)->sum('orders'),

                    'sales' => collect($rows)->sum('sales'),

                    'cash' => collect($rows)->sum('cash'),

                    'upi' => collect($rows)->sum('upi'),

                    'collection' => collect($rows)->sum('collection'),

                    'expenses' => collect($rows)->sum('expenses'),

                    'withdraw' => collect($rows)->sum('withdraw'),

                    'extra_cash' => collect($rows)->sum('extra_cash'),

                    'less_cash' => collect($rows)->sum('less_cash'),

                    'closing_cash' => end($rows)['closing_cash'] ?? 0,

                ],
            ],

            'fixed_expenses' => [],

            'electricity' => [],

            'summary' => [],

        ];
    }
}
