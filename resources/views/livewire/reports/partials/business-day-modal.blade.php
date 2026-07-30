@if ($showReconcileModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-2 pt-3 px-4">
                    <div class="w-100 position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0"
                            wire:click="closeModal"></button>

                        <div class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success-100 text-success rounded-circle mb-2"
                                style="width:44px;height:44px;">
                                <iconify-icon icon="mdi:cash-register" width="22"></iconify-icon>
                            </div>

                            <h2 class="fw-bold mb-1">
                                {{ $isReadOnly ? 'Business Day Register' : 'Cash Reconciliation' }}
                            </h2>

                            <div class="text-muted small">
                                {{ \Carbon\Carbon::parse($selectedDate)->format('l, d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body pt-3 pb-3">
                    <div class="row g-4 align-items-stretch">
                        {{-- Business Information --}}
                        <div class="col-lg-5">
                            <div class="border rounded-3 h-100 bg-white p-4">

                                <div class="text-uppercase small fw-semibold text-muted mb-3">
                                    Business Details
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Business Date</span>
                                    <span
                                        class="fw-semibold">{{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Opening Cash</span>
                                    <span class="fw-semibold">{{ getFormattedCurrency($openingCash) }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Cash Collection</span>
                                    <span
                                        class="fw-semibold text-success">{{ getFormattedCurrency($cashCollection) }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">UPI Collection</span>
                                    <span class="fw-semibold">₹0.00</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Expenses</span>
                                    <span
                                        class="fw-semibold text-danger">{{ getFormattedCurrency($expenseAmount) }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted">Withdrawals</span>
                                    <span class="fw-semibold">{{ getFormattedCurrency($withdrawAmount) }}</span>
                                </div>

                                <hr class="my-4">

                                <div class="text-uppercase small fw-semibold text-muted mb-3">
                                    Audit Information
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted">Closed By</span>
                                    <span class="fw-semibold">
                                        {{ $businessDayClosure?->closedBy?->name ?? '—' }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted">Closed At</span>
                                    <span class="fw-semibold">
                                        {{ $businessDayClosure ? $businessDayClosure->created_at->format('d M Y h:i A') : '—' }}
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="border rounded-3 h-100 bg-white p-4">

                                <div class="text-uppercase small fw-semibold text-muted mb-4">
                                    Cash Reconciliation
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted mb-1">Withdraw Amount</label>
                                        <input type="number" step="0.01" class="form-control text-end fw-semibold"
                                            wire:model.live="withdrawAmount" @disabled($isReadOnly)>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small text-muted mb-1">Actual Cash</label>
                                        <input type="number" step="0.01" class="form-control text-end fw-bold fs-5"
                                            wire:model.live="closingCash" @disabled($isReadOnly)>
                                    </div>
                                </div>

                                <div class="row text-center g-3">

                                    <div class="col-md-4">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="small text-muted text-uppercase mb-2">
                                                Expected Cash
                                            </div>

                                            <h3 class="fw-bold mb-0 text-primary">
                                                {{ getFormattedCurrency($expectedClosing) }}
                                            </h3>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="small text-muted text-uppercase mb-2">
                                                Actual Cash
                                            </div>

                                            <h3 class="fw-bold mb-0">
                                                {{ getFormattedCurrency($closingCash ?? 0) }}
                                            </h3>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="border rounded-3 p-3 h-100">

                                            <div class="small text-muted text-uppercase mb-2">
                                                Difference
                                            </div>

                                            <h3
                                                class="fw-bold mb-2
                                                @if ($difference > 0) text-success
                                                @elseif($difference < 0) text-danger
                                                @else text-primary @endif
                                            ">
                                                {{ getFormattedCurrency(abs($difference)) }}
                                            </h3>

                                            @if ($difference == 0)
                                                <span class="badge bg-success-subtle text-success">Balanced</span>
                                            @elseif($difference > 0)
                                                <span class="badge bg-info-subtle text-info">Extra Cash</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Cash Short</span>
                                            @endif

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    @if ($closingCash !== null)
                        <div
                            class="rounded-3 border px-3 py-2 mb-3
                            @if ($difference == 0) bg-success-subtle border-success-subtle
                            @elseif($difference > 0)
                                bg-info-subtle border-info-subtle
                            @else
                                bg-danger-subtle border-danger-subtle @endif">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fw-semibold">
                                    @if ($difference == 0)
                                        <iconify-icon icon="mdi:check-circle" class="me-1 text-success"></iconify-icon>
                                        Cash Tallied Perfectly
                                    @elseif($difference > 0)
                                        <iconify-icon icon="mdi:cash-plus" class="me-1 text-info"></iconify-icon>
                                        Extra Cash
                                    @else
                                        <iconify-icon icon="mdi:cash-remove" class="me-1 text-danger"></iconify-icon>
                                        Cash Short
                                    @endif
                                </div>
                                <div class="fw-bold">
                                    {{ getFormattedCurrency(abs($difference)) }}
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="mt-3">
                        <label class="form-label fw-semibold mb-2">Remarks</label>
                        @if ($isReadOnly)
                            <div class="border rounded-3 bg-light p-3 text-muted" style="min-height:72px;">
                                {{ $remarks ?: 'No remarks added.' }}
                            </div>
                        @else
                            <textarea wire:model.live="remarks" class="form-control" rows="2" placeholder="Enter remarks (optional)..."></textarea>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($isReadOnly)
                        <button class="btn btn-secondary" wire:click="closeModal">
                            Close
                        </button>
                    @else
                        <button class="btn btn-secondary" wire:click="closeModal">
                            Cancel
                        </button>
                        <button class="btn btn-success" wire:click="saveReconciliation">
                            Close Business Day
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
