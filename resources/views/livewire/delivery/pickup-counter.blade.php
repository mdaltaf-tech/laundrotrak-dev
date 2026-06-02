<div class="dashboard-main-body">
    <div class="card h-100 p-0">
        <div class="tw-py-1.5 tw-px-3 bg-base d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <form class="navbar-search">
                    <input type="text"
                        class="bg-base tw-px-3 tw-py-1.5 w-auto"
                        placeholder="Search Order / Customer / Phone"
                        wire:model.live.debounce.300ms="search">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
            </div>
        </div>
        <div class="tw-p-0">

@foreach($orders as $order)

@php

$ready = $order->articles
->where('status',2)
->count();

$total = $order->articles
->count();

$delivered = $order->articles
->where('status',3)
->count();

$remaining = $total - $delivered;

$paidAmount = $order->payments
->sum('received_amount');

$pendingAmount = max(
    0,
    $order->total - $paidAmount
);

@endphp

<div class="card garment-card pickup-order-card mb-3">

<div class="card-body">

<div class="row align-items-center">

    <div class="col-md-4">

        <div class="fw-bold fs-5">
            {{ $order->order_number }}
        </div>

        <div>
            {{ $order->customer_name }}
        </div>

        <div class="text-muted">
            {{ $order->phone_number }}
        </div>

        <div class="mt-2 d-flex gap-2 flex-wrap">

            <span class="badge bg-info text-white tw-text-xs">
                Ready : {{ $ready }}/{{ $total }}
            </span>

            <span class="badge bg-dark text-white tw-text-xs">
                Delivered : {{ $delivered }}/{{ $total }}
            </span>

            <span class="badge bg-warning text-dark tw-text-xs">
                Remaining : {{ $remaining }}
            </span>

        </div>

    </div>


    <div class="col-md-4 text-md-center text-start mt-3 mt-md-0">

        @if($pendingAmount>0)

            <span class="badge bg-danger tw-text-xs tw-px-2 tw-py-1">
                Pending : ₹{{ number_format($pendingAmount,2) }}
            </span>

        @else

            <span class="badge bg-success">
                Paid
            </span>

        @endif

    </div>


    <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">

        <button
            class="btn btn-success rounded-pill btn-sm">

            Send WhatsApp

        </button>

    </div>

</div>

</div>

</div>

</div>

@endforeach

</div>

</div>

</div>
