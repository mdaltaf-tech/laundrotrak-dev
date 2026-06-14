<div class="dashboard-main-body">
    <div class="row g-3">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ url('admin/orders') }}?status=0" class="tw-no-underline">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">
                                    {{ $lang->data['pending_order'] ?? 'Pending Orders' }}</p>
                                <h6 class="mb-0">{{ $pending_count }}</h6>
                                <small class="text-secondary-light">
                                    Click to view
                                </small>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="game-icons:basket" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ url('admin/orders') }}?status=1" class="tw-no-underline">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">
                                    {{ $lang->data['processing_order'] ?? 'Processing Order' }}</p>
                                <h6 class="mb-0"> {{ $processing_count }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="material-symbols:hub-outline"
                                    class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>

                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ url('admin/orders') }}?status=2" class="tw-no-underline">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">
                                    {{ $lang->data['ready_to_deliver'] ?? 'Ready To Deliver' }}</p>
                                <h6 class="mb-0">{{ $ready_count }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ion:thumbs-up" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ url('admin/orders') }}?overdue=1" class="tw-no-underline">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">
                                    {{ $lang->data['overdue_orders'] ?? 'Delayed Orders' }}
                                </p>
                                <h6 class="mb-0">{{ $delayedOrders }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="mdi:alert-circle" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ url('admin/orders') }}?overdue=1" class="tw-no-underline">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">
                                    {{ $lang->data['overdue_orders'] ?? 'Overdue Pickup' }}
                                </p>
                                <h6 class="mb-0">{{ $overduePickups }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-warning rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="mdi:clock-alert" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ url('admin/orders') }}?status=3" class="tw-no-underline">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-medium mb-1">
                                    Delivered Orders
                                </p>
                                <h6 class="mb-0">
                                    {{ $delivered_count }}
                                </h6>
                            </div>

                            <div class="w-50-px h-50-px bg-success rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon
                                    icon="mdi:check-circle"
                                    class="text-white text-2xl">
                                </iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ url('admin/orders') }}?paid_filter=0" class="tw-no-underline">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-medium mb-1">
                                    Unpaid Orders
                                </p>

                                <h6 class="mb-0">
                                    {{ $unpaid_count }}
                                </h6>
                            </div>

                            <div class="w-50-px h-50-px bg-danger rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon
                                    icon="mdi:cash-remove"
                                    class="text-white text-2xl">
                                </iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card shadow-none border h-100">
                <div class="card-body p-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="fw-medium mb-1">
                                Today's Collection
                            </p>

                            <h6 class="mb-0">
                                ₹{{ number_format($today_collection,2) }}
                            </h6>
                        </div>

                        <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon
                                icon="mdi:cash"
                                class="text-white text-2xl">
                            </iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gy-4 mt-1">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h6 class="text-lg mb-0">
                            Tomorrow's Delivery
                        </h6>
                        <div class="text-secondary-light small mt-1">
                            {{ $totalTomorrowOrders }} Orders • {{ $tomorrowGarments }} Garments
                        </div>

                        @if($totalTomorrowOrders > 8)
                            <a href="{{ url('admin/orders') }}"
                            class="text-primary small">
                                View All
                            </a>
                        @endif
                    </div>
                    <div class="tw-grid tw-mt-4 tw-grid-cols-1 lg:tw-grid-cols-2 xl:tw-grid-cols-4 align-items-center tw-gap-2">
                       @foreach ($orders as $item)
                        <a href="{{ url('admin/orders/view/'.$item->id) }}" class="text-decoration-none">
                            <div class="bg-neutral-50 p-16 radius-8" style="cursor:pointer;">
                                <div class="tw-flex tw-justify-between tw-items-start">
                                    <div>
                                        <div class="tw-font-bold text-primary-light">
                                            {{ $item->order_number }}
                                        </div>

                                        <div
                                            class="text-sm text-secondary-light fw-normal tw-flex tw-items-center tw-gap-2 tw-mt-1">
                                            <iconify-icon icon="mdi:user-outline"
                                                class="text-primary-light"></iconify-icon>

                                            {{ $lang->data['customer'] ?? 'Customer' }}:

                                            <span class="tw-font-bold">
                                                {{ $item->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        @if($item->status == 0)
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary">
                                                Pending
                                            </span>

                                        @elseif($item->status == 1)
                                            <span class="badge bg-warning-subtle text-warning border border-warning">
                                                Processing
                                            </span>

                                        @elseif($item->status == 2)
                                            <span class="badge bg-success-subtle text-success border border-success">
                                                Ready
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="tw-my-3 tw-space-y-1">
                                    <div class="text-sm text-secondary-light">
                                        Garments :
                                        <span class="tw-font-bold">
                                            {{ $item->garment_count }}
                                        </span>
                                    </div>
                                    @if($item->balance_amount > 0)
                                        <div class="text-sm">
                                            <span class="text-secondary-light">
                                                Balance :
                                            </span>
                                            <span class="fw-bold text-danger">
                                                ₹{{ number_format($item->balance_amount,2) }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="text-success text-sm fw-semibold">
                                            Fully Paid
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-12 d-flex align-items-center justify-content-between gap-10">
                                    <div class="d-flex align-items-center justify-content-between gap-10">
                                        <iconify-icon icon="solar:calendar-outline"
                                            class="text-primary-light"></iconify-icon>
                                        <span
                                            class="start-date text-secondary-light">{{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y') }}</span>
                                    </div>

                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @if(count($orders) <= 0)
                    <x-empty-item/>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12">
    <div class="row gy-4 mt-3">
    <div class="col-xl-5">
        <div class="card h-auto">
            <div class="card-body">
                <h6 class="mb-3">
                    ⚠ Delayed Orders
                </h6>
                <div class="tw-space-y-2">
                    @foreach($delayedOrderList as $order)
                        <a href="{{ url('admin/orders/view/'.$order->id) }}" class="tw-no-underline">
                            <div class="bg-neutral-50 p-16 radius-8">
                                <div class="tw-flex tw-justify-between tw-items-start">
                                    <div class="tw-font-bold text-primary-light">
                                        {{ $order->order_number }}
                                    </div>
                                    <span class="badge bg-warning">
                                        Processing
                                    </span>
                                </div>
                                <div class="tw-mt-2 text-sm">
                                    <div>
                                        Customer :
                                        <span class="tw-font-bold">
                                            {{ $order->customer_name }}
                                        </span>
                                    </div>
                                    <div>
                                        Garments :
                                        <span class="tw-font-bold">
                                            {{ $order->garment_count }}
                                        </span>
                                    </div>
                                    @if($order->balance_amount > 0)
                                        <div class="text-danger">
                                            Balance :
                                            ₹{{ number_format($order->balance_amount,2) }}
                                        </div>
                                    @endif
                                    <div class="text-danger">
                                        {{ \Carbon\Carbon::parse($order->delivery_date)->diffInDays(today()) }}
                                        day(s) overdue
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    @if($delayedOrderList->count() == 0)
                        <div class="text-center text-muted py-4">
                            No delayed orders 🎉
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>

    <div class="col-xl-7">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">
                    📦 Overdue Pickups
                </h6>
                <div class="tw-space-y-2">
                    @foreach($overduePickupList as $order)
                        <a href="{{ url('admin/orders/view/'.$order->id) }}" class="tw-no-underline">
                                <div class="bg-neutral-50 p-16 radius-8">
                                    <div class="tw-flex tw-justify-between tw-items-start">
                                        <div class="tw-font-bold text-primary-light">
                                            {{ $order->order_number }}
                                        </div>
                                        <span class="badge bg-success">
                                            Ready
                                        </span>
                                    </div>
                                    <div class="tw-mt-2 text-sm">
                                        <div>
                                            Customer :
                                            <span class="tw-font-bold">
                                                {{ $order->customer_name }}
                                            </span>
                                        </div>

                                        <div>
                                            Garments :
                                            <span class="tw-font-bold">
                                                {{ $order->garment_count }}
                                            </span>
                                        </div>

                                        @if($order->balance_amount > 0)

                                            <div class="text-danger">

                                                Balance :
                                                ₹{{ number_format($order->balance_amount,2) }}

                                            </div>

                                        @endif

                                        <div class="text-warning">

                                            Ready since
                                            {{ \Carbon\Carbon::parse($order->delivery_date)->diffInDays(today()) }}
                                            days

                                        </div>

                                    </div>

                                </div>

                            </a>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</div>

</div>
</div>
    @push('js')
        <script>
        var chartdata = document.getElementById("chartdata").value;
        var options = {
                series: JSON.parse(chartdata),
                labels: ['Pending', 'Processing', 'Ready to Deliver', 'Delivered', 'Returned'],
                legend: {
                    show: false
                },
                colors: ['#8392ab', '#faae42', '#2dce89', '#0083ff', '#f5365c'],

                chart: {
                    type: 'donut',
                    height: 270,
                    sparkline: {
                        enabled: true // Remove whitespace
                    },
                    margin: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },

                },
                stroke: {
                    width: 0,
                },
                dataLabels: {
                    enabled: false
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
            };
            var chart = new ApexCharts(document.querySelector("#userOverviewDonutChart"), options);
            chart.render();
        </script>
    @endpush
</div>
