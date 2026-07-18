<div class="card border-0 bg-primary-50 shadow-none mb-4">
    <div class="card-body text-center py-4">
        <div class="text-muted text-uppercase fw-semibold small mb-2">
            Expected Cash
        </div>

        <h1 class="fw-bold text-primary mb-2">
            {{ getFormattedCurrency($expectedClosing) }}
        </h1>

        <small class="text-muted">
            This is the amount that should be available before counting the cash drawer.
        </small>
    </div>
</div>
