<div class="dashboard-main-body">
    <div class="card h-100 p-0 radius-12">
        <div class="tw-py-1.5 tw-px-3 bg-base d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <form class="navbar-search">
                    <input type="text" class="bg-base h-40-px w-auto" placeholder="Search Here"
                        wire:model.live="search_query">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
            </div>
            @can('service_category_create')
                <button type="button"
                    class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#createModal" wire:click="resetFields()">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1">
                    </iconify-icon>
                    Add New Category
                </button>
            @endcan
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th class="text-center">Services</th>
                            <th class="text-center">Sort</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->category_name }}</td>
                                <td class="text-center">
                                    {{ $item->services_count }}
                                </td>

                                <td class="text-center">
                                    {{ $item->sort_order }}
                                </td>
                                <td class="text-center">
                                    @if ($item->is_active)
                                        <span
                                            class="badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="badge text-sm fw-semibold text-danger-600 bg-danger-100 px-20 py-9 radius-4">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        @can('service_category_edit')
                                            <button
                                                class="bg-info-100 text-info-600 bg-hover-info-200 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle"
                                                wire:click="edit({{ $item->id }})" data-bs-toggle="modal"
                                                data-bs-target="#createModal">

                                                <iconify-icon icon="lucide:edit"></iconify-icon>

                                            </button>
                                        @endcan
                                        @can('service_category_delete')
                                            <button wire:confirm="Delete this category?"
                                                wire:click="delete({{ $item->id }})"
                                                class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle">
                                                <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-empty-item />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="createModal" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content radius-16 bg-base">

                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">

                    <h5 class="modal-title">

                        {{ $category_id ? 'Edit Service Category' : 'Add Service Category' }}

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetFields()">
                    </button>

                </div>

                <div class="modal-body p-24">

                    <div class="mb-3">

                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Category Name
                        </label>

                        <input type="text" class="form-control radius-8 @error('category_name') is-invalid @enderror"
                            wire:model.defer="category_name" autofocus>

                        @error('category_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Sort Order
                        </label>

                        <input type="number" min="1"
                            class="form-control radius-8 @error('sort_order') is-invalid @enderror"
                            wire:model.defer="sort_order">

                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="form-switch switch-primary d-flex align-items-center gap-3">
                        <input class="form-check-input" type="checkbox" wire:model="is_active" id="categoryStatus">
                        <label for="categoryStatus"
                            class="form-check-label line-height-1 fw-medium text-secondary-light">
                            Is Active ?
                        </label>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <button
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8"
                            data-bs-dismiss="modal" wire:click="resetFields()">

                            Cancel

                        </button>

                        @if ($category_id)
                            <button class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8"
                                wire:click="update">

                                Update

                            </button>
                        @else
                            <button class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8"
                                wire:click="create">

                                Save

                            </button>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
