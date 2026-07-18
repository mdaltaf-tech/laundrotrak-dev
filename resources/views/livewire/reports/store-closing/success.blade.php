<div class="text-center py-4">

    <div
        class="avatar-xxl bg-success-100 text-success mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center">
        <iconify-icon icon="mdi:check-circle" width="60"></iconify-icon>
    </div>

    <h3 class="fw-bold text-success mb-2">
        Business Day Closed Successfully
    </h3>

    <p class="text-muted mb-4">
        Cash drawer has been reconciled successfully.
    </p>

    <div class="text-center mb-4">
        <span class="badge bg-light text-dark fs-6 px-3 py-2">
            Reconciliation No.:
            <strong>{{ $receiptNumber }}</strong>
        </span>
    </div>

    <div class="card border-0 bg-light mx-auto" style="max-width:420px;">
        <div class="card-body">

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Expected Cash</span>
                <strong>{{ getFormattedCurrency($expectedClosing) }}</strong>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Counted Cash</span>
                <strong>{{ getFormattedCurrency($closingCash) }}</strong>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Difference</span>

                <strong class="{{ $difference == 0 ? 'text-success' : 'text-danger' }}">
                    {{ getFormattedCurrency(abs($difference)) }}
                </strong>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Closed By</span>
                <strong>{{ auth()->user()->name }}</strong>
            </div>

            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Closed At</span>
                <strong>{{ now()->format('d M Y h:i A') }}</strong>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-center gap-3 mt-4">

        <a href="{{ route('prints.closing-slip', $businessDayClosureId) }}" target="_blank"
            class="btn btn-outline-primary">

            <iconify-icon icon="mdi:printer-outline" class="me-1">
            </iconify-icon>

            Print Closing Slip

        </a>

        <button type="button" wire:click="closeModal" class="btn btn-success">

            Done

        </button>

    </div>

</div>
