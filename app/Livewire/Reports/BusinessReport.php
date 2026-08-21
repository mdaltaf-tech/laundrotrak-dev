<?php

namespace App\Livewire\Reports;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BusinessReportExport;
use App\Models\CashRegister;
use App\Services\Reports\CashRegisterService;
use App\Actions\Reports\GetBusinessReportSummaryAction;
use App\DTO\BusinessReportSummary;
use App\Models\BusinessDayClosure;
use Carbon\Carbon;


class BusinessReport extends Component
{
    public string $month;

    public array $rows = [];

    public array $totals = [];

    /*
    |--------------------------------------------------------------------------
    | Reconciliation Modal
    |--------------------------------------------------------------------------
    */

    public bool $showReconcileModal = false;

    public ?string $selectedDate = null;

    public float $expectedDrawerCash = 0;

    public float $cashRemoved = 0;

    public ?float $countedCash = null;

    public float $difference = 0;

    public ?string $remarks = null;

    public bool $isClosed = false;

    public float $openingCash = 0;

    public float $cashCollection = 0;

    public float $expenseAmount = 0;

    public float $upiCollection = 0;

    public ?string $closedBy = null;

    public ?string $closedAt = null;

    public bool $closingCompleted = false;

    public ?string $differenceReason = null;

    public ?string $receiptNumber = null;

    public ?int $cashRegisterId = null;

    public ?int $businessDayClosureId = null;

    public bool $isReadOnly = false;

