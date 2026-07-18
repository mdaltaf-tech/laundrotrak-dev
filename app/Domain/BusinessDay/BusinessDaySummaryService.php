<?php

namespace App\Domain\BusinessDay;

use App\Models\CashRegister;
use App\Services\Reports\CashRegisterService;
use Carbon\Carbon;

class BusinessDaySummaryService
{
    public function get(string|\Carbon\Carbon $businessDate): BusinessDaySummary
    {
        $businessDate = Carbon::parse($businessDate)->toDateString();
        $month = Carbon::parse($businessDate)->startOfMonth();

        $report = app(CashRegisterService::class)
            ->getMonthlyRegister($month);

        $row = collect($report['rows'])
            ->firstWhere('business_date', $businessDate);

        if (! $row) {
            throw new \RuntimeException("Business day not found: {$businessDate}");
        }

        $cashRegister = CashRegister::whereDate('business_date', $businessDate)->first();

        $openingCash    = (float) ($row['opening_cash'] ?? 0);
        $cashCollection = (float) ($row['cash'] ?? 0);
        $expenseAmount  = (float) ($row['expenses'] ?? 0);

        // These can be replaced later with actual payment-method totals.
        $upiCollection    = (float) ($row['upi'] ?? 0);
        $cardCollection   = (float) ($row['card'] ?? 0);
        $walletCollection = (float) ($row['wallet'] ?? 0);
        $otherCollection  = (float) ($row['other'] ?? 0);

        $withdrawAmount = (float) ($cashRegister?->withdraw_amount ?? 0);
        $countedCash    = (float) ($cashRegister?->closing_cash ?? 0);

        $expectedCash = $openingCash
            + $cashCollection
            - $expenseAmount
            - $withdrawAmount;

        $difference = $countedCash - $expectedCash;

        return new BusinessDaySummary(
            businessDate: $businessDate,

            openingCash: $openingCash,
            cashCollection: $cashCollection,

            upiCollection: $upiCollection,
            cardCollection: $cardCollection,
            walletCollection: $walletCollection,
            otherCollection: $otherCollection,

            expenseAmount: $expenseAmount,
            withdrawAmount: $withdrawAmount,

            expectedCash: $expectedCash,
            countedCash: $countedCash,

            difference: $difference,

            differenceReason: null,
            remarks: $cashRegister?->remarks,
        );
    }
}
