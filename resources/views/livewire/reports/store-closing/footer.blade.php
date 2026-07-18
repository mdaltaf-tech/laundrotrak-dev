@if (!$closingCompleted)

    <div class="modal-footer border-0 pt-0">

        <button type="button" class="btn btn-light" wire:click="closeModal">

            Cancel

        </button>

        @if ($difference == 0)
            <button type="button" class="btn btn-success px-4" wire:click="saveReconciliation"
                wire:loading.attr="disabled">

                <span wire:loading.remove wire:target="saveReconciliation">
                    <iconify-icon icon="mdi:check-circle-outline" class="me-1"></iconify-icon>
                    Close Business Day
                </span>

                <span wire:loading wire:target="saveReconciliation">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Closing...
                </span>

            </button>
        @else
            <button type="button" class="btn btn-warning px-4" wire:click="saveReconciliation"
                wire:loading.attr="disabled">

                <span wire:loading.remove wire:target="saveReconciliation">
                    <iconify-icon icon="mdi:alert-outline" class="me-1"></iconify-icon>
                    Close With Difference
                </span>

                <span wire:loading wire:target="saveReconciliation">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Closing...
                </span>

            </button>
        @endif

    </div>

@endif
