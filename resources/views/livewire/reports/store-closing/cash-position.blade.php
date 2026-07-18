<div class="mb-4">

    <div class="d-flex align-items-center mb-3">

        <div
            class="avatar-sm bg-primary-100 text-primary-600 radius-8 me-3 d-flex align-items-center justify-content-center">
            <iconify-icon icon="mdi:cash-multiple"></iconify-icon>
        </div>

        <div>
            <h6 class="mb-0 fw-bold">
                Today's Cash Position
            </h6>

            <small class="text-muted">
                Verify today's cash movement before counting the drawer.
            </small>
        </div>

    </div>

    <div class="border rounded-3">

        <div class="d-flex justify-content-between p-3 border-bottom">

            <span class="text-muted">

                Opening Cash

            </span>

            <strong>

                {{ getFormattedCurrency($openingCash) }}

            </strong>

        </div>

        <div class="d-flex justify-content-between p-3 border-bottom">

            <span class="text-muted">

                Cash Received

            </span>

            <strong>

                {{ getFormattedCurrency($cashCollection) }}

            </strong>

        </div>

        <div class="d-flex justify-content-between p-3 border-bottom">

            <span class="text-muted">

                Cash Expenses

            </span>

            <strong class="{{ $expenseAmount > 0 ? 'text-danger' : '' }}">

                {{ getFormattedCurrency($expenseAmount) }}

            </strong>

        </div>

        <div class="p-3">

            <label class="form-label fw-semibold">

                Cash Withdraw

            </label>

            <input type="number" step="0.01" wire:model.live="withdrawAmount" class="form-control text-end">

        </div>

    </div>

</div>
