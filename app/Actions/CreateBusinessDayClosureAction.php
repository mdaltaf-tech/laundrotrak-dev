<?php

namespace App\Actions;

use App\Domain\BusinessDay\BusinessDaySummary;
use App\Models\BusinessDayClosure;
use App\Models\CashRegister;

class CreateBusinessDayClosureAction
{
    /**
     * Persist an immutable Business Day Closure snapshot.
     */
    public function execute(
        CashRegister $cashRegister,
        BusinessDaySummary $summary
    ): BusinessDayClosure {

        return BusinessDayClosure::create([

            'cash_register_id' => $cashRegister->id,
            'business_date'    => $summary->businessDate,

            /*
            |--------------------------------------------------------------------------
            | Financial Snapshot
            |--------------------------------------------------------------------------
            */

            'opening_cash'      => $summary->openingCash,
            'cash_collection'   => $summary->cashCollection,
            'upi_collection'    => $summary->upiCollection,
            'card_collection'   => $summary->cardCollection,
            'wallet_collection' => $summary->walletCollection,
            'other_collection'  => $summary->otherCollection,

            'expense_amount'    => $summary->expenseAmount,
            'withdraw_amount'   => $summary->withdrawAmount,

            'expected_cash'     => $summary->expectedCash,
            'counted_cash'      => $summary->countedCash,
            'difference_amount' => $summary->difference,

            /*
            |--------------------------------------------------------------------------
            | Reconciliation
            |--------------------------------------------------------------------------
            */

            'difference_reason' => $summary->differenceReason,
            'remarks'           => $summary->remarks,

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'closed_by'         => auth()->id(),
            'closed_at'         => now(),

        ]);
    }
}
