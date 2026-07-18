<div class="card radius-12">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <div>

            <h5 class="mb-1 fw-bold">
                Daily Cash Register
            </h5>

            <small class="text-muted">
                Daily business reconciliation and cash register
            </small>

        </div>

        <button class="btn btn-success-100 text-success-600">

            Export

        </button>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table bordered-table sm-table mb-0">

                <thead>

                    <tr>

                        <th width="10%">Date</th>

                        <th width="20%">Business</th>

                        <th width="20%">Collection</th>

                        <th width="25%">Cash Register</th>

                        <th width="10%">Status</th>

                        <th width="15%" class="text-center">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($rows as $row)
                        <tr>

                            <td class="align-middle">

                                @php
                                    $date = \Carbon\Carbon::parse($row['business_date']);
                                @endphp

                                <div class="fw-bold fs-5 text-dark">
                                    {{ $date->format('d') }}
                                </div>

                                <div class="small text-muted">
                                    {{ strtoupper($date->format('M')) }}
                                </div>

                                <div class="small text-secondary">
                                    {{ $date->format('D') }}
                                </div>

                                @if ($date->isToday())
                                    <span class="badge bg-primary-100 text-primary-600 radius-8 mt-2">
                                        Today
                                    </span>
                                @endif

                            </td>

                            <td class="align-middle">

                                <div class="fw-semibold">

                                    {{ number_format($row['orders']) }}

                                    <span class="text-muted small">
                                        Orders
                                    </span>

                                </div>

                                <div class="fw-bold text-primary mt-1">

                                    {{ getFormattedCurrency($row['sales']) }}

                                </div>

                            </td>

                            <td class="align-middle">

                                <div class="d-flex justify-content-between small">

                                    <span class="text-muted">
                                        Cash
                                    </span>

                                    <span class="fw-semibold">
                                        {{ getFormattedCurrency($row['cash']) }}
                                    </span>

                                </div>

                                <div class="d-flex justify-content-between small mt-1">

                                    <span class="text-muted">
                                        UPI
                                    </span>

                                    <span class="fw-semibold">
                                        {{ getFormattedCurrency($row['upi']) }}
                                    </span>

                                </div>

                                <div class="border-top mt-2 pt-2 d-flex justify-content-between">

                                    <strong>Total</strong>

                                    <strong>

                                        {{ getFormattedCurrency($row['collection']) }}

                                    </strong>

                                </div>

                            </td>

                            <td class="align-middle">

                                <div class="d-flex justify-content-between small">

                                    <span class="text-muted">

                                        Open

                                    </span>

                                    <strong>

                                        {{ getFormattedCurrency($row['opening_cash']) }}

                                    </strong>

                                </div>

                                <div class="d-flex justify-content-between small mt-1">

                                    <span class="text-muted">

                                        Expected

                                    </span>

                                    <strong>

                                        {{ getFormattedCurrency($row['expected_closing']) }}

                                    </strong>

                                </div>

                                <div class="d-flex justify-content-between small mt-1">

                                    <span class="text-muted">

                                        Close

                                    </span>

                                    <strong>

                                        {{ $row['closing_cash'] ? getFormattedCurrency($row['closing_cash']) : '—' }}

                                    </strong>

                                </div>

                            </td>

                            <td class="align-middle text-center">

                                @php
                                    $difference = ($row['closing_cash'] ?? 0) - $row['expected_closing'];
                                @endphp

                                @if (empty($row['closing_cash']))
                                    <span class="badge bg-warning-100 text-warning-600 radius-8 px-8 py-4">
                                        Open
                                    </span>
                                @elseif($difference == 0)
                                    <span class="badge bg-success-100 text-success-600 radius-8 px-8 py-4">
                                        Balanced
                                    </span>
                                @elseif($difference > 0)
                                    <span class="badge bg-info-100 text-info-600 radius-8 px-8 py-4">
                                        Extra
                                    </span>
                                @else
                                    <span class="badge bg-danger-100 text-danger-600 radius-8 px-8 py-4">
                                        Short
                                    </span>
                                @endif

                            </td>

                            <td class="align-middle text-center">

                                @if (empty($row['closing_cash']))
                                    <a href="#" class="fw-semibold small text-primary"
                                        wire:click.prevent="reconcile('{{ $row['business_date'] }}')">

                                        Close Day →

                                    </a>
                                @else
                                    <a href="#" wire:click.prevent="reconcile('{{ $row['business_date'] }}')"
                                        class="fw-semibold text-success">

                                        View →

                                    </a>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-5 text-muted">

                                No business data found.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
