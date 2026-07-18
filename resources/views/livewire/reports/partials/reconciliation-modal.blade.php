@if ($showReconcileModal)

    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header border-0 pb-0">

                    <div class="w-100 text-center position-relative">

                        <button type="button" class="btn-close position-absolute top-0 end-0" wire:click="closeModal">
                        </button>

                        <div class="mb-2">

                            <div
                                class="avatar-lg bg-success-100 text-success-600 mx-auto d-flex align-items-center justify-content-center radius-50">

                                <iconify-icon icon="mdi:cash-register" width="28"></iconify-icon>

                            </div>

                        </div>

                        <h4 class="fw-bold mb-1">

                            Close Business Day

                        </h4>

                        <div class="text-muted">

                            {{ \Carbon\Carbon::parse($selectedDate)->format('l, d F Y') }}

                        </div>

                    </div>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="card border">

                                <div class="card-body">

                                    <table class="table table-sm table-borderless mb-0">

                                        <tr>
                                            <th width="60%">Business Date</th>
                                            <td class="text-end">
                                                {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Opening Cash</th>
                                            <td class="text-end">
                                                {{ getFormattedCurrency($openingCash) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Cash Collection</th>
                                            <td class="text-end">
                                                {{ getFormattedCurrency($cashCollection) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Expenses</th>
                                            <td class="text-end text-danger">
                                                {{ getFormattedCurrency($expenseAmount) }}
                                            </td>
                                        </tr>

                                    </table>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="card border">

                                <div class="card-body">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Withdraw Amount

                                        </label>

                                        <input type="number" step="0.01" class="form-control text-end"
                                            wire:model.live="withdrawAmount">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Expected Cash

                                        </label>

                                        <input type="text" readonly class="form-control text-end fw-bold bg-light"
                                            value="{{ getFormattedCurrency($expectedClosing) }}">

                                    </div>

                                    <div>

                                        <label class="form-label">

                                            Actual Closing Cash

                                        </label>

                                        <input type="number" step="0.01" class="form-control text-end"
                                            wire:model.live="closingCash">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-12">

                            @if ($closingCash !== null)

                                @if ($difference > 0)
                                    <div class="alert alert-success mb-3">

                                        <strong>

                                            Extra Cash

                                        </strong>

                                        <span class="float-end">

                                            {{ getFormattedCurrency($difference) }}

                                        </span>

                                    </div>
                                @elseif($difference < 0)
                                    <div class="alert alert-danger mb-3">

                                        <strong>

                                            Cash Short

                                        </strong>

                                        <span class="float-end">

                                            {{ getFormattedCurrency(abs($difference)) }}

                                        </span>

                                    </div>
                                @else
                                    <div class="alert alert-info mb-3">

                                        Cash Tallied Perfectly

                                    </div>
                                @endif

                            @endif

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Remarks

                        </label>

                        <textarea rows="3" class="form-control" wire:model.defer="remarks"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary" wire:click="closeModal">

                        Cancel

                    </button>

                    <button class="btn btn-success" wire:click="saveReconciliation">

                        Save

                    </button>

                </div>

            </div>

        </div>

    </div>

@endif
