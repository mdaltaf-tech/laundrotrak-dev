<div>

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="mb-0">
                Business Report
            </h3>

            <small class="text-muted">
                Daily Cash Register & Business Summary
            </small>
        </div>

        <div style="width:220px;">

            <label class="form-label mb-1">
                Month
            </label>

            <input type="month" class="form-control" wire:model.live="month">

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- Daily Cash Register --}}
    {{-- ========================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Daily Cash Register
            </h5>

        </div>

        <div class="card-body p-0">

            @include('livewire.reports.partials.daily-register')

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- Fixed Expenses --}}
    {{-- ========================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Fixed Expenses
            </h5>

        </div>

        <div class="card-body">

            <div class="text-muted">

                Coming Soon...

            </div>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- Electricity --}}
    {{-- ========================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Electricity Register
            </h5>

        </div>

        <div class="card-body">

            <div class="text-muted">

                Coming Soon...

            </div>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- Business Summary --}}
    {{-- ========================================= --}}

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Business Summary
            </h5>

        </div>

        <div class="card-body">

            <div class="text-muted">

                Coming Soon...

            </div>

        </div>

    </div>

</div>

@include('livewire.reports.partials.reconciliation-modal')
