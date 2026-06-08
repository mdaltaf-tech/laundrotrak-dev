<div class="dashboard-main-body">

    <div class="card h-100 p-0">

        <div class="tw-py-1.5 tw-px-3 bg-base d-flex align-items-center flex-wrap gap-3 justify-content-between">

            <div class="d-flex align-items-center flex-wrap gap-3">

                <form class="navbar-search">

                    <input
                        type="text"
                        class="bg-base tw-px-3 tw-py-1.5 w-auto"
                        placeholder="Search Order / Customer / Phone"
                        wire:model.live.debounce.300ms="search">

                    <iconify-icon
                        icon="ion:search-outline"
                        class="icon">
                    </iconify-icon>

                </form>

            </div>

        </div>

        <div class="p-3">

            @foreach($orders as $order)

                @php
                    $paid=\App\Models\Payment::where(
                        'order_id',
                        $order->id
                    )->sum('received_amount');

                    $balance=
                    $order->total-$paid;

                    $ready=
                    \App\Models\OrderArticle::where(
                        'order_id',
                        $order->id
                    )
                    ->where(
                        'status',
                        2
                    )
                    ->count();

                    $delivered=
                    \App\Models\OrderArticle::where(
                        'order_id',
                        $order->id
                    )
                    ->where(
                        'status',
                        3
                    )
                    ->count();

                    $totalArticles =
                    \App\Models\OrderArticle::where(
                        'order_id',
                        $order->id
                    )->count();

                    $remaining = $totalArticles - $delivered;
                @endphp

       <div class="row align-items-center">

    {{-- Left --}}
    <div class="col-md-8">

        <div class="fw-semibold text-primary-light">
            {{ $order->order_number }}
        </div>

        <div class="text-neutral-600 tw-text-sm">
            {{ $order->customer_name }}
        </div>

        <div class="text-neutral-500 tw-text-xs">
            {{ $order->phone_number }}
        </div>
        <div class="mt-2 d-flex gap-2 flex-wrap">
            <span class="badge bg-info text-white tw-text-xs">
                Ready : {{ $ready }}/{{ $totalArticles }}
            </span>
            <span class="badge bg-dark text-white tw-text-xs">
                Delivered : {{ $delivered }}/{{ $totalArticles }}
            </span>
            <span class="badge bg-warning text-dark tw-text-xs">
                Remaining : {{ $remaining }}
            </span>
        </div>
    </div>


    {{-- Right --}}
    <div class="col-md-4 text-end">

        <div class="d-flex flex-column align-items-end justify-content-center h-100">

            @if($balance>0)

                <span class="badge bg-danger mb-2 tw-text-xs">

                    Pending :
                    {{ getFormattedCurrency($balance) }}

                </span>

            @endif


            @if($ready>0)

                <button class="btn rounded-pill btn-primary-100 text-primary-600 radius-8 tw-text-xs tw-py-1 tw-px-2" wire:click="openDelivery({{ $order->id }})">
                    Open Delivery
                </button>

            @else

                <span class="badge bg-secondary">

                    No Ready Garments

                </span>

            @endif

        </div>

    </div>

</div>
            @endforeach

        </div>

    </div>

    <div class="modal fade"
     id="deliveryModal"
     tabindex="-1"
     wire:ignore.self>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">

                <h5 class="modal-title">
                    Ready Garments
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            {{-- Body --}}
            <div class="modal-body">

                @if(count($deliveryArticles) > 0)

                    <div class="form-check mb-3">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            wire:model.live="selectAll"
                            id="selectAll">

                        <label
                            class="form-check-label"
                            for="selectAll">

                            Select All

                        </label>

                    </div>

                    @foreach($deliveryArticles as $item)

                    <div class="card mb-2 p-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="fw-bold">
                                    {{ $item->tag_no ?? $item->tag_number }}
                                </div>

                                <div>
                                    {{ $item->article_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $item->service_name }}
                                </small>

                            </div>

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="{{ $item->id }}"
                                    wire:model.live="selectedArticles">

                            </div>

                        </div>

                    </div>

                    @endforeach

                @else

                    <div class="text-center py-4 text-muted">
                        No ready garments available
                    </div>

                @endif

            </div>

            {{-- Footer --}}
            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Close

                </button>

                <button
                    class="btn btn-success"
                    wire:click="deliverSelected"
                    @if(empty($selectedArticles)) disabled @endif>

                    Deliver Selected

                </button>

            </div>

        </div>

    </div>

</div>

    @script

    <script>

        $wire.on(
            'open-delivery-modal',
            ()=>{

                let modalEl =
                    document.getElementById('deliveryModal');

                let modal =
                    bootstrap.Modal.getOrCreateInstance(
                        modalEl
                    );

                modal.show();

            }
        );

        document.addEventListener('livewire:init', () => {

            Livewire.on('close-delivery-modal', () => {

            let modalEl =
                document.getElementById('deliveryModal');

            let modal =
                bootstrap.Modal.getInstance(modalEl);

            if(modal){
                modal.hide();
            }

            setTimeout(() => {

                document
                    .querySelectorAll('.modal-backdrop')
                    .forEach(el => el.remove());

                document.body.classList.remove(
                    'modal-open'
                );

                document.body.removeAttribute(
                    'style'
                );

            },200);

        });

        });
    </script>

    @endscript

</div>
