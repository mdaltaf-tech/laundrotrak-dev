<?php

namespace App\Domain\BusinessDay;

class BusinessDaySummary
{
    public function __construct(
        public string $businessDate,

        public float $openingCash,
        public float $cashCollection,

        public float $upiCollection = 0,
        public float $cardCollection = 0,
        public float $walletCollection = 0,
        public float $otherCollection = 0,

        public float $expenseAmount = 0,
        public float $withdrawAmount = 0,

        public float $expectedCash = 0,
        public float $countedCash = 0,

        public float $difference = 0,

        public ?string $differenceReason = null,
        public ?string $remarks = null,
    ) {
    }
}
