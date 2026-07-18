<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <button class="btn btn-link p-0 text-decoration-none fw-semibold" type="button" data-bs-toggle="collapse"
            data-bs-target="#additionalNotes">

            <iconify-icon icon="mdi:note-edit-outline" class="me-1"></iconify-icon>

            Add Additional Notes

        </button>

        <div class="collapse mt-3" id="additionalNotes">

            <label class="form-label">
                Notes
            </label>

            <textarea rows="3" class="form-control" wire:model.defer="remarks"
                placeholder="Enter any remarks for today's closing (optional)...">
            </textarea>

        </div>

    </div>
</div>
