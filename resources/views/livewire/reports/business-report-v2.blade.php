<div class="dashboard-main-body">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1 fw-bold">
                Business Report
            </h3>

            <p class="text-muted mb-0">
                Daily Cash Register & Business Summary
            </p>

        </div>

        <div style="width:220px">

            <label class="form-label">
                Business Month
            </label>

            <input type="month" class="form-control" wire:model.live="month">

        </div>

    </div>

    @include('livewire.reports.partials.daily-register-v2')

    <div class="card radius-12 mt-4">
        <div class="card-body py-5 text-center text-muted">
            Fixed Expenses (Coming Next)
        </div>
    </div>

    <div class="card radius-12 mt-4">
        <div class="card-body py-5 text-center text-muted">
            Electricity Register (Coming Next)
        </div>
    </div>

    <div class="card radius-12 mt-4">
        <div class="card-body py-5 text-center text-muted">
            Business Summary (Coming Next)
        </div>
    </div>
    @include('livewire.reports.store-closing.wizard')
</div>
