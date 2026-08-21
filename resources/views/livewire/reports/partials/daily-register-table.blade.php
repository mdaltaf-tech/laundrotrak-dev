<div class="table-responsive">
    <table class="table report-table mb-0">

        <thead>
            <tr>
                <th>Date</th>
                <th>Business</th>
                <th>Collection</th>
                <th>Cash Register</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>

            @forelse($rows as $row)
                @php
                    $difference = ($row['closing_cash'] ?? 0) - $row['expected_closing'];
                @endphp

                <tr>

                    {{-- DATE --}}
                    <td class="text-center">
                        <x-date-card :date="$row['business_date']" />
                    </td>

                    {{-- BUSINESS --}}
                    <td>
                        <div class="report-business">
                            <div class="report-orders">
                                <span class="report-orders-count">
                                    {{ $row['orders'] }}
                                </span>
                                <span class="report-orders-label">
                                    Orders
                                </span>
                            </div>
                            <div class="report-sales">
                                {{ getFormattedCurrency($row['sales']) }}
                            </div>
                        </div>
                    </td>

                    {{-- COLLECTION --}}
                    <td>
                        <x-key-value label="Cash" :value="getFormattedCurrency($row['cash'])" />
                        <x-key-value label="UPI" :value="getFormattedCurrency($row['upi'])" />
                        <x-key-value label="TOTAL" border labelClass="report-total-label">
                            <strong>
                                {{ getFormattedCurrency($row['collection']) }}
                            </strong>
                        </x-key-value>
                    </td>

                    {{-- CASH REGISTER --}}
                    <td>

                        <x-key-value label="Opening">
                            {{ getFormattedCurrency($row['opening_cash']) }}
                        </x-key-value>

                        <x-key-value label="Expenses">
                            <span class="text-danger">
                                {{ getFormattedCurrency($row['expenses']) }}
                            </span>
                        </x-key-value>

                        <x-key-value label="Cash Removed">
                            {{ getFormattedCurrency($row['withdraw']) }}
                        </x-key-value>

                        <x-key-value label="Expected">
                            {{ getFormattedCurrency($row['expected_closing']) }}
                        </x-key-value>

                        <x-key-value label="Closing">
                            {{ $row['closing_cash'] !== null ? getFormattedCurrency($row['closing_cash']) : '—' }}
                        </x-key-value>

                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">

                        @if (is_null($row['closing_cash']))
                            <x-status-pill variant="warning">
                                Open
                            </x-status-pill>
                        @elseif($difference == 0)
                            <x-status-pill variant="success">
                                Balanced
                            </x-status-pill>
                        @elseif($difference > 0)
                            <x-status-pill variant="info">
                                Excess Cash
                            </x-status-pill>
                        @else
                            <x-status-pill variant="danger">
                                Cash Short
                            </x-status-pill>
                        @endif

                    </td>

                    {{-- ACTION --}}
                    <td class="text-center">

                        @if (empty($row['closing_cash']))
                            <a href="#" class="report-link"
                                wire:click.prevent="reconcile('{{ $row['business_date'] }}')">
                                Close Day →
                            </a>
                        @else
                            <a href="#" class="report-link report-link-success"
                                wire:click.prevent="reconcile('{{ $row['business_date'] }}')">
                                View Register →
                            </a>
                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6">

                        <div class="report-empty">

                            No business data available.

                        </div>

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>
</div>
