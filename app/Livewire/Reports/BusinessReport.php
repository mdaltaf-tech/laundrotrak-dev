<?php

namespace App\Livewire\Reports;

use App\Models\CashRegister;
use App\Services\Reports\CashRegisterService;
use Carbon\Carbon;
use Livewire\Component;

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

    public float $expectedClosing = 0;

    public float $withdrawAmount = 0;

    public ?float $closingCash = null;

    public float $difference = 0;

    public ?string $remarks = null;

    public bool $isClosed = false;

    public float $openingCash = 0;

    public float $cashCollection = 0;

    public float $expenseAmount = 0;

    public bool $closingCompleted = false;

    public ?string $differenceReason = null;

    public ?string $receiptNumber = null;

    public ?int $cashRegisterId = null;

    public ?int $businessDayClosureId = null;

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
        $this->selectedDate = $date;

        $row = collect($this->rows)
            ->firstWhere('business_date', $date);

        if (!$row) {
            return;
        }

        $register = CashRegister::firstOrNew([
            'business_date' => $date
        ]);

        $summary = app(\App\Domain\BusinessDay\BusinessDaySummaryService::class)
            ->get($date);

        $this->openingCash = $summary->openingCash;
        $this->cashCollection = $summary->cashCollection;
        $this->expenseAmount = $summary->expenseAmount;
        $this->withdrawAmount = $summary->withdrawAmount;
        $this->expectedClosing = $summary->expectedCash;
        $this->closingCash = $summary->countedCash;
        $this->difference = $summary->difference;

        $register = CashRegister::whereDate('business_date', $date)->first();

        $this->remarks = $summary->remarks;
        $this->isClosed = $register?->is_closed ?? false;

        $this->showReconcileModal = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Live Difference Calculation
    |--------------------------------------------------------------------------
    */

    public function updatedClosingCash($value): void
    {
        $this->closingCash = max(0, (float) $value);

        $this->recalculateTotals();
    }

    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */

    public function saveReconciliation()
    {
        $this->validate([
            'withdrawAmount'   => 'required|numeric|min:0',
            'closingCash'      => 'required|numeric|min:0',
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

        $summary->withdrawAmount = $this->withdrawAmount;
        $summary->countedCash = $this->closingCash;
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
            'expectedClosing',
            'withdrawAmount',
            'closingCash',
            'difference',
            'remarks',
            'isClosed'
        ]);

        $this->showReconcileModal = false;
    }

    public function render()
    {
        return view('livewire.reports.business-report-v2');
    }

    public function updatedWithdrawAmount($value): void
    {
        $this->withdrawAmount = max(0, (float) $value);

        $this->recalculateTotals();
    }

    private function calculateExpectedClosing()
    {
        $this->expectedClosing =
            $this->openingCash
            + $this->cashCollection
            - $this->expenseAmount
            - $this->withdrawAmount;
    }

    private function recalculateTotals(): void
    {
        $this->expectedClosing =
            $this->openingCash
            + $this->cashCollection
            - $this->expenseAmount
            - $this->withdrawAmount;

        $this->difference =
            ($this->closingCash ?? 0)
            - $this->expectedClosing;
    }
}
