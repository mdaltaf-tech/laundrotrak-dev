@if ($showReconcileModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width:1100px;">
            <div class="modal-content rounded-4 shadow-sm">
                <div class="modal-header  border-bottom px-5 pt-4 pb-3 position-relative"
                    style="border-color:#E9ECEF !important;">
                    <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="w-100">
                        <h2 class="mb-1 fw-bold text-dark"
                            style="font-size:2rem !important;font-weight:700 !important;line-height:1.55;">
                            {{ $isReadOnly ? 'Business Day Register' : 'Cash Reconciliation' }}
                        </h2>

                        <div class="text-muted fw-medium" style="font-size:15px;color:#6B7280;margin-top:4px;">
                            Saturday, 01 Aug 2026
                        </div>
                    </div>
                </div>
                <div class="modal-body px-5 py-4">

                    {{-- Business Summary --}}
                    <div class="mb-4">

                        <div class="text-uppercase text-muted fw-semibold mb-3"
                            style="font-size:13px;letter-spacing:.6px;">
                            <div class="section-title">
                                <span class="section-bar"></span>
                                Business Summary
                            </div>
                        </div>

                        <div class="row g-3">

                            {{-- Opening Cash --}}
                            <div class="col-md-3">
                                <div class="rounded-3 border h-100 px-4 py-3"
                                    style="background:#F8FAFC;box-shadow:0 1px 2px rgba(0,0,0,.04);transition:.2s;border:1px solid #E8EDF3;">
                                    <div
                                        style="
                        font-size:13px;
                        font-weight:500;
                        color:#6B7280;
                        margin-bottom:10px;
                    ">
                                        Opening Cash
                                    </div>

                                    <div class="fw-bold text-dark"
                                        style="
                        font-size:1.85rem;
                        font-weight:700;
                        line-height:1.15;
                        min-height:64px;
                    ">
                                        {{ getFormattedCurrency($openingCash) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Cash Collection --}}
                            <div class="col-md-3">
                                <div class="rounded-3 border h-100 px-4 py-3"
                                    style="background:#F0FDF4;box-shadow:0 1px 2px rgba(0,0,0,.04);transition:.2s;border:1px solid #E8EDF3;">
                                    <div
                                        style="
                        font-size:13px;
                        font-weight:500;
                        color:#6B7280;
                        margin-bottom:10px;
                    ">
                                        Cash Collection
                                    </div>

                                    <div class="fw-bold text-success"
                                        style="
                        font-size:1.85rem;
                        font-weight:700;
                        line-height:1.15;
                        min-height:64px;
                    ">
                                        {{ getFormattedCurrency($cashCollection) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Expenses --}}
                            <div class="col-md-3">
                                <div class="rounded-3 border h-100 px-4 py-3"
                                    style="background:#FEF2F2;box-shadow:0 1px 2px rgba(0,0,0,.04);transition:.2s;border:1px solid #E8EDF3;">
                                    <div
                                        style="
                        font-size:13px;
                        font-weight:500;
                        color:#6B7280;
                        margin-bottom:10px;
                    ">
                                        Expenses
                                    </div>

                                    <div class="fw-bold text-danger"
                                        style="
                        font-size:1.85rem;
                        font-weight:700;
                        line-height:1.15;
                        min-height:64px;
                    ">
                                        {{ getFormattedCurrency($expenseAmount) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Expected Closing --}}
                            <div class="col-md-3">
                                <div class="rounded-3 border h-100 px-4 py-3"
                                    style="background:#EFF6FF;box-shadow:0 1px 2px rgba(0,0,0,.04);transition:.2s;border:1px solid #E8EDF3;">
                                    <div
                                        style="
                        font-size:13px;
                        font-weight:500;
                        color:#6B7280;
                        margin-bottom:10px;
                    ">
                                        Expected Closing
                                    </div>

                                    <div class="fw-bold text-primary"
                                        style="
                        font-size:1.85rem;
                        font-weight:700;
                        line-height:1.15;
                        min-height:64px;
                    ">
                                        {{ getFormattedCurrency($expectedClosing) }}
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- Cash Verification --}}
                    <div class="mb-4">

                        <div class="text-muted fw-bold mb-3" style="font-size:13px;letter-spacing:.6px;">
                            CASH VERIFICATION
                        </div>

                        <div class="p-0">

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold mb-2"
                                        style="font-size:15px;font-weight:600;color:#374151;">
                                        Cash Withdrawn Before Closing
                                    </label>

                                    <input type="number" step="0.01" class="form-control text-end fw-bold"
                                        style="height:50px;font-size:22px;font-weight:700;"
                                        wire:model.live="withdrawAmount" @disabled($isReadOnly)>
                                    <div class="form-text">
                                        Cash removed from drawer.
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold mb-2"
                                        style="font-size:15px;font-weight:600;color:#374151;">
                                        Actual Cash Counted
                                    </label>

                                    <input type="number" step="0.01" class="form-control text-end fw-bold"
                                        style="height:50px;font-size:22px;font-weight:700;"
                                        wire:model.live="closingCash" @disabled($isReadOnly)>
                                    <div class="form-text" style="color:#9CA3AF;font-size:13px;">
                                        Physical cash available.
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Reconciliation Status --}}
                    <div class="mb-4">

                        <div class="text-muted fw-bold mb-3" style="font-size:13px;letter-spacing:.6px;">
                            RECONCILIATION STATUS
                        </div>

                        <div class="border rounded-3 p-4 bg-white">

                            <div class="row align-items-center">

                                <div class="col-md-8">

                                    <div class="text-muted mb-1" style="font-size:13px;">
                                        Status
                                    </div>

                                    <h4
                                        class="fw-bold mb-2
                    @if ($difference == 0) text-success
                    @elseif($difference > 0)
                        text-info
                    @else
                        text-danger @endif">
                                        @if ($difference == 0)
                                            Balanced
                                        @elseif($difference > 0)
                                            Extra Cash
                                        @else
                                            Cash Short
                                        @endif
                                    </h4>

                                    <div class="text-muted" style="font-size:13px;color:#6B7280;">
                                        @if ($difference == 0)
                                            Cash counted exactly matches the expected closing balance.
                                        @elseif($difference > 0)
                                            Physical cash is higher than the expected closing balance.
                                        @else
                                            Physical cash is lower than the expected closing balance.
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                                    <div class="text-muted mb-1" style="font-size:13px;color:#9CA3AF;">
                                        Difference
                                    </div>

                                    <div class="fw-bold
                    @if ($difference > 0) text-info
                    @elseif($difference < 0)
                        text-danger
                    @else
                        text-success @endif"
                                        style="font-size:2rem;font-weight:700;">

                                        {{ getFormattedCurrency(abs($difference)) }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Remarks --}}
                    <div class="mt-3">

                        <label class="form-label fw-semibold mb-2" style="font-size:14px;">
                            Remarks
                            <span class="text-muted fw-normal">(Optional)</span>
                        </label>

                        <textarea wire:model.live="remarks" class="form-control" rows="4" placeholder="Add remarks (optional)"
                            style="resize:none;padding:16px;" @disabled($isReadOnly)></textarea>

                    </div>

                </div>
                <div class="modal-footer border-top px-5 py-4">

                    @if ($isReadOnly)
                        <button class="btn btn-outline-secondary px-4" wire:click="closeModal">
                            Close
                        </button>
                    @else
                        <button class="btn btn-outline-secondary px-4" wire:click="closeModal">
                            Cancel
                        </button>

                        <button class="btn btn-success px-4" wire:click="saveReconciliation">
                            Close Business Day
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endif
