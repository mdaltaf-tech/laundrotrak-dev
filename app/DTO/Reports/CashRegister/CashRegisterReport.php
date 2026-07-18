<?php

namespace App\DTO\Reports\CashRegister;

use Illuminate\Support\Collection;

class CashRegisterReport
{
    /**
     * @param Collection<int, CashRegisterRow> $rows
     */
    public function __construct(
        public Collection $rows,
        public CashRegisterTotals $totals,
    ) {
    }
}
