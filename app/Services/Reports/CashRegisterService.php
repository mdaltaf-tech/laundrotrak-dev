<?php

namespace App\Services\Reports;

use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CashRegisterService
{
    /**
     * Generate monthly cash register.
     */
    public function getMonthlyRegister(Carbon $month): array
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $orders = $this->getOrders($start, $end);

        $payments = $this->getPayments($start, $end);

        $expenses = $this->getExpenses($start, $end);

        $registers = $this->getCashRegisters($start, $end);

        $rows = $this->buildRows(
            $start,
            $end,
            $orders,
            $payments,
            $expenses,
            $registers
        );

        return [
            'rows' => $rows,
            'totals' => $this->calculateTotals($rows),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Orders
     * --------------------------------------------------------------------------
     */
    private function getOrders(Carbon $start, Carbon $end): Collection
    {
        return Order::active()
            ->whereBetween('order_date', [$start, $end])
            ->selectRaw("
                DATE(order_date) as business_date,
                COUNT(*) as order_count,
                SUM(total) as sales
            ")
            ->groupByRaw("DATE(order_date)")
            ->get()
            ->keyBy('business_date');
    }

    /**
     * --------------------------------------------------------------------------
     * Payments
     * --------------------------------------------------------------------------
     */
    private function getPayments(Carbon $start, Carbon $end): Collection
    {
        return Payment::active()
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
    }

    /**
     * --------------------------------------------------------------------------
     * Expenses
     * --------------------------------------------------------------------------
     */
    private function getExpenses(Carbon $start, Carbon $end): Collection
    {
        return Expense::query()
            ->whereBetween('expense_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->selectRaw("
                expense_date as business_date,
                SUM(expense_amount) as expense_amount
            ")
            ->groupBy('expense_date')
            ->get()
            ->keyBy('business_date');
    }

    /**
     * --------------------------------------------------------------------------
     * Cash Register Entries
     * --------------------------------------------------------------------------
     */
    private function getCashRegisters(Carbon $start, Carbon $end): Collection
    {
        return CashRegister::query()
            ->whereBetween('business_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->get()
            ->keyBy(function ($row) {
                return $row->business_date->format('Y-m-d');
            });
    }

    /**
     * --------------------------------------------------------------------------
     * Build Daily Rows
     * --------------------------------------------------------------------------
     */
    private function buildRows(
        Carbon $start,
        Carbon $end,
        Collection $orders,
        Collection $payments,
        Collection $expenses,
        Collection $registers
    ): array {

        $rows = [];

        $openingCash = $this->getOpeningCash($start);

        $date = $start->copy();

        while ($date <= $end) {

            $key = $date->format('Y-m-d');

            $order = $orders->get($key);

            $payment = $payments->get($key);

            $expense = $expenses->get($key);

            $register = $registers->get($key);

            $cash = (float) ($payment->cash ?? 0);

            $upi = (float) ($payment->upi ?? 0);

            $expenseAmount = (float) ($expense->expense_amount ?? 0);

            $withdraw = (float) ($register->withdraw_amount ?? 0);

            $expectedClosing = $openingCash
                + $cash
                - $expenseAmount
                - $withdraw;

            $closingCash = $register->closing_cash ?? null;

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

                'business_date' => $key,

                'orders' => (int) ($order->order_count ?? 0),

                'sales' => (float) ($order->sales ?? 0),

                'cash' => $cash,

                'upi' => $upi,

                'collection' => $cash + $upi,

                'expenses' => $expenseAmount,

                'withdraw' => $withdraw,

                'opening_cash' => $openingCash,

                'expected_closing' => $expectedClosing,

                'closing_cash' => $closingCash,

                'extra_cash' => $extraCash,

                'less_cash' => $lessCash,

                'remarks' => $register->remarks ?? '',
            ];

            $openingCash = $nextOpening;

            $date->addDay();
        }

        return $rows;
    }

    /**
     * --------------------------------------------------------------------------
     * Totals
     * --------------------------------------------------------------------------
     */
    private function calculateTotals(array $rows): array
    {
        $collection = collect($rows);

        return [

            'orders' => $collection->sum('orders'),

            'sales' => $collection->sum('sales'),

            'cash' => $collection->sum('cash'),

            'upi' => $collection->sum('upi'),

            'collection' => $collection->sum('collection'),

            'expenses' => $collection->sum('expenses'),

            'withdraw' => $collection->sum('withdraw'),

            'extra_cash' => $collection->sum('extra_cash'),

            'less_cash' => $collection->sum('less_cash'),

            'closing_cash' => optional($collection->last())['closing_cash'] ?? 0,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Get Opening Cash
     * --------------------------------------------------------------------------
     *
     * Returns previous available closing cash before the selected month.
     */
    private function getOpeningCash(Carbon $monthStart): float
    {
        $lastRegister = CashRegister::query()
            ->where('business_date', '<', $monthStart->toDateString())
            ->whereNotNull('closing_cash')
            ->orderByDesc('business_date')
            ->first();

        if (!$lastRegister) {
            return 0;
        }

        return (float) $lastRegister->closing_cash;
    }
}
