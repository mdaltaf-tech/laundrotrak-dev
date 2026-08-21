<x-filter-bar>
    <div>
        <label class="form-label mb-1">
            Report Month
        </label>
        <input type="month" class="form-control" wire:model.live="month">
    </div>
    <button type="button" class="btn btn-success" wire:click="export">
        Export
    </button>
</x-filter-bar>
