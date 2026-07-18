<?php

namespace App\DTO\Reports;

class CashRegisterRow
{
    public function __construct(
        public string $businessDate,

        public int $orders,

        public float $sales,

        public float $cash,

        public float $upi,

        public float $collection,

        public float $expenses,

        public float $withdraw,

        public float $openingCash,

        public float $expectedClosing,

        public ?float $closingCash,

        public float $extraCash,

        public float $lessCash,

        public string $remarks,

        public bool $isClosed,
    ) {
    }
}
