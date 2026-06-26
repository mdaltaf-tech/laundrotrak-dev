<div class="dashboard-main-body">
    <div class="card h-100 p-0">
        <div class="tw-py-1.5 tw-px-3 bg-base d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <form class="navbar-search">
                    <input type="text" class="bg-base tw-px-3 tw-py-1.5 w-auto" name="search" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model.live="search_query">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
            </div>
            @can('order_create')
            <a href="{{route('orders.pos')}}" type="button" class="btn btn-primary text-sm btn-sm radius-8 d-flex align-items-center gap-2" >
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                {{ $lang->data['add_new_order'] ?? 'Add New Order' }}
            </a>
            @endcan
        </div>
        <div class="d-flex flex-wrap gap-2 mt-3 mb-3">
            <div class="d-flex flex-wrap gap-2">
                <button
                    class="btn btn-sm rounded-pill {{ $quick_filter == '' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="resetFilters">
                    All Orders
                </button>
                <button
                    class="btn btn-sm rounded-pill {{ $quick_filter == 'today' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="filterByQuick('today')">
                    Today's Delivery
                </button>
                <button
                    class="btn btn-sm rounded-pill {{ $quick_filter == 'tomorrow' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="filterByQuick('tomorrow')">
                    Tomorrow's Delivery
                </button>
                <button
                    class="btn btn-sm rounded-pill {{ $quick_filter == 'delayed' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="filterByQuick('delayed')">
                    Delayed Orders
                </button>
                <button
                    class="btn btn-sm rounded-pill {{ $quick_filter == 'pickup_overdue' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="filterByQuick('pickup_overdue')">
                    Overdue Pickup
                </button>
                <button
                    class="btn btn-sm rounded-pill {{ $paid_filter === '0' || $paid_filter === 0 ? 'btn-danger' : 'btn-outline-danger' }}"
                    wire:click="filterByPayment(0)">
                    Unpaid
                </button>

                <button
                    class="btn btn-sm rounded-pill {{ $paid_filter === '1' || $paid_filter === 1 ? 'btn-warning' : 'btn-outline-warning' }}"
                   wire:click="filterByPayment(1)">
                    Partial
                </button>

                <button
                    class="btn btn-sm rounded-pill {{ $paid_filter === '2' || $paid_filter === 2 ? 'btn-success' : 'btn-outline-success' }}"
                    wire:click="filterByPayment(2)">
                    Paid
                </button>

                <button
                    class="btn btn-sm rounded-pill {{ $paid_filter === '3' || $paid_filter === 3 ? 'btn-info' : 'btn-outline-info' }}"
                    wire:click="filterByPayment(3)">
                    Credit
                </button>
            </div>
        </div>
        <div class="tw-p-0">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                  <thead>
                    <tr>
                      <th scope="col" class="">{{ $lang->data['order_info'] ?? 'Order Info' }}</th>
                      <th scope="col" class="">{{ $lang->data['customer'] ?? 'Customer' }}</th>
                      <th scope="col" class="">{{ $lang->data['order_amount'] ?? 'Order Amount' }}</th>
                      <th scope="col" class=""> {{ $lang->data['status'] ?? 'Status' }}</th>
                      <th scope="col" class="">{{ $lang->data['payment'] ?? 'Payment' }}</th>
                      <th scope="col" class=""> {{ $lang->data['created_by'] ?? 'Created By' }}</th>
                      <th scope="col" class="text-center">{{ $lang->data['action'] ?? 'Action' }}</th>
                    </tr>
                  </thead>
                  <tbody>
                        @foreach ($orders as $item)
                        <tr class="tw-text-xs">
                            <td>
                                <div class="tw-flex tw-flex-col">
                                    <div class="text-neutral-600">
                                        {{ $lang->data['order_id'] ?? 'Order ID' }} : <span class="tw-font-medium text-primary-light">{{ $item->order_number }}</span>
                                    </div>
                                    <div class="text-neutral-600">
                                        {{ $lang->data['order_date'] ?? 'Order Date' }} : <span class="tw-font-medium text-primary-light">{{ \Carbon\Carbon::parse($item->order_date)->format('d/m/y') }}</span>
                                    </div>
                                    <div class="text-neutral-600">
                                        {{ $lang->data['delivery_date'] ?? 'Delivery Date' }} : <span class="tw-font-medium text-primary-light">{{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/y') }}</span>
                                    </div>
                                    <div class="text-neutral-600">
                                        Total Items :
                                        <span class="tw-font-medium text-primary-light">
                                            {{ $item->total_items }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="">
                                <p>{{ $item->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}</p>
                                <p>{{$item->phone_number ? getCountryCode() : ''}}{{$item->phone_number ? (int)$item->phone_number : ''}}</p>
                            </td>
                            <td class="text-primary">
                                {{ getFormattedCurrency($item->total) }}
                            </td>
                            <td class="">
                                @if ($item->status == 0)
                                <span class="badge  fw-semibold text-neutral-600 bg-neutral-200 px-20 py-9 radius-4 text-white">
                                    {{ $lang->data['pending'] ?? 'Pending' }}
                                </span>
                                @elseif($item->status == 1)
                                <span class="badge  fw-semibold text-warning-600 bg-warning-100 px-20 py-9 radius-4 text-white">
                                    {{ $lang->data['processing'] ?? 'Processing' }}
                                </span>
                                @elseif($item->status ==2)
                                <span class="badge  fw-semibold text-info-600 bg-info-100 px-20 py-9 radius-4 text-white">
                                    {{ $lang->data['ready_to_deliver'] ?? 'Ready To Deliver' }}
                                </span>
                                @elseif($item->status == 3)
                                <span class="badge  fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                    {{ $lang->data['delivered'] ?? 'Delivered' }}
                                </span>
                                @elseif($item->status == 4)
                                <span class="badge  fw-semibold text-danger-600 bg-danger-100 px-20 py-9 radius-4 text-white">
                                    {{ $lang->data['returned'] ?? 'Returned' }}
                                </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $paidamount = \App\Models\Payment::active()
                                        ->where('order_id', $item->id)
                                        ->sum('received_amount');
                                @endphp
                                <div class="tw-flex tw-flex-col">
                                    <div class="text-neutral-600">
                                        {{ $lang->data['total_amount'] ?? 'Total Amount' }} : <span class="tw-font-medium text-primary-light">{{ getFormattedCurrency($item->total) }}</span>
                                    </div>
                                    <div class="text-neutral-600">
                                        @php
                                            $current_paid_amount = \App\Models\Payment::active()
                                                ->where('order_id', $item->id)
                                                ->sum('received_amount');
                                        @endphp
                                        {{ $lang->data['paid_amount'] ?? 'Paid Amount' }} : <span class="tw-font-medium text-primary-light"> {{ getFormattedCurrency($current_paid_amount) }}</span>
                                        @if($item->was_delivered_on_credit && $item->balance_amount > 0)
                                            <div class="text-danger fw-semibold">
                                                Credit Outstanding :
                                                {{ getFormattedCurrency($item->balance_amount) }}
                                            </div>
                                            @if($item->credit_delivered_at)
                                                <div class="text-warning fw-medium">
                                                    Credit Since :
                                                    {{
                                                        \Carbon\Carbon::parse($item->credit_delivered_at)
                                                        ->startOfDay()
                                                        ->diffInDays(today()->startOfDay())
                                                    }}
                                                    Days
                                                </div>
                                            @endif
                                            <span class="badge bg-danger-100 text-danger-600">
                                                CREDIT
                                            </span>
                                        @endif
                                    </div>
                                    @if ($paidamount < $item->total)
                                        @if($item->status != 4)
                                        @can('payment_create')
                                            <div class="tw-mt-1">
                                                <button type="button" class="btn rounded-pill btn-success-100 text-success-600 radius-8 tw-text-xs tw-py-1 tw-px-2 " data-bs-toggle="modal" data-bs-target="#exampleModal" wire:click="payment({{ $item->id }})">{{ $lang->data['add_payment'] ?? 'Add Payment' }}</button>
                                            </div>
                                        @endcan
                                        @endif
                                    @else
                                    @if($item->status != 4)
                                    <div class="tw-mt-1">
                                        <button type="button" class="btn rounded-pill btn-neutral-300 text-neutral-600 radius-8 tw-text-xs tw-py-1 tw-px-2 " >{{ $lang->data['fully_paid'] ?? 'Fully Paid' }}</button>
                                    </div>
                                    @endif
                                    @endif

                                </div>
                            </td>
                            <td class="">
                                {{ $item->user->name ?? "" }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    @can('order_view')
                                    <a href="{{route('order.view',$item->id)}}" type="button" class="bg-success-100 text-success-600 bg-hover-success-200 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle" >
                                        <iconify-icon icon="lucide:eye" class="menu-icon"></iconify-icon>
                                    </a>
                                    @endcan
                                    <a
                                        href="{{ route('orders.print-all-tags', ['order' => $item->id]) }}"
                                        target="_blank"
                                        class="w-36-px h-36s-px bg-warning-focus text-warning-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                        title="Print All Garment Tags"
                                    >
                                        <iconify-icon icon="solar:tag-horizontal-bold"></iconify-icon>
                                    </a>
                                    @can('order_print')
                                    <a href="{{route('order.print',$item->id)}}" target="_blank" class="bg-warning-100 text-warning-600 bg-hover-warning-200 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle" >
                                        <iconify-icon icon="material-symbols-light:print-outline" class="menu-icon tw-text-xl"></iconify-icon>
                                    </a>
                                    @endcan
                                    @can('order_edit')
                                    <a href="{{route('orders.pos.edit',$item->id)}}" class="bg-info-100 text-info-600 bg-hover-info-200 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle" >
                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                    </a>
                                    @endcan
                                    @can('order_delete')
                                    <button type="button" wire:click.prevent="deleteOrder({{$item->id}})" class="remove-item-button bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                  </tbody>
                </table>
                @if(count($orders) == 0)
                    <x-empty-item/>
                @endif
                @if($hasMorePages)
                <div x-data="{
                        init () {
                            let observer = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    if (entry.isIntersecting) {
                                        @this.call('loadOrders')
                                        console.log('loading...')
                                    }
                                })
                            }, {
                                root: null
                            });
                            observer.observe(this.$el);
                        }
                    }" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4">
                    <div class="text-center pb-2 d-flex justify-content-center align-items-center">
                    {{ $lang->data['loading'] ?? 'Loading...' }}
                        <div class="spinner-grow d-inline-flex mx-2 text-primary" role="status">
                            <span class="visually-hidden"> {{ $lang->data['loading'] ?? 'Loading...' }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">{{ $lang->data['payment_details'] ?? 'Payment Details' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @if ($order)
                    <div class="modal-body p-24">
                        <form action="#">
                            <div class="row">
                                <div class="col-12">
                                    <div class="">
                                        <ul>
                                            <li class="d-flex align-items-center gap-1 mb-12 tw-justify-between tw-w-full">
                                                <span class="text-md fw-semibold text-primary-light">{{ $lang->data['customer'] ?? 'Customer' }} :</span>
                                                <span class="text-secondary-light fw-medium">{{ $customer_name }}</span>
                                            </li>
                                            <li class="d-flex align-items-center gap-1 mb-12 tw-justify-between ">
                                                <span class="text-md fw-semibold text-primary-light"> {{ $lang->data['order_id'] ?? 'Order ID' }} :</span>
                                                <span class="text-secondary-light fw-medium">{{ $order->order_number }}</span>
                                            </li>
                                            <li class="d-flex align-items-center gap-1 mb-12 tw-justify-between">
                                                <span class="text-md fw-semibold text-primary-light">  {{ $lang->data['order_date'] ?? 'Order Date' }} :</span>
                                                <span class="text-secondary-light fw-medium">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</span>
                                            </li>
                                            <li class="d-flex align-items-center gap-1 mb-12 tw-justify-between">
                                                <span class="text-md fw-semibold text-primary-light">  {{ $lang->data['delivery_date'] ?? 'Delivery Date' }} :</span>
                                                <span class="text-secondary-light fw-medium">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</span>
                                            </li>
                                            <li class="d-flex align-items-center gap-1 mb-12 tw-justify-between">
                                                <span class="text-md fw-semibold text-primary-light"> {{ $lang->data['order_amount'] ?? 'Order Amount' }} :</span>
                                                <span class="text-secondary-light fw-medium"> {{ getFormattedCurrency($order->total) }}</span>
                                            </li>
                                            <li class="d-flex align-items-center gap-1 mb-12 tw-justify-between">
                                                <span class="text-md fw-semibold text-primary-light"> {{ $lang->data['paid_amount'] ?? 'Paid Amount' }} :</span>
                                                <span class="text-secondary-light fw-medium"> {{ getFormattedCurrency($current_paid_amount) }}</span>
                                            </li>
                                            <li class="d-flex align-items-center gap-1 tw-justify-between">
                                                <span class="text-md fw-semibold text-primary-light"> {{ $lang->data['balance'] ?? 'Balance' }} :</span>
                                                <span class="text-secondary-light fw-medium">
                                                    {{ getFormattedCurrency($balance) }}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 tw-my-6">
                                    <hr>
                                </div>
                                <div class="col-12 mb-20 ">
                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control radius-8" placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}" wire:model="paid_amount" >
                                    @error('paid_amount')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-20 ">
                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['payment_type'] ?? 'Payment Type' }} <span class="text-danger">*</span></label>
                                    <select  class="form-select radius-8" wire:model="payment_mode">
                                        <option value="">
                                            {{ $lang->data['choose_payment_type'] ?? 'Choose Payment Type' }}
                                        </option>
                                        <option class="select-box" value="1">
                                            {{ $lang->data['cash'] ?? 'Cash' }}
                                        </option>
                                        <option class="select-box" value="2">
                                            {{ $lang->data['upi'] ?? 'UPI' }}
                                        </option>
                                        <option class="select-box" value="3">
                                            {{ $lang->data['card'] ?? 'Card' }}
                                        </option>
                                        <option class="select-box" value="4">
                                            {{ $lang->data['cheque'] ?? 'Cheque' }}
                                        </option>
                                        <option class="select-box" value="5">
                                            {{ $lang->data['bank_transfer'] ?? 'Bank Transfer' }}
                                        </option>
                                    </select>
                                    @error('payment_mode')
                                    <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        {{ $lang->data['payment_date'] ?? 'Payment Date' }}
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control radius-8"
                                        wire:model="payment_date">

                                    @error('payment_date')
                                        <span class="error text-danger d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                    <small class="text-muted d-block mt-1">
                                        Use actual payment receipt date.
                                    </small>
                                </div>
                                <div class="col-12 mb-20">
                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['notes'] ?? 'Notes' }} </label>
                                    <textarea class="form-control radius-8" placeholder="{{ $lang->data['enter_notes'] ?? 'Enter Notes' }}"  wire:model="note"></textarea>
                                    @error('note')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                                    <button data-bs-dismiss="modal" type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                                    {{ $lang->data['cancel'] ?? 'Cancel' }}
                                    </button>
                                    <button type="button" wire:click.prevent="addPayment()" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                                    {{ $lang->data['save'] ?? 'Save' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-tag-print', (event) => {
            const url = event.url;

            if (!url) {
                console.error('Tag print URL was not received.', event);
                return;
            }

            window.open(url, '_blank');
        });
    });
</script>
@endpush
