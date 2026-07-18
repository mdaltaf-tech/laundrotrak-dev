<?php

namespace App\Actions\CashRegister;

use App\Actions\CreateBusinessDayClosureAction;
use App\Domain\CashRegister\CloseCashRegisterResult;
use App\Domain\BusinessDay\BusinessDaySummary;
use App\Models\CashRegister;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CloseCashRegisterAction
{
    /**
     * Close / Reconcile cash for a business day.
     */
    public function execute(
        BusinessDaySummary $summary,
        int $userId
    ): CloseCashRegisterResult {

        return DB::transaction(function () use ($summary, $userId) {

            $businessDate   = $summary->businessDate;
            $withdrawAmount = $summary->withdrawAmount;
            $closingCash    = $summary->countedCash;
            $remarks        = $summary->remarks;

            if ($businessDate instanceof Carbon) {
                $businessDate = $businessDate->toDateString();
            }

            $register = CashRegister::firstOrNew([
                'business_date' => $businessDate,
            ]);

            if (! $register->exists) {
                $register->created_by = $userId;
            }

            $register->withdraw_amount = $withdrawAmount;
            $register->closing_cash    = $closingCash;
            $register->remarks         = $remarks;
            $register->is_closed       = true;
            $register->reconciled_at ??= now();
            $register->updated_by      = $userId;

            $register->save();

            /*
            |--------------------------------------------------------------------------
            | Generate Closing Slip Number
            |--------------------------------------------------------------------------
            */

            if (blank($register->receipt_no)) {

                $register->receipt_no = sprintf(
                    'CLS%s%05d',
                    now()->format('ymd'),
                    $register->id
                );

                $register->save();
            }

            /*
            |--------------------------------------------------------------------------
            | Create Immutable Business Day Closure
            |--------------------------------------------------------------------------
            */

            $closure = app(CreateBusinessDayClosureAction::class)
                ->execute($register, $summary);

            return new CloseCashRegisterResult(
                cashRegister: $register->fresh(),
                closure: $closure,
            );
        });
    }
}
