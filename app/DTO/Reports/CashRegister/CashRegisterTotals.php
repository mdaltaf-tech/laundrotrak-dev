<?php

namespace App\DTO\Reports;

class CashRegisterTotals
{
    public function __construct(
        public int $orders,

        public float $sales,

        public float $cash,

        public float $upi,

        public float $collection,

        public float $expenses,

        public float $withdraw,

        public float $extraCash,

        public float $lessCash,

        public ?float $closingCash,
    ) {
    }
}