    public ?BusinessDayClosure $businessDayClosure = null;

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->month = now()->format('Y-m');
        $this->loadReport();
    }

    public function updatedMonth()
    {
        $this->loadReport();
    }

    /*
    |--------------------------------------------------------------------------
    | Load Report
    |--------------------------------------------------------------------------
    */

    public function loadReport()
    {
        $report = app(CashRegisterService::class)
            ->getMonthlyRegister(
                Carbon::createFromFormat(
                    'Y-m',
                    $this->month
                )
            );

        $this->rows = $report['rows'];

        $this->totals = $report['totals'];
    }

    /*
    |--------------------------------------------------------------------------
    | Open Reconciliation
    |--------------------------------------------------------------------------
    */

    public function reconcile($date)
{
    try {
        $this->selectedDate = $date;

        // Reset modal state for the selected day
        $this->closingCompleted = false;
        $this->receiptNumber = null;
        $this->cashRegisterId = null;
        $this->businessDayClosureId = null;
        $this->businessDayClosure = null;
        $this->isReadOnly = false;
        $this->closedBy = '-';
        $this->closedAt = '-';

        $row = collect($this->rows)
            ->firstWhere('business_date', $date);

        if (!$row) {
            return;
        }

        $summary = app(
            \App\Domain\BusinessDay\BusinessDaySummaryService::class
        )->get($date);

        /*
        |--------------------------------------------------------------------------
        | Load Summary
        |--------------------------------------------------------------------------
        */

        $this->openingCash = $summary->openingCash;
        $this->cashCollection = $summary->cashCollection;
        $this->upiCollection = $summary->upiCollection;
        $this->expenseAmount = $summary->expenseAmount;
        $this->cashRemoved = $summary->withdrawAmount;
        $this->expectedDrawerCash = $summary->expectedCash;
        $this->countedCash = $summary->countedCash;

        $this->remarks = $summary->remarks;

        /*
        |--------------------------------------------------------------------------
        | Load Existing Register / Closure
        |--------------------------------------------------------------------------
        */

        $register = CashRegister::whereDate(
            'business_date',
            $date
        )->first();

        if ($register) {

            $this->cashRegisterId = $register->id;

            // Existing closing register number
            $this->receiptNumber = $register->receipt_no;

            $this->businessDayClosure = BusinessDayClosure::with([
                'closedBy',
                'cashRegister',
            ])
                ->where('cash_register_id', $register->id)
                ->first();

            if ($this->businessDayClosure) {

                $this->businessDayClosureId =
                    $this->businessDayClosure->id;

                $this->isReadOnly = true;
                $this->isClosed = true;

                $this->closedBy =
                    $this->businessDayClosure->closedBy?->name ?? '-';

                $this->closedAt =
                    $this->businessDayClosure->closed_at
                        ? Carbon::parse(
                            $this->businessDayClosure->closed_at
                        )->format('d M Y, h:i A')
                        : '-';

                // Use the immutable closure snapshot
                $this->cashRemoved =
                    (float) $this->businessDayClosure->withdraw_amount;

                $this->countedCash =
                    (float) $this->businessDayClosure->counted_cash;

                $this->difference =
                    (float) $this->businessDayClosure->difference_amount;

                $this->remarks =
                    $this->businessDayClosure->remarks;

            } else {

                $this->isReadOnly = false;
                $this->isClosed = false;

                $this->calculateTotals();
            }

        } else {

            $this->isReadOnly = false;
            $this->isClosed = false;

            $this->calculateTotals();
        }

        /*
        |--------------------------------------------------------------------------
        | Open Modal
        |--------------------------------------------------------------------------
        */

        $this->showReconcileModal = true;

    } catch (\Throwable $e) {

        dd(
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
    }
}

    /*
    |--------------------------------------------------------------------------
    | Live Difference Calculation
    |--------------------------------------------------------------------------
    */

    public function updatedCountedCash($value): void
    {
        $this->calculateTotals();
    }

    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */

    public function saveReconciliation()
    {
        if ($this->isReadOnly) {
            session()->flash(
                'error',
                'This business day has already been closed.'
            );
            return;
        }

        if (
            BusinessDayClosure::where(
                'cash_register_id',
                $this->cashRegisterId
            )->exists()
        ) {
            $this->isReadOnly = true;

            session()->flash(
                'error',
                'This business day has already been closed.'
            );

            return;
        }

        $this->validate([
            'cashRemoved'   => 'required|numeric|min:0',
            'countedCash'      => 'required|numeric|min:0',
            'differenceReason' => $this->difference != 0 ? 'required' : 'nullable',
            'remarks'          => 'nullable|string|max:500',
        ]);

        $summary = app(\App\Domain\BusinessDay\BusinessDaySummaryService::class)
            ->get($this->selectedDate);

        /*
        |--------------------------------------------------------------------------
        | Apply User Inputs
        |--------------------------------------------------------------------------
        */

        $summary->withdrawAmount  = $this->cashRemoved;
        $summary->countedCash = $this->countedCash;
        $summary->remarks = $this->remarks;
        $summary->differenceReason = $this->differenceReason;

        /*
        |--------------------------------------------------------------------------
        | Recalculate
        |--------------------------------------------------------------------------
        */

        $summary->expectedCash =
            $summary->openingCash
            + $summary->cashCollection
            - $summary->expenseAmount
            - $summary->withdrawAmount;

        $summary->difference =
            $summary->countedCash
            - $summary->expectedCash;

        /*
        |--------------------------------------------------------------------------
        | Close Register
        |--------------------------------------------------------------------------
        */

        $result = app(\App\Actions\CashRegister\CloseCashRegisterAction::class)
            ->execute(
                $summary,
                auth()->id()
            );

        $this->cashRegisterId = $result->cashRegister->id;

        $this->receiptNumber = $result->cashRegister->receipt_no;

        $this->businessDayClosureId = $result->closure->id;

        $this->closingCompleted = true;

        $this->loadReport();

        $this->businessDayClosure = $result->closure;

        $this->isReadOnly = true;

        $this->isClosed = true;

        session()->flash(
            'success',
            'Business day closed successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Close Modal
    |--------------------------------------------------------------------------
    */

    public function closeModal()
    {
        $this->reset([
            'showReconcileModal',
            'selectedDate',
            'expectedDrawerCash',
            'cashRemoved',
            'countedCash',
            'difference',
            'remarks',
            'differenceReason',
            'isClosed',

            // Success state
            'closingCompleted',
            'receiptNumber',
            'cashRegisterId',
            'businessDayClosureId',
            'isReadOnly',
            'businessDayClosure',
        ]);

        $this->showReconcileModal = false;
    }

    public function getStatusTextProperty()
    {
        if ($this->difference == 0) return 'Balanced';
        if ($this->difference > 0) return 'Extra Cash';
        return 'Cash Short';
    }

    public function getStatusClassProperty()
    {
        if ($this->difference == 0) return 'success';
        if ($this->difference > 0) return 'info';
        return 'danger';
    }

    public function getStatusMessageProperty()
    {
        if ($this->difference == 0)
            return 'Cash counted exactly matches the expected drawer cash.';
        if ($this->difference > 0)
            return 'Physical cash is higher than the expected drawer cash.';
        return 'Physical cash is lower than the expected drawer cash.';
    }

    #[Title('Business Report')]
    public function render()
    {
        return view('livewire.reports.business-report');
    }

    public function updatedCashRemoved($value)
    {
        $this->calculateTotals();
    }

    public function export()
    {
        $filename = 'business-day-register-' . $this->month . '.xlsx';

        return Excel::download(
            new BusinessReportExport(
                $this->rows,
                $this->month
            ),
            $filename
        );
    }

    private function calculateTotals(): void
    {
        $this->expectedDrawerCash =
            $this->openingCash
            + $this->cashCollection
            - $this->expenseAmount
            - $this->cashRemoved;

        $this->difference =
            ($this->countedCash ?? 0)
            - $this->expectedDrawerCash;
    }

    #[Computed]
    public function summary(): BusinessReportSummary
    {
        return app(GetBusinessReportSummaryAction::class)
            ->execute($this->month);
    }
}
