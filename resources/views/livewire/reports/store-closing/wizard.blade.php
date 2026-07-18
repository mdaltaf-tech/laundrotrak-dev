@if ($showReconcileModal)

    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.55);">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0">

                    <div class="w-100 text-center position-relative">

                        <button type="button" class="btn-close position-absolute end-0 top-0" wire:click="closeModal">
                        </button>

                        <div
                            class="avatar-xl bg-success-100 text-success rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3">
                            <iconify-icon icon="mdi:cash-register" width="34"></iconify-icon>
                        </div>

                        <h3 class="fw-bold mb-1">
                            Close Business Day
                        </h3>

                        <div class="text-muted">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('l, d F Y') }}
                        </div>

                    </div>

                </div>

                {{-- Body --}}
                <div class="modal-body px-4 pb-0">

                    @if ($closingCompleted)
                        @include('livewire.reports.store-closing.success')
                    @else
                        @include('livewire.reports.store-closing.cash-position')

                        @include('livewire.reports.store-closing.expected-cash')

                        @include('livewire.reports.store-closing.drawer-count')

                        @include('livewire.reports.store-closing.difference-card')

                        @include('livewire.reports.store-closing.remarks')
                    @endif

                </div>

                @include('livewire.reports.store-closing.footer')

            </div>
        </div>
    </div>

@endif
