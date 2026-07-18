<div class="modal fade @if ($showClosingSummary) show d-block @endif" tabindex="-1"
    @if ($showClosingSummary) style="background:rgba(0,0,0,.45);" @endif>

    @if ($showClosingSummary)
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header border-0 pb-0">
                    <div>
                        <h2 class="fw-bold mb-1">Business Day Summary</h2>
                        <div class="text-muted">
                            {{ \Carbon\Carbon::parse($selectedCashRegister->business_date)->format('l, d M Y') }}
                        </div>
                    </div>

                    <button class="btn-close" wire:click="closeSummary">
                    </button>
                </div>

                <div class="modal-body">

                    {{-- Status --}}
                    <div class="alert alert-success text-center mb-4">
                        <strong>Business Day Closed</strong>
                    </div>

                    {{-- Summary --}}
                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <table class="table table-borderless mb-0">

                                <tr>
                                    <td>Opening Cash</td>
                                    <td class="text-end fw-semibold">
                                        {{ getFormattedCurrency($selectedCashRegister->opening_cash) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Cash Collection</td>
                                    <td class="text-end fw-semibold">
                                        {{ getFormattedCurrency($selectedCashRegister->cash_received) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Expenses</td>
                                    <td class="text-end fw-semibold">
                                        {{ getFormattedCurrency($selectedCashRegister->expense_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Withdraw</td>
                                    <td class="text-end fw-semibold">
                                        {{ getFormattedCurrency($selectedCashRegister->withdraw_amount) }}
                                    </td>
                                </tr>

                                <tr class="border-top">
                                    <td><strong>Expected Cash</strong></td>
                                    <td class="text-end fw-bold">
                                        {{ getFormattedCurrency($selectedCashRegister->expected_cash) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Counted Cash</td>
                                    <td class="text-end fw-bold">
                                        {{ getFormattedCurrency($selectedCashRegister->closing_cash) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Difference</td>
                                    <td class="text-end fw-bold">
                                        {{ getFormattedCurrency($selectedCashRegister->difference_amount) }}
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </div>

                    <div class="mt-4">

                        <div class="row">

                            <div class="col-md-6">

                                <label class="text-muted small">Closed By</label>

                                <div class="fw-semibold">
                                    {{ optional($selectedCashRegister->user)->name }}
                                </div>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted small">Closed At</label>

                                <div class="fw-semibold">
                                    {{ optional($selectedCashRegister->closed_at)->format('d M Y h:i A') }}
                                </div>

                            </div>

                        </div>

                    </div>

                    @if ($selectedCashRegister->remarks)
                        <div class="mt-4">

                            <label class="text-muted small">
                                Remarks
                            </label>

                            <div class="border rounded p-3 bg-light">
                                {{ $selectedCashRegister->remarks }}
                            </div>

                        </div>
                    @endif

                </div>

                <div class="modal-footer">

                    <button class="btn btn-outline-primary">

                        Print Closing Slip

                    </button>

                    <button class="btn btn-secondary" wire:click="closeSummary">

                        Close

                    </button>

                </div>

            </div>
        </div>
    @endif
</div>
