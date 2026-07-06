<div class="tw-overflow-x-clip" x-data="posFunction">
    <div class="tw-w-full tw-bg-white tw-flex tw-justify-between tw-items-center ">
        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-2 tw-px-3 tw-py-2 lg:tw-items-center">
            <div class="tw-flex tw-gap-2 tw-shrink-0">
                <a href="{{ route('orders') }}" class="no-underline">
                    <button style="width:130px;"
                        class="bg-primary-600 tw-text-white tw-text-xs radius-8
                        tw-h-10
                        tw-w-28
                        tw-flex
                        tw-items-center
                        tw-justify-center
                        gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="tw-size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        <span>{{ $lang->data['back'] ?? 'Back' }}</span>
                    </button>
                </a>

                <template x-if="detached">
                    <button style="width:130px;"
                        class="tw-h-10
                        tw-w-28
                        bg-primary-600
                        tw-rounded-md
                        tw-text-white
                        tw-flex
                        tw-items-center
                        tw-justify-center
                        tw-gap-1.5
                        tw-border-0
                        tw-shadow-md"
                        @click="shown = !shown">
                        <template x-if="!shown">
                            <div class="tw-flex  tw-items-center tw-gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="tw-size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                <span class="text-sm ">{{ $lang->data['cart'] ?? 'Cart' }}</span>
                            </div>
                        </template>
                        <template x-if="shown">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="tw-size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>

                                <span class="text-sm ">{{ $lang->data['products'] ?? 'Products' }}</span>
                            </div>
                        </template>
                    </button>
                </template>
            </div>
            <div class="icon-field has-validation tw-w-full lg:tw-flex-1">
                <span class="icon tw-translate-y-[2px]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </span>
                <input type="text" class="form-control tw-w-full" wire:model.live="search_query"
                    placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" required="">
            </div>
        </div>
        <button type="button" data-theme-toggle
            class="w-40-px h-40-px bg-neutral-200 rounded-circle tw-hidden justify-content-center align-items-center"></button>
    </div>

    <div class="tw-w-full tw-h-full tw-flex lg:tw-flex-row tw-flex-col tw-relative tw-mt-0.5 lg:tw-gap-0">
        <div
            class="lg:tw-w-1/2 lg:tw-flex-1 tw-w-full tw-flex tw-flex-col tw-h-[calc(100vh-4rem)] tw-p-2 tw-bg-white p-16">
            <div class="tw-flex tw-flex-col">

                <div class="d-flex flex-nowrap gap-2 mb-2 overflow-auto category-scroll">
                    <button wire:click="changeCategory(null)"
                        class="btn {{ !$selectedCategory ? 'btn-primary' : 'btn-outline-secondary' }} text-nowrap">

                        All
                        <small class="ms-1">({{ collect($this->categories)->sum('services_count') }})</small>
                    </button>

                    @foreach ($this->categories as $category)
                        <button wire:click="changeCategory({{ $category->id }})"
                            class="btn {{ $selectedCategory == $category->id ? 'btn-primary' : 'btn-outline-secondary' }} text-nowrap">

                            @if ($selectedCategory == $category->id)
                                <i class="fas fa-check-circle me-1"></i>
                            @endif

                            {{ $category->category_name }}

                            <small class="ms-1">
                                ({{ $category->services_count ?? 0 }})
                            </small>

                        </button>
                    @endforeach

                </div>
                <div
                    class="tw-w-full tw-h-[calc(100vh-9rem)] tw-overflow-y-scroll custom-scroll tw-mt-2 tw-flex tw-p-0.5">
                    <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-2 tw-h-fit tw-w-full">
                        @foreach ($services as $item)
                            <a type="button" class=" hover:tw-translate-y-1" data-bs-toggle="modal"
                                data-bs-target="#servicetype" wire:click="selectService({{ $item->id }})">
                                <div class="card bg-neutral-100">
                                    <div
                                        class="card-body tw-flex tw-items-center tw-justify-center tw-flex-col tw-rounded-md  tw-overflow-clip tw-ring-1 tw-ring-neutral-200">
                                        <img src="{{ asset('assets/img/service-icons/' . $item->icon) }}"
                                            class="tw-h-24 tw-w-24 tw-object-center tw-rounded-md tw-py-2">
                                        <div
                                            class="tw-px-2 tw-py-1.5  tw-w-full tw-flex tw-justify-center tw-items-center">
                                            <div class="tw-text-sm tw-text-center tw-truncate tw-font-bold tw-w-[90%] ">
                                                {{ $item->service_name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:tw-w-1/2
            lg:tw-flex-1
            tw-h-[calc(100vh-4rem)]
            tw-bg-white
            p-16
            tw-flex
            tw-flex-col"
            :class="shown && detached ? 'tw-absolute tw-inset-0 tw-w-full' :
                'tw-hidden lg:tw-block tw-shrink-0'">
            <div
                class="tw-flex tw-flex-col lg:tw-flex-row tw-items-start lg:tw-items-center tw-gap-3 lg:tw-gap-8 tw-w-full">
                <div class="tw-flex tw-w-full lg:tw-w-auto tw-flex-col" x-data="{}">
                    <div class="tw-text-sm">{{ $lang->data['order'] ?? 'Order' }} : <span
                            class="tw-font-bold">#{{ $order_id }}</span></div>
                    <div class="tw-flex tw-items-start lg:tw-items-center tw-gap-2 tw-flex-wrap">
                        <div class="tw-text-sm tw-relative">
                            {{ $lang->data['date'] ?? 'Date' }} : <span
                                class="tw-font-bold">{{ $date }}</span>
                            <input type="date" wire:model.live="date" name=""
                                class="tw-opacity-0 tw-absolute tw-pointer-events-none" x-ref="date">
                        </div>

                        <button @click="$refs.date.showPicker()"
                            class="tw-px-2 tw-py-1 bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path
                                    d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z" />
                                <path
                                    d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                            </svg>
                        </button>
                    </div>

                    <div class="tw-flex tw-items-start lg:tw-items-center tw-gap-2 tw-flex-wrap">
                        <div class="tw-text-sm tw-relative">
                            {{ $lang->data['delivery_date'] ?? 'Delivery Date' }} : <span
                                class="tw-font-bold">{{ $delivery_date }}</span>
                            <input type="date" wire:model.live="delivery_date" name=""
                                class="tw-opacity-0 tw-absolute tw-pointer-events-none" x-ref="delivery_date">
                        </div>

                        <button @click="$refs.delivery_date.showPicker()"
                            class="tw-px-2 tw-py-1 bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path
                                    d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z" />
                                <path
                                    d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                            </svg>
                        </button>
                    </div>

                </div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-w-full tw-shrink">
                    <div class="icon-field  tw-relative tw-w-full tw-items-center">
                        <span class="icon -tw-translate-y-[2px]">
                            <iconify-icon icon="f7:person"></iconify-icon>
                        </span>
                        <input type="text"
                            class="form-control @error('paid_amount_customer') is-invalid   @enderror"
                            placeholder="@if (!$selected_customer) {{ $lang->data['select_a_customer'] ?? 'Select A Customer' }} @else {{ $selected_customer->name }} @endif"
                            required="" wire:model.live.debounce.300ms="customer_query">
                        @if ($customers && count($customers) > 0)
                            <div
                                class="tw-absolute tw-top-[100%] tw-left-0 tw-right-0 tw-z-[9999]
                                    tw-bg-white tw-rounded-lg tw-shadow-lg
                                    tw-max-h-48 tw-overflow-y-auto">
                                @foreach ($customers as $row)
                                    <li class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900"
                                        wire:click="selectCustomer({{ $row->id }})">
                                        <div class="tw-font-medium">
                                            {{ $row->name }}
                                        </div>

                                        <div class="tw-text-xs tw-text-gray-500">
                                            {{ $row->phone }}
                                        </div>
                                    </li>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @can('customer_create')
                        <button type="button" data-bs-toggle="modal" data-bs-target="#addcustomer"
                            class="tw-min-w-[48px] tw-px-3 tw-py-3 bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-fill-add" viewBox="0 0 16 16">
                                <path
                                    d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path
                                    d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                            </svg>
                        </button>
                    @endcan
                </div>
            </div>
            <div
                class="tw-w-full
                    tw-flex
                    tw-flex-col
                    tw-flex-1
                    tw-min-h-0
                    tw-mt-4
                    tw-rounded-lg tw-overflow-visible tw-border @error('error') tw-border-red-500 @else tw-border-neutral-200 dark:tw-border-[#1b2431] @enderror tw-border-solid">
                <div class="tw-flex tw-flex-col lg:tw-w-full tw-overflow-x-auto">
                    <div class="tw-flex tw-flex-col lg:tw-w-full tw-w-[100rem]">
                        <div class="tw-flex tw-flex-col  tw-overflow-x-auto tw-w-full tw-shrink-0">
                            <table class="tw-w-full tw-text-xs tw-shrink-0 tw-h-fit ">
                                <thead class="tw-bg-[#e9ecef] dark:tw-bg-[#1b2431]">
                                    <tr>
                                        <th class="tw-py-2 tw-px-2 tw-text-xs tw-w-[10rem] lg:tw-w-[10%] tw-text-left">
                                            {{ $lang->data['service'] ?? 'Service' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[15%] tw-text-center tw-hidden lg:tw-table-cell">
                                            {{ $lang->data['color'] ?? 'Color' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[15%] tw-text-center">
                                            {{ $lang->data['price'] ?? 'Price' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[15%] tw-text-center tw-hidden lg:tw-table-cell">
                                            {{ $lang->data['rate'] ?? 'Rate' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[15%] tw-text-center">
                                            {{ $lang->data['qty'] ?? 'QTY' }}</th>

                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[10%] tw-text-center tw-hidden lg:tw-table-cell">
                                            {{ $lang->data['tax'] ?? 'Tax  ' }} ({{ $tax_percent }}%)</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[10%] tw-text-center">
                                            {{ $lang->data['total'] ?? 'Total' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[5%] tw-text-center">
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div
                            class="tw-flex tw-h-[calc(100dvh-23rem)] tw-overflow-y-auto tw-overflow-x-auto tw-w-full tw-shrink-0">
                            <table class="tw-w-full tw-text-xs tw-shrink-0 tw-h-fit">
                                <tbody>
                                    @php
                                        $currentcount = 0;
                                    @endphp
                                    @foreach ($selservices as $key => $item)
                                        <tr
                                            class="tw-border-b tw-border-neutral-200 dark:tw-border-neutral-800/50 tw-border-solid">
                                            <td class="tw-py-2 tw-px-2 lg:tw-w-[10%] tw-w-[10rem] tw-text-left">
                                                <div class="tw-flex tw-flex-col">
                                                    @php
                                                        $currentcount++;
                                                        $itemtaxtotal = 0;
                                                        $itemtotal = 0;
                                                        $localrate = 0;
                                                        if (getTaxType() == 2) {
                                                            $localrate =
                                                                $selling_price[$key] *
                                                                (100 / (100 + $tax_percent ?? 0));
                                                            $itemtotallocal =
                                                                $selling_price[$key] *
                                                                $quantity[$key] *
                                                                (100 / (100 + $tax_percent ?? 0));
                                                            $itemtaxtotal =
                                                                $selling_price[$key] * $quantity[$key] -
                                                                    $itemtotallocal ??
                                                                0;
                                                            $itemtotal = $selling_price[$key] * $quantity[$key];
                                                        } else {
                                                            $itemtotallocal = $selling_price[$key] * $quantity[$key];
                                                            $localrate = $selling_price[$key];
                                                            $itemtaxtotal = ($itemtotallocal * $tax_percent) / 100;
                                                            $itemtotal = $itemtotallocal + $itemtaxtotal;
                                                        }
                                                    @endphp
                                                    <div class="tw-text-xs tw-font-semibold">
                                                        {{ $serviceLookup[$item['service']] ?? '' }}</div>
                                                    <div class="tw-text-xs tw-font-normal text-primary-600">
                                                        [{{ $serviceTypeLookup[$item['service_type']] ?? '' }}]</div>
                                                </div>
                                            </td>
                                            <td
                                                class="tw-py-2 tw-px-1 lg:tw-w-[15%] tw-w-[10rem] tw-text-center tw-hidden lg:tw-table-cell">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    <input type="color" name=""
                                                        pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                                        class="tw-w-10 tw-h-6"
                                                        wire:model.live="colors.{{ $key }}"
                                                        wire:change="changeColor({{ $key }})">
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[15%] tw-w-[10rem] tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    <input type="text" name=""
                                                        wire:model.live="selling_price.{{ $key }}"
                                                        id=""
                                                        class="tw-ring-1 tw-px-1 tw-py-0.5 tw-rounded-md tw-w-[4rem]">
                                                </div>
                                            </td>
                                            <td
                                                class="tw-py-2 tw-px-1 lg:tw-w-[15%] tw-w-[10rem] tw-text-center tw-hidden lg:tw-table-cell">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    {{ getFormattedCurrency($localrate ?? 0) }}
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[15%] tw-w-[10rem] tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    <div
                                                        class="tw-flex tw-items-center tw-gap-2 tw-justify-center tw-text-sm">
                                                        <button wire:click="decrease({{ $key }})"
                                                            class="tw-px-2 tw-py-1 bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-dash"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8" />
                                                            </svg>
                                                        </button>
                                                        {{ $quantity[$key] }}
                                                        <button wire:click="increase({{ $key }})"
                                                            class="tw-px-2 tw-py-1 bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd"
                                                                    d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="tw-py-2 tw-px-1 lg:tw-w-[10%] tw-w-[10rem] tw-text-center tw-hidden lg:tw-table-cell">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    {{ getFormattedCurrency($itemtaxtotal ?? 0) }}
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[10%] tw-w-[10rem] tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    {{ getFormattedCurrency($itemtotal ?? 0) }}
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[5%] tw-w-[10rem] tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    <button wire:click="removeItem({{ $key }})"
                                                        class="tw-px-2 tw-py-1 tw-bg-red-500 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-trash"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                            <path
                                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-5 gap-20">
                    {{-- LEFT CARD --}}
                    <div class="summary-card">
                        {{-- <h6 class="summary-title">
                            Order Details
                        </h6> --}}
                        {{-- Addon --}}
                        <div class="summary-action-row" data-bs-toggle="modal" data-bs-target="#addons">
                            <div class="summary-icon">
                                <iconify-icon icon="solar:box-bold"></iconify-icon>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Addon</div>
                                @if (!empty($selected_addons))
                                    <small data-bs-toggle="tooltip"
                                        title="{{ count($selected_addons) }} addon(s) selected">

                                        {{ count($selected_addons) }} selected

                                    </small>
                                @endif
                            </div>
                        </div>
                        {{-- Notes --}}
                        <div class="summary-action-row" data-bs-toggle="modal" data-bs-target="#notesModal">
                            <div class="summary-icon">
                                <iconify-icon icon="solar:pen-bold"></iconify-icon>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Notes</div>
                                @if ($note)
                                    <small data-bs-toggle="tooltip" title="{{ $note }}">
                                        {{ Str::limit($note, 20) }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        {{-- Discount --}}
                        <div class="summary-action-row" data-bs-toggle="modal" data-bs-target="#discount">
                            <div class="summary-icon">
                                <iconify-icon icon="solar:tag-price-bold"></iconify-icon>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Discount</div>
                                @if ($discount)
                                    <small>
                                        {{ getFormattedCurrency($discount) }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        {{-- Charges --}}
                        <div class="summary-action-row" data-bs-toggle="modal"
                            data-bs-target="#additionalChargesModal">
                            <div class="summary-icon">
                                <iconify-icon icon="solar:bill-list-bold"></iconify-icon>
                            </div>
                            <div class="summary-content">
                                <div class="tw-flex tw-items-center tw-gap-2">
                                    <div class="summary-label">
                                        Order Charges
                                    </div>
                                    @if ($this->additionalChargeCount)
                                        <span class="additional-charge-badge">
                                            {{ $this->additionalChargeCount }}
                                        </span>
                                    @endif
                                </div>
                                @if ($this->additionalChargeCount)
                                    <small data-bs-toggle="tooltip" title="{{ $this->additionalChargeSummary }}">
                                        {{ $this->additionalChargeCount }} Charge(s)
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- RIGHT CARD --}}
                    <div class="summary-card">
                        {{-- <h6 class="summary-title">
                            Bill Summary
                        </h6> --}}
                        {{-- Existing Summary rows here --}}
                        <div class="summary-value-row">
                            <span>Sub Total</span>
                            <strong>{{ getFormattedCurrency($sub_total) }}</strong>
                        </div>
                        <div class="summary-value-row">
                            <span>Tax ({{ getTaxPercentage() }}%)</span>
                            <strong>{{ getFormattedCurrency($tax) }}</strong>
                        </div>
                        <div class="summary-value-row">
                            <span>Order Charges</span>
                            <strong>{{ getFormattedCurrency($this->orderAdditionalChargesTotal) }}</strong>
                        </div>
                        <div class="summary-value-row">
                            <span>Total Items</span>
                            <strong>{{ $this->totalItems }}</strong>
                        </div>
                        <div class="summary-total-row">
                            <span class="summary-total-label">
                                Gross Total
                            </span>
                            <span class="summary-total-value">
                                {{ getFormattedCurrency($total) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tw-flex tw-items-center tw-gap-2 tw-mt-1 tw-p-2 tw-w-full tw-h-14">
                @if (!$order)
                    <button
                        class="tw-px-2 tw-justify-center tw-font-semibold tw-py-2 tw-h-full bg-success-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-w-full tw-border-0 tw-shadow-md "
                        data-bs-toggle="modal" data-bs-target="#payment">
                        <span>{{ $lang->data['payment'] ?? 'Payment' }}</span>
                    </button>
                    <button
                        class="tw-px-2 tw-justify-center tw-font-semibold tw-py-2 tw-h-full bg-info-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-w-full tw-border-0 tw-shadow-md "
                        wire:click.prevent="save('cash')">
                        <span>{{ $lang->data['cash'] ?? 'Cash' }}</span>
                    </button>
                @endif
                <button
                    class="tw-px-2 tw-justify-center tw-font-semibold tw-py-2 tw-h-full bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-w-full tw-border-0 tw-shadow-md "
                    wire:click.prevent="save">
                    <span>{{ $lang->data['save_print'] ?? 'Save & Print' }}</span>
                </button>
                <button
                    class="tw-px-2 tw-py-2.5 tw-bg-red-500 tw-rounded-md tw-text-white tw-h-full tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md  "
                    wire:click.prevent="clearAll">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                        <path
                            d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9" />
                        <path fill-rule="evenodd"
                            d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="servicetype" tabindex="-1" role="dialog" aria-labelledby="servicetype"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">
                        {{ $lang->data['select_service_type'] ?? 'Select Service Type' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="row">
                        @foreach ($service_types as $item)
                            <div class="col-12 mb-20">
                                <div class="tw-flex tw-items-center tw-justify-between">
                                    <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border border-neutral-500"
                                                type="checkbox" id="test{{ $item['id'] }}" name="test"
                                                value="{{ $item['id'] }}"
                                                wire:model.live="selected_type.{{ $item['id'] }}">
                                        </div>
                                        <label for="test{{ $item['id'] }}"
                                            class="form-label fw-medium text-primary-light mb-0">{{ $item['service_type_name'] }}</label>
                                    </div>
                                    <div class="">{{ $item['price'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            <span>{{ $lang->data['cancel'] ?? 'Cancel' }}</span>
                        </button>
                        <button type="submit" wire:click.prevent="addItem"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            <span>{{ $lang->data['save'] ?? 'Save' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModal"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">
                        {{ $lang->data['notes_remarks'] ?? 'Notes / Remarks' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="tw-flex tw-gap-2 tw-flex-col">
                        <div class="">
                            {{ $lang->data['notes_remarks'] ?? 'Notes / Remarks' }}
                        </div>
                        <textarea rows="3" type="number" name="" id="" wire:model.live="note" class=" form-control"
                            placeholder="{{ $lang->data['enter_notes'] ?? 'Enter Notes' }}"></textarea>
                    </div>

                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            {{ $lang->data['close'] ?? 'Close' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="discount" tabindex="-1" role="dialog" aria-labelledby="discount"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">
                        {{ $lang->data['discount'] ?? 'Discount' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="tw-flex tw-gap-2 tw-flex-col">
                        <div class="">
                            {{ $lang->data['discount'] ?? 'Discount' }}
                        </div>
                        <input type="number" name="" id="" wire:model.live="discount"
                            class=" form-control" placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}">
                    </div>
                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            {{ $lang->data['close'] ?? 'Close' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Charges Modal -->
    <div class="modal fade" id="additionalChargesModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md d-flex align-items-center">
                        {{ $lang->data['order_charges'] ?? 'Order Charges' }}
                        @if ($this->additionalChargeCount)
                            <span class="badge rounded-pill bg-primary ms-2">
                                {{ $this->additionalChargeCount }}
                            </span>
                        @endif
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">

                    {{-- Add Charge Row --}}
                    <div class="row g-3 align-items-end p-10">

                        <div class="col-md-4">
                            <div class="form-floating">
                                <select
                                    wire:key="additional-charge-select-{{ $editingAdditionalChargePosition ?? 'new' }}"
                                    wire:model="orderAdditionalChargeTypeId"
                                    wire:change="changeAdditionalChargeType($event.target.value)" class="form-select">
                                    <option value="">Select Charge</option>

                                    @foreach ($this->selectableAdditionalChargeTypes as $charge)
                                        <option value="{{ $charge['id'] }}">
                                            {{ $charge['charge_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Charge</label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="number" class="form-control text-end" step="0.01" min="0"
                                    wire:model.live="orderAdditionalChargeAmount">

                                <label>Amount</label>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-floating">
                                <input type="text" maxlength="255" class="form-control"
                                    wire:model.live="orderAdditionalChargeRemarks">

                                <label>Remarks</label>
                            </div>
                        </div>

                        <div class="col-md-1">

                            <button type="button" class="btn btn-primary charge-add-btn w-100"
                                wire:click="addOrderAdditionalCharge" wire:loading.attr="disabled"
                                @disabled(blank($orderAdditionalChargeTypeId) || blank($orderAdditionalChargeAmount))>
                                <iconify-icon icon="solar:add-circle-bold"></iconify-icon>
                            </button>

                        </div>

                    </div>

                    <hr class="my-4">

                    {{-- Charges --}}
                    <div class="additional-charge-list">

                        @forelse($this->orderAdditionalCharges as $index=>$charge)
                            <div class="charge-card">

                                <div class="d-flex justify-content-between">

                                    <div>

                                        <div class="charge-title">
                                            {{ $charge['charge_name'] }}
                                        </div>

                                        @if ($charge['remarks'])
                                            <small class="text-muted">

                                                {{ $charge['remarks'] }}

                                            </small>
                                        @endif

                                    </div>

                                    <div class="text-end">

                                        <div class="charge-amount">

                                            {{ getFormattedCurrency($charge['amount']) }}

                                        </div>

                                        <div class="mt-2 d-flex justify-content-end gap-2">

                                            <button class="btn btn-light btn-sm charge-action-btn"
                                                wire:click="editOrderAdditionalCharge({{ $index }})">

                                                <iconify-icon icon="solar:pen-bold"></iconify-icon>

                                            </button>

                                            <button class="btn btn-danger btn-sm charge-action-btn"
                                                wire:click="removeOrderAdditionalCharge({{ $index }})">

                                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="additional-charge-empty">

                                <iconify-icon icon="solar:bill-list-bold"></iconify-icon>

                                <h5>No Charges Added</h5>

                                <small>
                                    Add Pickup, Delivery, Packing or any custom order charge.
                                </small>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer charge-footer">
                    <div class="charge-total-card">
                        <span>Total Charges</span>
                        <span class="charge-total-value">
                            {{ getFormattedCurrency($this->orderAdditionalChargesTotal) }}
                        </span>
                    </div>
                    <button
                        class="border border-primary-600 bg-hover-primary-200 text-primary-600 text-md px-40 py-11 radius-8"
                        data-bs-dismiss="modal">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addons" tabindex="-1" role="dialog" aria-labelledby="discount"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">
                        {{ $lang->data['addons'] ?? 'Addons' }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    @foreach ($addons as $row)
                        <div class="col-12 mb-20 tw-flex tw-justify-between tw-items-center">
                            <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input radius-4 border border-neutral-500" type="checkbox"
                                        name="addon" id="addon{{ $row->id }}"
                                        wire:model.live="selected_addons.{{ $row->id }}">
                                </div>
                                <label for="addon{{ $row->id }}"
                                    class="form-label fw-medium  text-primary-light mb-0">{{ $row->addon_name }}</label>
                            </div>
                            <div class="text-primary">{{ getFormattedCurrency($row->addon_price) }}</div>
                        </div>
                    @endforeach
                    @if (count($addons) == 0)
                        <div class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                            <div class="">No addons were found!.</div>
                        </div>
                    @endif
                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            {{ $lang->data['close'] ?? 'Close' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!$order)
        <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="payment"
            aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content modal-content-lg radius-16 bg-base">
                    <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                        <h1 class="modal-title text-md" id="exampleModalLabel">
                            {{ $lang->data['payments'] ?? 'Payments' }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-24">
                        <div class="">
                            <ul>
                                <li class="d-flex align-items-center gap-1 tw-justify-between text-sm">
                                    <span class="text-md fw-semibold text-primary-light">
                                        {{ $lang->data['balance'] ?? 'Balance' }} :</span>
                                    <span class="text-secondary-light fw-medium">
                                        {{ getFormattedCurrency($this?->currentBalance) }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 tw-mb-6 tw-mt-4">
                            <hr>
                        </div>
                        <div class="col-12 tw-my-6">
                            @if (!empty($payments))
                                <table class="table basic-border-table mb-0 tw-w-full tw-text-xs">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Payment Type </th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $key => $item)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td class="text-primary">
                                                    {{ getFormattedCurrency($item['amount']) }}
                                                </td>
                                                <td> {{ getpaymentMode($item['payment_type']) }}</td>
                                                <td>
                                                    <button wire:click="removePayment({{ $key }})"
                                                        type="button"
                                                        class="remove-item-button bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium tw-size-6 d-flex justify-content-center align-items-center rounded-circle">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="tw-py-16">
                                    <div class="text-center tw-text-xs">
                                        {{ $lang->data['no_payment'] ?? 'No payments were added, Add a payment to show it here.' }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 tw-my-6">
                            <hr>
                        </div>
                        <div class="row mb-20 ">
                            <div class="col-6 ">
                                <label for="name"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" class="form-control radius-8"
                                    placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}"
                                    wire:model="payment_amount">
                                @error('payment_amount')
                                    <span class="error text-danger tw-text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6 ">
                                <label for="name"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['payment_type'] ?? 'Payment Type' }}
                                    <span class="text-danger">*</span></label>
                                <select class="form-select radius-8" wire:model="payment_type">
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
                                @error('payment_type')
                                    <span class="error text-danger tw-text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-20 ">
                            <div class="col-6 ">
                                <label for="name"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['notes'] ?? 'Notes' }}
                                </label>
                                <input type="text" class="form-control radius-8"
                                    placeholder="{{ $lang->data['notes'] ?? 'Notes' }}" wire:model="payment_note">
                                @error('payment_note')
                                    <span class="error text-danger tw-text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <button
                                    class="tw-px-2 col-6 tw-text-xs tw-justify-center tw-font-semibold tw-py-3 tw-mt-[30px]  bg-success-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-w-full tw-border-0 tw-shadow-md "
                                    wire:click="add_payment">
                                    <span>{{ $lang->data['add_payment'] ?? 'Add Payment' }}</span>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer tw-mt-12">
                        <button
                            class="tw-justify-center tw-font-semibold tw-py-2 tw-h-full bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-px-12 tw-border-0 tw-shadow-md "
                            wire:click.prevent="save" wire:loading.attr="disabled">
                            <span>{{ $lang->data['save_print'] ?? 'Save & Print' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade " id="addcustomer" tabindex="-1" role="dialog" aria-labelledby="addcustomer"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addcustomer">
                        {{ $lang->data['add_customer'] ?? 'Add Customer' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['customer_name'] ?? 'Customer Name' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_customer_name'] ?? 'Enter Customer Name' }}"
                                    wire:model="customer_name">
                                @error('customer_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['phone_number'] ?? 'Phone Number' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_phone_number'] ?? 'Enter Phone Number' }}"
                                    wire:model="customer_phone">
                                @error('customer_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['email'] ?? 'Email' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_email'] ?? 'Enter Email' }}"
                                    wire:model="email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['tax_number'] ?? 'Tax Number' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_tax_number'] ?? 'Enter Tax Number' }}"
                                    wire:model="tax_no">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ $lang->data['address'] ?? 'Address' }}</label>
                                <textarea type="text" class="form-control" placeholder="{{ $lang->data['enter_address'] ?? 'Enter Address' }}"
                                    wire:model="address"></textarea>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="employee" checked
                                        wire:model="is_active">
                                    <label class="form-check-label"
                                        for="employee">{{ $lang->data['is_active'] ?? 'Is Active' }} ?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="button" class="btn btn-primary"
                            wire:click.prevent="createCustomer()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
        <script wire:ignore>
            function posFunction() {
                return {
                    detached: false,
                    shown: false,
                    init() {
                        if (window.innerWidth < 768) {
                            this.detached = true;
                        } else {
                            this.detached = false;
                        }
                        window.addEventListener('resize', (e) => {
                            if (window.innerWidth < 768) {
                                this.detached = true;
                            } else {
                                this.detached = false;
                            }
                        })

                        this.$wire.on('reloadpage', orderId => {
                            if (this.$wire.order) {
                                window.location.href = '{{ url('admin/orders/') }}';
                            } else {
                                window.location.reload();

                            }
                        })
                        this.$wire.on('printPageOrder', orderId => {
                            console.log('PRINT ORDER', orderId);

                            window.open(
                                '{{ url('admin/orders/print') }}/' + orderId,
                                '_blank'
                            );

                            setTimeout(function() {
                                window.location.href =
                                    '{{ url('admin/orders/') }}';
                            }, 1000);
                        })

                        this.$wire.on('printPage', (url) => {

                            window.open(url, '_blank');

                            window.onfocus = function() {
                                setTimeout(() => window.location.reload(), 1000);
                            };

                        });
                    },
                }
            }

            document.addEventListener('livewire:navigated', initTooltips);
            document.addEventListener('livewire:load', initTooltips);

            function initTooltips() {
                document
                    .querySelectorAll('[data-bs-toggle="tooltip"]')
                    .forEach(el => new bootstrap.Tooltip(el));
            }
        </script>
        <livewire:components.check-financial-year-component />

    </div>
</div>
