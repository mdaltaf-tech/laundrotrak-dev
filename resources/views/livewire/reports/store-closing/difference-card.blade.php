@php
    $difference = round($difference, 2);

    if ($difference == 0) {
        $status = 'balanced';
        $title = 'Drawer Balanced';
        $message = 'Everything matches perfectly. You are ready to close the business day.';
        $icon = 'mdi:check-circle';
        $bgClass = 'bg-success-50';
        $iconClass = 'bg-success text-white';
        $textClass = 'text-success';
    } elseif ($difference < 0) {
        $status = 'short';
        $title = 'Cash Short';
        $message = 'Cash in drawer is less than the expected amount.';
        $icon = 'mdi:alert-circle';
        $bgClass = 'bg-danger-50';
        $iconClass = 'bg-danger text-white';
        $textClass = 'text-danger';
    } else {
        $status = 'extra';
        $title = 'Extra Cash Found';
        $message = 'Cash in drawer is more than the expected amount.';
        $icon = 'mdi:cash-plus';
        $bgClass = 'bg-info-50';
        $iconClass = 'bg-info text-white';
        $textClass = 'text-info';
    }
@endphp

<div class="card border-0 {{ $bgClass }} mb-4">
    <div class="card-body text-center">

        <div
            class="avatar-xl {{ $iconClass }} mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
            <iconify-icon icon="{{ $icon }}" width="34"></iconify-icon>
        </div>

        <h4 class="fw-bold {{ $textClass }}">
            {{ $title }}
        </h4>

        <p class="text-muted mb-3">
            {{ $message }}
        </p>

        <div class="display-6 fw-bold {{ $textClass }}">
            {{ getFormattedCurrency(abs($difference)) }}
        </div>

        @if ($difference != 0)
            <div class="mt-4">

                <label class="form-label fw-semibold">
                    Why is there a difference? <span class="text-danger">*</span>
                </label>

                <select class="form-select" wire:model.live="differenceReason">

                    <option value="">Select Reason</option>

                    <option value="counting_error">
                        Counting Error
                    </option>

                    <option value="cash_short">
                        Cash Short
                    </option>

                    <option value="cash_excess">
                        Cash Excess
                    </option>

                    <option value="pending_expense">
                        Pending Expense Entry
                    </option>

                    <option value="customer_credit">
                        Customer Credit
                    </option>

                    <option value="other">
                        Other
                    </option>

                </select>

                @error('differenceReason')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>
        @endif

    </div>
</div>
