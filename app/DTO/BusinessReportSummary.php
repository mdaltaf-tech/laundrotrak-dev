<?php

namespace App\DTO;

class BusinessReportSummary
{
    public function __construct(
        public readonly float $totalCollection,
        public readonly float $cashCollection,
        public readonly float $upiCollection,
        public readonly float $expenses,
        public readonly float $withdrawals,
        public readonly int $closedDays,
        public readonly int $totalDays,
    ) {}
}
