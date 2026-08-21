<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BusinessReportExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected array $rows,
        protected string $month
    ) {
    }

    public function headings(): array
    {
        return [
            'Date',
            'Orders',
            'Sales',
            'Cash Collection',
            'UPI Collection',
            'Total Collection',
            'Expenses',
            'Cash Removed',
            'Opening Cash',
            'Expected Cash',
            'Counted Cash',
            'Difference',
            'Status',
        ];
    }

    public function array(): array
    {
        return collect($this->rows)
            ->map(function ($row) {

                $closingCash = $row['closing_cash'] !== null
                    ? (float) $row['closing_cash']
                    : null;

                $expectedCash = (float) $row['expected_closing'];

                $difference = $closingCash !== null
                    ? $closingCash - $expectedCash
                    : null;

                if ($closingCash === null) {
                    $status = 'OPEN';
                } elseif (abs($difference) < 0.01) {
                    $status = 'BALANCED';
                } elseif ($difference > 0) {
                    $status = 'EXTRA CASH';
                } else {
                    $status = 'CASH SHORT';
                }

                return [
                    $row['business_date'],
                    $row['orders'],
                    $row['sales'],
                    $row['cash'],
                    $row['upi'],
                    $row['collection'],
                    $row['expenses'],
                    $row['withdraw'],
                    $row['opening_cash'],
                    $row['expected_closing'],
                    $closingCash,
                    $difference,
                    $status,
                ];
            })
            ->values()
            ->toArray();
    }
}
