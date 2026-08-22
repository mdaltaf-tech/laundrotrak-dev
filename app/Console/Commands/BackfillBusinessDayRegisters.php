<?php

namespace App\Console\Commands;

use App\Models\BusinessDayClosure;
use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BackfillBusinessDayRegisters extends Command
{
    /**
     * --------------------------------------------------------------------------
     * Command
     * --------------------------------------------------------------------------
     *
     * Historical period:
     *
     * 06-Nov-2025 → 30-Jul-2026
     *
     * July 31 will be handled manually.
     *
     */
    protected $signature = 'business-day:backfill
                            {--from=2025-11-06 : Start date}
                            {--to=2026-07-30 : End date}';

    protected $description = 'Backfill historical cash registers and business day closures';

    /**
     * --------------------------------------------------------------------------
     * Handle
     * --------------------------------------------------------------------------
     */
    public function handle(): int
    {
        $from = Carbon::parse($this->option('from'))->startOfDay();
        $to = Carbon::parse($this->option('to'))->startOfDay();

        /*
        |--------------------------------------------------------------------------
        | Historical Admin User
        |--------------------------------------------------------------------------
        |
        | User ID 1 = Admin.
        |
        */

        $admin = User::find(1);

        if (!$admin) {
            $this->error('Admin user with ID 1 was not found.');

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Date Range
        |--------------------------------------------------------------------------
        */

        if ($from->gt($to)) {
            $this->error('The start date cannot be after the end date.');

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Display Information
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Backfilling cash registers and business day closures'
        );

        $this->line(
            "Period: {$from->format('d-M-Y')} → {$to->format('d-M-Y')}"
        );

        $this->line(
            'Historical withdrawal: ₹0.00'
        );

        $this->line(
            'Historical closer: Admin (User ID 1)'
        );

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Load Source Data
        |--------------------------------------------------------------------------
        */

        $payments = $this->getPayments($from, $to);

        $expenses = $this->getExpenses($from, $to);

        /*
        |--------------------------------------------------------------------------
        | Opening Cash
        |--------------------------------------------------------------------------
        |
        | If there is an existing register before the selected period,
        | use its closing cash.
        |
        | Since we are doing a clean historical backfill, this will normally
        | return 0 for the first run.
        |
        */

        $openingCash = $this->getOpeningCash($from);

        /*
        |--------------------------------------------------------------------------
        | Counters
        |--------------------------------------------------------------------------
        */

        $createdRegisters = 0;

        $createdClosures = 0;

        /*
        |--------------------------------------------------------------------------
        | Process
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $from,
            $to,
            $payments,
            $expenses,
            $openingCash,
            $admin,
            &$createdRegisters,
            &$createdClosures
        ) {

            $currentOpeningCash = $openingCash;

            $date = $from->copy();

            while ($date->lte($to)) {

                $businessDate = $date->toDateString();

                /*
                |--------------------------------------------------------------------------
                | Daily Source Data
                |--------------------------------------------------------------------------
                */

                $payment = $payments->get($businessDate);

                $expense = $expenses->get($businessDate);

                /*
                |--------------------------------------------------------------------------
                | Collections
                |--------------------------------------------------------------------------
                */

                $cashCollection = (float) ($payment->cash ?? 0);

                $upiCollection = (float) ($payment->upi ?? 0);

                /*
                |--------------------------------------------------------------------------
                | Expenses
                |--------------------------------------------------------------------------
                */

                $expenseAmount = (float) (
                    $expense->expense_amount ?? 0
                );

                /*
                |--------------------------------------------------------------------------
                | Historical Withdrawal
                |--------------------------------------------------------------------------
                |
                | We intentionally keep this ZERO.
                |
                | The accumulated historical cash will be withdrawn manually
                | on July 31.
                |
                */

                $withdrawAmount = 0.00;

                /*
                |--------------------------------------------------------------------------
                | Expected Cash
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                |
                | Expenses are NOT deducted here.
                |
                | Our business-report cash logic is:
                |
                | Opening Cash
                | + Cash Collection
                | - Withdrawal
                | = Expected Cash
                |
                */

                $expectedCash =
                    $currentOpeningCash
                    + $cashCollection
                    - $withdrawAmount;

                /*
                |--------------------------------------------------------------------------
                | Historical Closing Cash
                |--------------------------------------------------------------------------
                |
                | We don't have physical historical cash-count records.
                |
                | Therefore, for the backfill:
                |
                | Counted Cash = Expected Cash
                |
                | Difference = 0
                |
                */

                $countedCash = $expectedCash;

                $differenceAmount = 0.00;

                /*
                |--------------------------------------------------------------------------
                | Create Cash Register
                |--------------------------------------------------------------------------
                */
                $cashRegister = CashRegister::updateOrCreate(
                    [
                        'business_date' => $businessDate,
                    ],
                    [
                        'withdraw_amount' => $withdrawAmount,
                        'closing_cash' => $countedCash,
                        'reconciled_at' => $date->copy()->endOfDay(),
                        'remarks' => 'Historical backfill',
                        'is_closed' => true,
                        'created_by' => $admin->id,
                        'updated_by' => $admin->id,
                    ]
                );

                // Generate the historical closing register receipt number
                // using the actual cash register ID.
                if (blank($cashRegister->receipt_no)) {
                    $cashRegister->receipt_no = sprintf(
                        'CLS%s%05d',
                        $date->format('ymd'),
                        $cashRegister->id
                    );
                    $cashRegister->save();
                }

                /*
                |--------------------------------------------------------------------------
                | Create Business Day Closure
                |--------------------------------------------------------------------------
                */

                BusinessDayClosure::updateOrCreate(
                    [
                        'business_date' => $businessDate,
                    ],
                    [
                        'cash_register_id' => $cashRegister->id,

                        'opening_cash' => $currentOpeningCash,

                        'cash_collection' => $cashCollection,

                        'upi_collection' => $upiCollection,

                        /*
                        |--------------------------------------------------------------------------
                        | These payment types are currently not being retrieved
                        | by getPayments(), so keep them at zero rather than
                        | inventing historical values.
                        |--------------------------------------------------------------------------
                        */

                        'card_collection' => 0.00,

                        'wallet_collection' => 0.00,

                        'other_collection' => 0.00,

                        /*
                        |--------------------------------------------------------------------------
                        | Expenses are stored separately.
                        |--------------------------------------------------------------------------
                        */

                        'expense_amount' => $expenseAmount,

                        'withdraw_amount' => $withdrawAmount,

                        'expected_cash' => $expectedCash,

                        'counted_cash' => $countedCash,

                        'difference_amount' => $differenceAmount,

                        'difference_reason' => null,

                        'remarks' => 'Historical backfill',

                        'closed_by' => $admin->id,

                        'closed_at' => $date->copy()->endOfDay(),
                    ]
                );

                $createdRegisters++;

                $createdClosures++;

                /*
                |--------------------------------------------------------------------------
                | Next Day Opening
                |--------------------------------------------------------------------------
                */

                $currentOpeningCash = $countedCash;

                /*
                |--------------------------------------------------------------------------
                | Next Date
                |--------------------------------------------------------------------------
                */

                $date->addDay();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('Historical backfill completed successfully.');

        $this->newLine();

        $this->table(
            [
                'Created Registers',
                'Created Closures',
                'Historical Withdrawal',
                'From',
                'To',
            ],
            [
                [
                    $createdRegisters,
                    $createdClosures,
                    '₹0.00',
                    $from->format('d-M-Y'),
                    $to->format('d-M-Y'),
                ],
            ]
        );

        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * --------------------------------------------------------------------------
     * Payments
     * --------------------------------------------------------------------------
     *
     * Returns daily Cash and UPI collections.
     */
    private function getPayments(
        Carbon $start,
        Carbon $end
    ): Collection {
        return Payment::active()
            ->whereBetween('payment_date', [
                $start,
                $end,
            ])
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
            ->groupByRaw('DATE(payment_date)')
            ->get()
            ->keyBy('business_date');
    }

    /**
     * --------------------------------------------------------------------------
     * Expenses
     * --------------------------------------------------------------------------
     *
     * Returns total expenses for each business date.
     */
    private function getExpenses(
        Carbon $start,
        Carbon $end
    ): Collection {
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
     * Opening Cash
     * --------------------------------------------------------------------------
     *
     * Finds the last known closing cash before the backfill period.
     */
    private function getOpeningCash(
        Carbon $periodStart
    ): float {
        $lastRegister = CashRegister::query()
            ->where(
                'business_date',
                '<',
                $periodStart->toDateString()
            )
            ->whereNotNull('closing_cash')
            ->orderByDesc('business_date')
            ->first();

        if (!$lastRegister) {
            return 0.00;
        }

        return (float) $lastRegister->closing_cash;
    }
}

// php artisan business-day:backfill --from=2025-11-06 --to=2026-07-30 --user=YOUR_USER_ID
