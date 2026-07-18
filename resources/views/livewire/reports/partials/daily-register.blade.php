<div class="dashboard-main-body">

    <div class="card h-100 p-0 radius-12">

        {{-- Header --}}
        <div class="tw-py-2 tw-px-3 bg-base d-flex align-items-center justify-content-between flex-wrap gap-3">

            <div>

                <h5 class="mb-0 fw-bold">
                    Daily Cash Register
                </h5>

                <small class="text-muted">
                    Daily business reconciliation and cash register
                </small>

            </div>

            <div class="d-flex align-items-end gap-3">

                <div>

                    <label class="form-label mb-1">
                        Business Month
                    </label>

                    <input type="month" class="form-control bg-base h-40-px" wire:model.live="selectedMonth">

                </div>

                <button class="btn btn-success-100 text-success-600 radius-8 px-16 py-9">

                    Export

                </button>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive scroll-sm tw-min-h-[calc(100vh-16rem)]">

                <table class="table bordered-table sm-table mb-0">

                    <thead>

                        <tr>

                            <th style="width:10%">
                                Date
                            </th>

                            <th style="width:18%">
                                Business
                            </th>

                            <th style="width:18%">
                                Collection
                            </th>

                            <th style="width:25%">
                                Cash Register
                            </th>

                            <th style="width:14%">
                                Status
                            </th>

                            <th style="width:15%" class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>
                        @forelse($rows as $row)
                            <tr @class([
                                'tw-bg-blue-50' => $row['business_date'] == now()->toDateString(),
                            ])>

                                {{-- Date --}}
                                <td class="align-top">

                                    <div class="fw-bold">
                                        {{ \Carbon\Carbon::parse($row['business_date'])->format('d') }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($row['business_date'])->format('M') }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($row['business_date'])->format('D') }}
                                    </div>

                                </td>

                                {{-- Business --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ number_format($row['orders']) }}

                                        <small class="text-muted">
                                            Orders
                                        </small>

                                    </div>

                                    <div class="mt-2">

                                        <span class="fw-bold text-primary">

                                            {{ getFormattedCurrency($row['sales']) }}

                                        </span>

                                        <div class="small text-muted">

                                            Order Value

                                        </div>

                                    </div>

                                </td>

                                {{-- Collection --}}
                                <td class="align-middle">

                                    <div class="d-flex justify-content-between">

                                        <small class="text-muted">
                                            Cash
                                        </small>

                                        <span class="fw-semibold">

                                            {{ getFormattedCurrency($row['cash']) }}

                                        </span>

                                    </div>

                                    <div class="d-flex justify-content-between mt-1">

                                        <small class="text-muted">
                                            UPI
                                        </small>

                                        <span class="fw-semibold">

                                            {{ getFormattedCurrency($row['upi']) }}

                                        </span>

                                    </div>

                                    <hr class="my-2">

                                    <div class="d-flex justify-content-between">

                                        <strong>Total</strong>

                                        <strong>

                                            {{ getFormattedCurrency($row['collection']) }}

                                        </strong>

                                    </div>

                                </td>

                                {{-- Cash Register --}}
                                <td>

                                    <div class="d-flex justify-content-between">

                                        <span class="text-muted">

                                            Opening

                                        </span>

                                        <strong>

                                            {{ getFormattedCurrency($row['opening_cash']) }}

                                        </strong>

                                    </div>

                                    <div class="d-flex justify-content-between mt-1">

                                        <span class="text-muted">

                                            Expected

                                        </span>

                                        <strong>

                                            {{ getFormattedCurrency($row['expected_closing']) }}

                                        </strong>

                                    </div>

                                    <div class="d-flex justify-content-between mt-1">

                                        <span class="text-muted">

                                            Closing

                                        </span>

                                        <strong>

                                            @if ($row['closing_cash'])
                                                {{ getFormattedCurrency($row['closing_cash']) }}
                                            @else
                                                —
                                            @endif

                                        </strong>

                                    </div>

                                    <hr class="my-2">

                                    @php

                                        $difference = $row['extra_cash'] > 0 ? $row['extra_cash'] : $row['less_cash'];

                                    @endphp

                                    <div class="d-flex justify-content-between">

                                        @if ($row['extra_cash'] > 0)
                                            <span class="text-success">

                                                Extra Cash

                                            </span>

                                            <strong class="text-success">

                                                {{ getFormattedCurrency($difference) }}

                                            </strong>
                                        @elseif($row['less_cash'] > 0)
                                            <span class="text-danger">

                                                Cash Short

                                            </span>

                                            <strong class="text-danger">

                                                {{ getFormattedCurrency($difference) }}

                                            </strong>
                                        @else
                                            <span class="text-muted">

                                                Difference

                                            </span>

                                            <strong>

                                                —

                                            </strong>
                                        @endif

                                    </div>

                                </td>

                                {{-- Status --}}
                                <td>

                                    @if ($row['closing_cash'])
                                        <span
                                            class="badge fw-semibold text-success-600 bg-success-100 radius-8 px-12 py-6">

                                            Reconciled

                                        </span>
                                    @else
                                        <span
                                            class="badge fw-semibold text-warning-600 bg-warning-100 radius-8 px-12 py-6">

                                            Pending

                                        </span>
                                    @endif

                                </td>

                                {{-- Action --}}
                                <td class="text-center">

                                    <button class="btn btn-sm btn-primary-100 text-primary-600 radius-8"
                                        wire:click="reconcile('{{ $row['business_date'] }}')">

                                        {{ $row['closing_cash'] ? 'View' : 'Close Cash' }}

                                    </button>

                                </td>

                            </tr>
                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    No records found.

                                </td>

                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="table-secondary fw-bold">

                        <tr>

                            <td>Total</td>

                            <td class="text-end">{{ $totals['orders'] }}</td>

                            <td class="text-end">{{ getFormattedCurrency($totals['sales']) }}</td>

                            <td class="text-end">{{ getFormattedCurrency($totals['cash']) }}</td>

                            <td class="text-end">{{ getFormattedCurrency($totals['upi']) }}</td>

                            <td class="text-end">{{ getFormattedCurrency($totals['collection']) }}</td>

                            <td class="text-end">{{ getFormattedCurrency($totals['expenses']) }}</td>

                            <td class="text-end">{{ getFormattedCurrency($totals['withdraw']) }}</td>

                            <td></td>

                            <td></td>

                            <td class="text-end">
                                {{ getFormattedCurrency($totals['closing_cash']) }}
                            </td>

                            <td class="text-end">
                                {{ getFormattedCurrency($totals['extra_cash']) }}
                            </td>

                            <td class="text-end">
                                {{ getFormattedCurrency($totals['less_cash']) }}
                            </td>

                            <td colspan="2"></td>

                        </tr>

                    </tfoot>

                </table>

            </div>
