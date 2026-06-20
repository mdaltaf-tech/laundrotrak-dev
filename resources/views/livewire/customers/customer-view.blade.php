<div class="dashboard-main-body">
    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                <div class="pb-24  mb-24 tw-px-4 tw-mx-0 tw-flex tw-flex-col">
                    <div class=" border-bottom">
                        <div class="tw-flex tw-items-center tw-gap-4 tw-py-2">
                            <div class="tw-size-14 dark:tw-bg-white tw-bg-neutral-300 tw-rounded-full"></div>
                            <div class="tw-flex tw-flex-col">
                                <h6 class="mb-0 mt-16"> {{$customer->name}}</h6>
                                <span class="text-secondary-light mb-16"> {{$customer->email}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-24 tw-gap-6 tw-flex tw-flex-col">
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-info-50 d-flex justify-content-center align-items-center">
                                <iconify-icon icon="hugeicons:invoice-01" class="text-neutral-900 tw-text-lg"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal"> {{ $lang->data['total_invoices'] ?? 'Total Invoices' }}</h6>
                                <span class="text-sm text-secondary-light fw-normal"> {{$invoice_count}}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-info-50 d-flex justify-content-center align-items-center">
                                <iconify-icon icon="mdi:dollar" class="text-neutral-900 tw-text-lg"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal">{{ $lang->data['invoice_total'] ?? 'Invoice Total' }}</h6>
                                <span class="text-sm text-secondary-light fw-normal">{{getFormattedCurrency($invoice_amount)}}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-info-50 d-flex justify-content-center align-items-center">
                                <iconify-icon icon="mdi:dollar" class="text-neutral-900 tw-text-lg"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal">{{ $lang->data['total_payments'] ?? 'Total Payments' }}</h6>
                                <span class="text-sm text-secondary-light fw-normal">{{getFormattedCurrency($payment)}}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-info-50 d-flex justify-content-center align-items-center">
                                <iconify-icon icon="mdi:dollar" class="text-neutral-900 tw-text-lg"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal">{{ $lang->data['outstanding_balance'] ?? 'Outstanding Balance' }}</h6>

                                <span class="text-sm text-secondary-light fw-normal">
                                    @php
                                    $balance_amount = $invoice_amount - $payment;
                                @endphp
                                @if ($balance_amount == 0)
                                    {{ getFormattedCurrency($balance_amount) }} {{ 'Cr' }}
                                @else
                                    @if ($balance_amount < 0)
                                        {{ getFormattedCurrency($balance_amount * -1) }} {{ 'Cr' }}
                                    @else
                                        {{ getFormattedCurrency($balance_amount) }} {{ 'Dr' }}
                                    @endif
                                @endif
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-warning-50 d-flex justify-content-center align-items-center">
                                <iconify-icon
                                    icon="hugeicons:money-receive-circle"
                                    class="text-neutral-900 tw-text-lg">
                                </iconify-icon>
                            </div>

                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal">
                                    Credit Orders
                                </h6>

                                <div class="text-sm text-secondary-light fw-normal">
                                    <div>{{ $creditOrderCount }} Orders</div>
                                    <div class="text-danger">
                                        Outstanding :
                                        {{ getFormattedCurrency($creditOutstanding) }}
                                    </div>
                                    @if($creditOrderCount > 0)
                                        <div>
                                            Oldest Due :
                                            {{ $oldestCreditDays }} Days
                                        </div>
                                    @endif
                                    @if($creditOutstanding > 0)
                                        <div class="mt-12">
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm"
                                                wire:click="openSettlementModal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#creditSettlementModal"
                                            >
                                                Settle Credit
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-success-50 d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:phone-outline" class="text-neutral-900 tw-text-lg"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal">{{ $lang->data['phone_number'] ?? 'Phone Number' }}</h6>
                                <span class="text-sm text-secondary-light fw-normal">{{getCountryCode()}} {{$customer->phone ? $customer->phone : '-'}}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-success-50 d-flex justify-content-center align-items-center">
                                <iconify-icon icon="oui:email" class="text-neutral-900 tw-text-lg"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal"> {{ $lang->data['email'] ?? 'Email' }}</h6>
                                <span class="text-sm text-secondary-light fw-normal">{{$customer->email ? $customer->email : '-'}}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-success-50 d-flex justify-content-center align-items-center">
                                <iconify-icon icon="tabler:tax" class="text-neutral-900 tw-text-lg"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal">{{ $lang->data['tax_number'] ?? 'Tax Number' }}</h6>
                                <span class="text-sm text-secondary-light fw-normal">{{$customer->tax_number ? $customer->tax_number : '-'}}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-12">
                            <div class="w-40-px h-40-px radius-8 flex-shrink-0 bg-success-50 d-flex justify-content-center align-items-center">
                                <iconify-icon icon="entypo:address" class="text-neutral-900 tw-text-lg"></iconify-icon>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-normal"> {{ $lang->data['address'] ?? 'Address' }}</h6>
                                <span class="text-sm text-secondary-light fw-normal"> {{$customer->address ? $customer->address : '-'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body p-24">
                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                          {{ $lang->data['invoices'] ?? 'Invoices' }}
                          </button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab" aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                          {{ $lang->data['payments'] ?? 'Payments' }}
                          </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <livewire:customers.partials.customer-invoice :customer="$customer"/>
                        <livewire:customers.partials.customer-payments :customer="$customer"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="creditSettlementModal"
        tabindex="-1"
        aria-hidden="true"
        wire:ignore.self
    >
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">

                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md">
                        Credit Settlement
                    </h1>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body p-24">
                    <div class="col-12">
                        <ul>
                            <li class="d-flex align-items-center gap-1 mb-12 tw-justify-between">
                                <span class="text-md fw-semibold text-primary-light">
                                    Credit Orders :
                                </span>

                                <span class="text-secondary-light fw-medium">
                                    {{ $creditOrderCount }}
                                </span>
                            </li>

                            <li class="d-flex align-items-center gap-1 tw-justify-between">
                                <span class="text-md fw-semibold text-primary-light">
                                    Outstanding :
                                </span>

                                <span class="text-danger fw-semibold">
                                    {{ getFormattedCurrency($creditOutstanding) }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 tw-my-6">
                        <hr>
                    </div>
                    <div class="col-12 mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Amount Received
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control radius-8"
                            wire:model="settlement_amount"
                            placeholder="Enter Amount">

                        @error('settlement_amount')
                            <span class="error text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-12 mb-20 ">
                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['payment_type'] ?? 'Payment Type' }} <span class="text-danger">*</span></label>
                        <select  class="form-select radius-8" wire:model="settlement_payment_type">
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
                        @error('settlement_payment_type')
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
                            wire:model="settlement_payment_date">

                        @error('settlement_payment_date')
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
                        <textarea class="form-control radius-8" placeholder="{{ $lang->data['enter_notes'] ?? 'Enter Notes' }}"  wire:model="settlement_notes"></textarea>
                        @error('settlement_notes')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button
                            data-bs-dismiss="modal"
                            type="button"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            Cancel
                        </button>
                        <button
                            type="button"
                            wire:click.prevent="settleCustomerCredit"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            Save Settlement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
