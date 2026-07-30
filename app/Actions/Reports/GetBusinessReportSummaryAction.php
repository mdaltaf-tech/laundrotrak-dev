<?php

namespace App\Actions\Reports;

use App\DTO\BusinessReportSummary;
use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;

class GetBusinessReportSummaryAction
{
    public function execute(string $month): BusinessReportSummary
    {
        $reportMonth = Carbon::createFromFormat('Y-m', $month);

        $start = $reportMonth->copy()->startOfMonth();
        $end   = $reportMonth->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Payments
        |--------------------------------------------------------------------------
        */

        $payments = Payment::query()
            ->active()
            ->whereBetween('payment_date', [$start, $end])
            ->get();

        $totalCollection = $payments->sum('received_amount');

        $cashCollection = $payments
            ->where('payment_type', Payment::PAYMENT_CASH)
            ->sum('received_amount');

        $upiCollection = $payments
            ->where('payment_type', Payment::PAYMENT_UPI)
            ->sum('received_amount');

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::query()
            ->whereBetween('expense_date', [$start, $end])
            ->sum('expense_amount');

        /*
        |--------------------------------------------------------------------------
        | Withdrawals
        |--------------------------------------------------------------------------
        */

        $withdrawals = CashRegister::query()
            ->whereBetween('business_date', [$start, $end])
            ->sum('withdraw_amount');

        /*
        |--------------------------------------------------------------------------
        | Closed Days
        |--------------------------------------------------------------------------
        */

        $closedDays = CashRegister::query()
            ->whereBetween('business_date', [$start, $end])
            ->whereNotNull('closing_cash')
            ->count();

        return new BusinessReportSummary(
            totalCollection: $totalCollection,
            cashCollection: $cashCollection,
            upiCollection: $upiCollection,
            expenses: $expenses,
            withdrawals: $withdrawals,
            closedDays: $closedDays,
            totalDays: $reportMonth->daysInMonth,
        );
    }
}
