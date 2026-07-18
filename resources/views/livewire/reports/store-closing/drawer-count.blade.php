<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <div class="d-flex align-items-center mb-3">
            <div
                class="avatar-sm bg-success-100 text-success-600 radius-8 me-3 d-flex align-items-center justify-content-center">
                <iconify-icon icon="mdi:cash-register" width="20"></iconify-icon>
            </div>

            <div>
                <h6 class="mb-0 fw-bold">Count Your Cash Drawer</h6>
                <small class="text-muted">
                    Enter the total cash physically available in the drawer.
                </small>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label fw-semibold">Counted Cash</label>

            <div class="input-group input-group-lg">
                <span class="input-group-text">
                    ₹
                </span>

                <input type="number" step="0.01" min="0" class="form-control text-end fw-bold fs-4"
                    wire:model.live="closingCash" placeholder="0.00" autofocus>

            </div>
        </div>

        <div class="text-center mt-3">

            <a href="#" class="small text-decoration-none text-primary">

                <iconify-icon icon="mdi:calculator-variant-outline" class="me-1"></iconify-icon>

                Count by Denomination
                <span class="text-muted">(Coming Soon)</span>

            </a>

        </div>

    </div>
</div>
