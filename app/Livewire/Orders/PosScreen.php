<?php

namespace App\Livewire\Orders;

use App\Livewire\Installer\InstallController;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Addon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderArticle;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use App\Models\OrderAddonDetail;
use App\Models\Translation;
use App\Models\AdditionalChargeType;
use App\Models\OrderAdditionalCharge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class PosScreen extends Component
{
    public $services, $search_query, $order_id, $inputs = [], $selservices = [], $customer, $date, $delivery_date, $discount, $note, $paid_amount, $payment_type = 1;
    public $service_types, $service, $inputi, $prices = [], $selling_price = [], $quantity = [], $selected_type = [], $addons, $selected_addons = [], $colors = [];
    public $customer_name, $customer_phone, $email, $tax_no, $address, $selected_customer, $customers, $customer_query, $is_active = 1;
    public $total, $sub_total, $addon_total, $tax_percent, $tax, $balance, $flag = 0, $lang,$taxamount;
    public $taxable,$order;
    public $payments = [],$payment_amount, $payment_note;
    public $selectedCategory;
    public $serviceLookup = [];
    public $serviceTypeLookup = [];
    public $serviceTypeIdLookup = [];
    public $serviceDetailLookup = [];
    public $addonLookup = [];
    public $editingAdditionalCharge = null;
    public $editingAdditionalChargePosition = null;
    public $additionalChargeTypeLookup = [];

    /*
    |--------------------------------------------------------------------------
    | Order Additional Charges
    |--------------------------------------------------------------------------
    */

    public $orderAdditionalChargeTypeId = '';

    public $orderAdditionalChargeAmount = '';

    public $orderAdditionalChargeRemarks = '';

    public $orderAdditionalCharges = [];

    /*
    |--------------------------------------------------------------------------
    | Masters
    |--------------------------------------------------------------------------
    */

    public $additionalChargeTypes = [];

    #[Layout('components.layouts.pos'), Title('POS')]
    public function render()
    {
        return view('livewire.orders.pos-screen');
    }

    public function mount($id = null)
    {
        if (!\Illuminate\Support\Facades\Gate::allows('order_create')) {
            abort(404);
        }
        // $posManager = new InstallController();
        // $validation = $posManager->verify_license();
        // if(!isset($validation['status']) || $validation['status'] != true)
        // {
        //     return redirect()->route('license');
        // }
        $this->serviceLookup = Service::pluck('service_name', 'id')->toArray();

        $this->serviceTypeLookup = ServiceType::pluck(
            'service_type_name',
            'id'
        )->toArray();

        $this->serviceTypeIdLookup = ServiceType::pluck(
            'id',
            'service_type_name'
        )->toArray();

        $this->serviceDetailLookup =
            ServiceDetail::all()
            ->keyBy(function ($item) {
                return $item->service_id . '_' . $item->service_type_id;
            });

        $this->addonLookup =
            Addon::where('is_active',1)
            ->get()
            ->keyBy('id');

        $chargeTypes = AdditionalChargeType::active()
            ->ordered()
            ->get();

        $this->additionalChargeTypes = $chargeTypes;

        $this->additionalChargeTypeLookup =
            $chargeTypes
                ->mapWithKeys(fn ($item) => [
                    $item->id => [
                        'id'             => $item->id,
                        'charge_name'    => $item->charge_name,
                        'default_amount' => (float) $item->default_amount,
                    ],
                ])
                ->toArray();

        if ($this->categories->count()) {

            $this->selectedCategory = $this->categories->first()->id;

            $this->services = Service::where('is_active', 1)
                ->where('category_id', $this->selectedCategory)
                ->latest()
                ->get();
        }
        else {
            $this->services = collect();
        }
        $this->date = Carbon::today()->toDateString();
        $this->addons = Addon::where('is_active', 1)->latest()->get();
        $this->delivery_date = Carbon::today()
            ->addDays(4)
            ->toDateString();
        $this->tax_percent = getTaxPercentage();
        $this->generateOrderID();


        if ($id) {
            $this->order = Order::whereId($id)->firstOrFail();
            // Payments are managed from View Order / Orders List.
            // Do not load payments into Edit Order.
            $this->payments = [];
            if ($this->order->customer_id && $this->order->customer_id != NULL) {
                $this->selectCustomer($this->order->customer_id);
            }
            foreach ($this->order->details as $row) {
                $this->editItem($row);
            }
            $this->delivery_date = Carbon::parse($this->order->delivery_date)->toDateString();
            $this->date = Carbon::parse($this->order->order_date)->toDateString();
            $this->order_id = $this->order->order_number;
            $this->note = $this->order->note;
            $this->discount = $this->order->discount;
            $this->loadOrderAdditionalCharges($this->order);

            foreach ($this->order->addons as $row) {
                $this->selected_addons[$row->addon_id] = true;
            }
        }
        if (session()->has('selected_language')) {
            /* if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
        $this->service_types = collect();
        $this->calculateTotal();
    }

    public function editItem($row)
    {
        $this->add($this->inputi);
        $serviceTypeId = $row->service_type_id;
        $key =
            $row->service_id . '_' . $serviceTypeId;

        $servicedetail =
            $this->serviceDetailLookup[$key]
            ?? null;

        if ($servicedetail) {
            $this->selservices[$this->inputi]['service'] = $row->service_id;
            $this->selservices[$this->inputi]['service_type'] = $serviceTypeId;

            if ($this->order->tax_type == 2) {
                $this->selling_price[$this->inputi] =  $servicedetail->service_price;
                $itemtotallocal =   $servicedetail->service_price  * (100 / (100 + $this->tax_percent ?? 0));
                $this->prices[$this->inputi] = number_format($itemtotallocal, 2);
            } else {
                $this->prices[$this->inputi] =  $servicedetail->service_price;
                $this->selling_price[$this->inputi] =  $servicedetail->service_price;
            }

            $this->colors[$this->inputi] = $row->color_code;
            $this->prices[$this->inputi] = $row->service_price;
            $this->quantity[$this->inputi] = $row->service_quantity;
        }
        $this->calculateTotal();
    }

    public function changeColor($id)
    {
        $this->colors[$id] = $this->colors[$id];
    }

    /* process while update element */
    public function updated($name, $value)
    {

        /* if updated value is empty set the value as null */
        if ($value == '') data_set($this, $name, null);
        /* if updated elemtnt is search_query */
        if ($name == 'search_query') {
            $query = Service::where('is_active', 1);
            if ($this->selectedCategory) {
                $query->where('category_id', $this->selectedCategory);
            }
            if (!empty($value)) {
                $query->where('service_name', 'like', '%' . $value . '%');
            }
            $this->services = $query->latest()->get();
        }
        /* if the updated value is customer_query */
        if ($name == 'customer_query' && $value != '') {
            $this->customers = Customer::where(function ($query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%')->orWhere('phone', 'like', '%' . $value . '%');
            })->latest()->limit(5)->get();
        } elseif ($name == 'customer_query' && $value == '') {
            $this->customers = collect();
        }

        if (
            $name == 'discount' ||
            strpos($name, 'selling_price') !== false ||
            strpos($name, 'prices') !== false ||
            strpos($name, 'quantity') !== false ||
            strpos($name, 'selected_addons') !== false
        ) {
            $this->calculateTotal();
        }
    }

    /* select service */
    public function selectService($id)
    {
        $this->selected_type = [];
        $this->service = Service::where('id', $id)->first();
        $this->service_types = collect();
        /* if service is not empty */
        if ($this->service) {
            $servicedetails = ServiceDetail::where('service_id', $id)->get();
            $serviceTypes = $this->serviceTypeLookup;
            foreach ($servicedetails as $row) {
                $this->service_types->push([
                    'id' => $row->service_type_id,
                    'service_type_name' =>
                        $serviceTypes[$row->service_type_id] ?? '',
                    'price' =>
                        getFormattedCurrency($row->service_price)
                ]);
            }
        }
        if ($this->service_types) {
            if (count($this->service_types) > 0) {
                $first = $this->service_types->first();
                if ($first) {
                    $this->selected_type[$first['id']] = true;
                }
            }
        }
        $this->calculateTotal();
    }

    /* select services*/
    public function addItem()
    {

        if ($this->service) {
            $anyTicked = false;
            foreach ($this->selected_type as $item) {
                if ($item == true) {
                    $anyTicked = true;
                }
            }
            if (count($this->selected_type) > 0 && $anyTicked) {
                $tax_type = getTaxType();
                foreach ($this->selected_type as $item => $value) {
                    if ($value === true) {
                        $this->add($this->inputi);
                        $this->selservices[$this->inputi]['service'] = $this->service->id;
                        $this->selservices[$this->inputi]['service_type']  = $item;
                        $key = $this->service->id . '_' . $item;

                        $servicedetail =
                            $this->serviceDetailLookup[$key]
                            ?? null;
                        /* if service details is not empty */
                        if ($servicedetail) {
                            if ($tax_type == 2) {
                                $this->selling_price[$this->inputi] =  $servicedetail->service_price;
                                $itemtotallocal =   $servicedetail->service_price  * (100 / (100 + $this->tax_percent ?? 0));
                                $this->prices[$this->inputi] = number_format($itemtotallocal, 2);
                            } else {
                                $this->prices[$this->inputi] =  $servicedetail->service_price;
                                $this->selling_price[$this->inputi] =  $servicedetail->service_price;
                            }
                        }
                    }
                }
                $this->service_types = collect();
                $this->dispatch('closemodal');
                $this->calculateTotal();
            } else {
                $this->addError('service_error', 'Select a service type');
                return 0;
            }
        }
    }
    /* add the item to array */
    public function add($i)
    {
        $this->inputi = $i + 1;
        $this->inputs[$this->inputi] = 1;
        $this->prices[$this->inputi] = 100;
        $this->service_types[$this->inputi] = '';
        $this->quantity[$this->inputi]  = 1;
        $this->colors[$this->inputi]  = '';
    }
    /* increase the count */
    public function increase($key)
    {
        /* if quantity of key is exist */
        if (isset($this->quantity[$key])) {
            $this->quantity[$key]++;
            $this->calculateTotal();
        }
    }

    public function priceChange($key)
    {
        $this->calculateTotal();
    }
    /* decrease the count */
    public function decrease($key)
    {
        /* is quantity of key is exist */
        if (isset($this->quantity[$key])) {
            if ($this->quantity[$key] > 1) {
                /* if quantity of key is >1 */
                $this->quantity[$key]--;
            } else {
                /* unset the details if quantity of key is 1 */
                unset($this->quantity[$key]);
                unset($this->prices[$key]);
                unset($this->service_types[$key]);
                unset($this->selservices[$key]);
                unset($this->selling_price[$key]);
            }
            $this->calculateTotal();
        }
    }
    public function removeItem($key)
    {
        unset($this->quantity[$key]);
        unset($this->prices[$key]);
        unset($this->service_types[$key]);
        unset($this->selservices[$key]);
        unset($this->selling_price[$key]);
        $this->calculateTotal();
    }
    /* create customer */
    public function createCustomer()
    {   /* validation */
        $this->validate([
            'customer_name'  => 'required',
            'customer_phone'    => 'required',
            'email' => 'unique:customers|nullable'

        ]);
        $customer = Customer::create([
            'name'  => $this->customer_name,
            'phone' => $this->customer_phone,
            'email' => $this->email,
            'tax_number'    => $this->tax_no,
            'address'   => $this->address,
            'is_active' => $this->is_active ?? 0,
        ]);
        $this->selected_customer = $customer;
        $this->dispatch('closemodal');
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->email    = '';
        $this->tax_no = '';
        $this->address = '';
        $this->is_active = 1;
    }
    /* select customer */
    public function selectCustomer($id)
    {
        $this->selected_customer = Customer::where('id', $id)->first();
        $this->customer_query = '';
        $this->customers = collect();
    }
    /* generate order Id */
    public function generateOrderID()
    {
        $code_prefix = 'ORD-';
        $ordernumber = Order::Orderby('id', 'desc')->first();
        /*if order number is exist*/
        if ($ordernumber && $ordernumber->order_number != "") {
            /* if invoice code not empty */
            $code = explode("-", $ordernumber->order_number);
            $new_code = $code[1] + 1;
            $new_code = str_pad($new_code, 4, "0", STR_PAD_LEFT);
            $this->order_id = $code_prefix . $new_code;
        } else {
            /* if order code is empty set start */
            $this->order_id = $code_prefix . '0001';
        }
    }
    /* calculate service total */
    public function calculateTotal()
    {
        $this->sub_total = 0;
        $this->addon_total = 0;

        $this->total = 0;
        $this->sub_total = 0;
        $this->taxamount = 0;
        $this->taxable = 0;

        $itemtotal = 0;
        $itemtaxtotal2 = 0;
        $sub_total = 0;

        $tax_type = getTaxType();
        foreach ($this->selling_price as $key => $value) {
            $this->sub_total += $value * $this->quantity[$key];
            $itemtaxtotal = 0;
            if ($tax_type == 2) {
                $itemtotallocal =  ($this->selling_price[$key] * $this->quantity[$key])  * (100 / (100 + $this->tax_percent ?? 0));
                $itemtaxtotal +=  ($this->selling_price[$key] * $this->quantity[$key]) - $itemtotallocal ?? 0;

                $itemtotal += ($this->selling_price[$key] * $this->quantity[$key]);
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotallocal;
                $sub_total += $itemtotallocal;
            } else {
                $itemtotallocal =  ($this->selling_price[$key] * $this->quantity[$key]);
                $itemtaxtotal += $itemtotallocal * $this->tax_percent / 100;
                $itemtotal += $itemtotallocal + $itemtaxtotal;
                $itemtaxtotal2 += $itemtaxtotal;
                $this->taxable += $itemtotallocal;
                $sub_total += $itemtotallocal;
            }
        }

        /* if any addons selected */
        if ($this->selected_addons) {
            foreach ($this->selected_addons as $key => $value) {
                if ($value === true) {
                    $itemtaxtotal = 0;

                    $addon =
                        $this->addonLookup[$key]
                        ?? null;
                    if (!$addon) {
                        continue;
                    }

                    if ($tax_type == 2) {
                        $itemtotallocal =  ($addon->addon_price)  * (100 / (100 + $this->tax_percent ?? 0));
                        $itemtaxtotal +=  ($addon->addon_price) - $itemtotallocal ?? 0;
                        $itemtotal +=  ($addon->addon_price);
                        $itemtaxtotal2 += $itemtaxtotal;
                        $this->taxable += $itemtotallocal;
                        $sub_total += $itemtotallocal;
                        $this->addon_total += $itemtotallocal;
                    } else {
                        $itemtotallocal =   ($addon->addon_price);
                        $itemtaxtotal += $itemtotallocal * $this->tax_percent / 100;
                        $itemtotal += $itemtotallocal + $itemtaxtotal;
                        $itemtaxtotal2 += $itemtaxtotal;
                        $this->taxable += $itemtotallocal;
                        $this->addon_total += $itemtotallocal;
                        $sub_total += $itemtotallocal;
                    }
                }
            }
        }
        $this->sub_total = $sub_total;
        $this->tax = $itemtaxtotal2;
        $this->total =
            ($this->sub_total + $itemtaxtotal2)
            + $this->orderAdditionalChargesTotal
            - ($this->discount ?? 0);

        $this->total = round($this->total, 2);
        $this->balance = $this->total - ($this->paid_amount ?? 0);
    }

    //add payment
    public function add_payment()
    {
        $this->validate([
            'payment_type'  => 'required',
            'payment_amount' => 'lte:' . $this->getPaymentBalance()
        ]);

        $payment = [
            'amount' => (float)$this->payment_amount,
            'payment_note' => $this->payment_note,
            'payment_type' => $this->payment_type,
            'payment_id' => null
        ];
        $this->payment_amount = '';
        $this->payment_note = '';
        $this->payment_type = 1;
        array_push($this->payments, $payment);
        $this->dispatch(
            'alert',
            ['type' => 'success',  'message' => ' Payment has been created']
        );
    }

    /* save the order */
    public function save($type = null)
    {
        if ($this->flag == 1) {
            return;
        }
        $this->flag = 1;
        DB::beginTransaction();

        try {
            $amount = 0;
            if ($type === 'cash') {
                $this->payments = [];
                array_push($this->payments, [
                    'amount' => $this->total,
                    'payment_note' => $this->payment_note,
                    'payment_type' => $this->payment_type,
                    'payment_id' => null
                ]);
            }
            $this->calculateTotal();

            $this->validate([
                'payment_type'  => 'required'
            ]);
            /* if selected services > 0  send error alert*/
            if (count($this->selservices) <= 0) {
                $this->dispatch(
                    'alert',
                    ['type' => 'error',  'message' => ' You have not added any service to the cart']
                );
                $this->addError('error', 'Select a service');

                $this->flag = 0;
                DB::rollBack();
                return 0;
            }
            $balance = $this->getPaymentBalance();
            /* if balance is <0 send error alert*/
            if ($balance < 0) {
                $this->dispatch(
                    'alert',
                    ['type' => 'error',  'message' => 'Paid Amount cannot be greater than total.']
                );
                $this->addError('paid_amount', 'Paid Amount cannot be greater than total.');

                $this->flag = 0;
                DB::rollBack();
                return 0;
            }
            /* if customer not exist and has any balance to pay send the error alert */
            if ($balance != 0 && $this->selected_customer == null) {
                $this->addError('paid_amount_customer', 'The customer must be registered to use ledger.');

                $this->flag = 0;
                DB::rollBack();
                return 0;
            }
            if (!$this->order) {
                $this->generateOrderID();
            }

            if ($this->order) {
                $paidAmount = Payment::active()
                    ->where('order_id', $this->order->id)
                    ->sum('received_amount');
            } else {
                $paidAmount = collect($this->payments)
                    ->sum('amount');
            }

            $balanceAmount = max(
                0,
                $this->total - $paidAmount
            );

            if ($paidAmount <= 0) {

                $paymentStatus = Order::PAYMENT_UNPAID;
            } elseif ($balanceAmount > 0) {

                $paymentStatus = Order::PAYMENT_PARTIAL;
            } else {

                $paymentStatus = Order::PAYMENT_PAID;
            }

            $garmentsChanged = false;
            $addonsChanged = false;

            $order = $this->order;
            if ($this->order) {
                $garmentsChanged = $this->garmentsChanged();
                $addonsChanged = $this->addonsChanged();

                if (
                    $this->hasProcessedArticles()
                    &&
                    $garmentsChanged
                ) {
                    $this->dispatch(
                        'alert',
                        [
                            'type' => 'error',
                            'message' =>
                            'Garments cannot be modified after processing has started. You may update delivery date, notes and customer information.'
                        ]
                    );

                    $this->flag = 0;
                    DB::rollBack();

                    return;
                }

                Order::whereId($this->order->id)->update([
                    'customer_id'   => $this->selected_customer->id ?? null,
                    'customer_name' => $this->selected_customer->name ?? null,
                    'phone_number'  => $this->selected_customer->phone ?? null,
                    'order_date'    => Carbon::parse($this->date)->toDateTimeString(),
                    'delivery_date' => Carbon::parse($this->delivery_date)->toDateTimeString(),
                    'sub_total' => $this->sub_total,
                    'addon_total'   => $this->addon_total,
                    'discount'  => $this->discount ?? 0,
                    'tax_percentage'    => $this->tax_percent,
                    'tax_amount'    => $this->tax,
                    'tax_type'  => getTaxType(),
                    'taxable_amount'    => $this->taxable,
                    'total' => $this->total,
                    'note'  => $this->note,
                    'paid_amount' => $paidAmount,
                    'balance_amount' => $balanceAmount,
                    'payment_status' => $paymentStatus,
                    'order_type'    => 1,
                ]);

                $this->saveOrderAdditionalCharges($order);

                if ($garmentsChanged)
                {
                    OrderDetail::whereOrderId(
                        $this->order->id
                    )->update([
                        'is_deleted' => 1
                    ]);

                    OrderArticle::where(
                        'order_id',
                        $this->order->id
                    )->delete();
                }

                if ($addonsChanged)
                {
                    OrderAddonDetail::whereOrderId(
                        $this->order->id
                    )->update([
                        'is_deleted' => 1
                    ]);
                }
            } else {
                $order = Order::create([
                    'order_number'  => $this->order_id,
                    'customer_id'   => $this->selected_customer->id ?? null,
                    'customer_name' => $this->selected_customer->name ?? null,
                    'phone_number'  => $this->selected_customer->phone ?? null,
                    'order_date'    => Carbon::parse($this->date)->toDateTimeString(),
                    'delivery_date' => Carbon::parse($this->delivery_date)->toDateTimeString(),
                    'sub_total' => $this->sub_total,
                    'addon_total'   => $this->addon_total,
                    'discount'  => $this->discount ?? 0,
                    'tax_percentage'    => $this->tax_percent,
                    'tax_amount'    => $this->tax,
                    'tax_type'  => getTaxType(),
                    'taxable_amount'    => $this->taxable,
                    'total' => $this->total,
                    'note'  => $this->note,
                    'status'    => 0,
                    'order_type'    => 1,
                    'paid_amount' => $paidAmount,
                    'balance_amount' => $balanceAmount,
                    'payment_status' => $paymentStatus,
                    'created_by'    => Auth::user()->id,
                    'financial_year_id' => getFinancialYearId()
                ]);

                $this->saveOrderAdditionalCharges($order);
            }

        if (
            !$this->order
            || $garmentsChanged
        )
        {
            foreach ($this->selservices as $key => $value) {
                $serviceId = $value['service'];
                $serviceTypeId = $value['service_type'];
                $serviceTypeName =
                    $this->serviceTypeLookup[$serviceTypeId]
                    ?? '';
                $amount += $this->prices[$key];


                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'service_id' => $serviceId,
                    'service_type_id' => $serviceTypeId,
                    'service_name' => $serviceTypeName,
                    'service_quantity' => $this->quantity[$key],
                    'service_detail_total' => $this->selling_price[$key] * $this->quantity[$key],
                    'service_price' => $this->selling_price[$key],
                    'color_code' => $this->colors[$key],
                ]);

                for ($i = 1; $i <= $this->quantity[$key]; $i++) {
                    OrderArticle::create([
                        'order_id' => $order->id,
                        'order_detail_id' => $orderDetail->id,
                        'tag_number' =>
                        $this->generateTag(
                            $order->order_number
                        ),
                        'article_name' => $this->serviceLookup[$serviceId] ?? '',
                        'service_name' => $serviceTypeName,
                        'color_code' => $this->colors[$key],
                        'created_by' => Auth::id()
                    ]);
                }
            }
        }
        if (
            !$this->order
            || $addonsChanged
        )
        {
            if ($this->selected_addons) {
                foreach ($this->selected_addons as $key => $value) {
                    if ($value === true) {

                        $addon =
                            $this->addonLookup[$key]
                            ?? null;

                        if (!$addon) {
                            continue;
                        }

                        \App\Models\OrderAddonDetail::create([
                            'order_id'  => $order->id,
                            'addon_id'    => $addon->id,
                            'addon_name'    => $addon->addon_name,
                            'addon_price'   => $addon->addon_price,
                        ]);
                    }
                }
            }
        }
            if (!$this->order && !empty($this->payments)) {
                foreach ($this->payments as $payment) {
                    $payment = \App\Models\Payment::create([
                        'payment_date'  => $this->date,
                        'customer_id'   => $this->selected_customer->id ?? null,
                        'customer_name' => $this->selected_customer->name ?? null,
                        'order_id'  => $order->id,
                        'payment_type'  => $payment['payment_type'],
                        'received_amount'    => $payment['amount'],
                        'payment_note' => $payment['payment_note'] ?? null,
                        'financial_year_id' => getFinancialYearId(),
                        'created_by'    => Auth::user()->id,
                    ]);

                    $order->refreshPaymentStatus();
                }
            }
            if ($this->selected_customer) {
                $message = sendOrderCreateSMS($order->id, $this->selected_customer->id);
                if ($message) {
                    $this->dispatch(
                        'alert',
                        ['type' => 'error',  'message' => $message, 'title' => 'SMS Error']
                    );
                }
            }

            DB::commit();
            $this->flag = 0;

            $message = $this->order
                ? 'Order Updated Successfully!'
                : $order->order_number . ' Was Successfully Created!';

            $this->dispatch(
                'alert',
                ['type' => 'success',  'message' => $message]
            );

            if (\Illuminate\Support\Facades\Gate::allows('order_print')) {
                $printOrderId = $this->order?->id ?? $order->id;

                $this->dispatch(
                    'printPage',
                    route('order.print', [
                        'id' => $printOrderId,
                        'printer_type' => 1,
                    ])
                );

                if (!$this->order) {
                    $this->clearAll();
                }
            }

            if (!$this->order) {
                $this->clearAll();
            }
        } catch (\Exception $e) {
            $this->flag = 0;
            DB::rollBack();
            throw $e;
        }
    }

    public function getPaymentBalance()
    {
        $orderBalance = $this->total;
        $paymentsTotal = 0;
        foreach ($this->payments as $payment) {
            $paymentsTotal += $payment['amount'];
        }
        return $orderBalance - $paymentsTotal;
    }

    public function magicFill()
    {
        if ($this->total) {
            $this->paid_amount = $this->total;
        } else {
            $this->paid_amount = 0;
        }
    }
    //Reload page on clicking clearall
    public function clearAll()
    {
        $this->dispatch('reloadpage');
    }

    //remove payment
    public function removePayment($paymentIndex)
    {
        array_splice($this->payments, $paymentIndex, 1);
    }

    public function generateTag($orderNumber)
    {
        $orderNo = preg_replace(
            '/[^0-9]/',
            '',
            $orderNumber
        );

        $lastTag =
            OrderArticle::where(
                'tag_number',
                'like',
                'FBL' . $orderNo . '-%'
            )
            ->latest('id')
            ->first();

        $next = 1;

        if ($lastTag) {
            preg_match(
                '/(\d+)$/',
                $lastTag->tag_number,
                $match
            );

            $next = ((int)$match[1]) + 1;
        }

        return 'FBL'
            . $orderNo
            . '-'
            . str_pad(
                $next,
                3,
                '0',
                STR_PAD_LEFT
            );
    }

    public function changeCategory($categoryId = null)
    {
        $this->selectedCategory = $categoryId;

        $query = Service::where('is_active', 1);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($this->search_query)) {
            $query->where('service_name', 'like', '%' . $this->search_query . '%');
        }

        $this->services = $query->latest()->get();
    }

    private function hasProcessedArticles(): bool
    {
        if (!$this->order) {
            return false;
        }

        return OrderArticle::where(
            'order_id',
            $this->order->id
        )
        ->where(
            'status',
            '>',
            OrderArticle::STATUS_RECEIVED
        )
        ->exists();
    }

    private function garmentsChanged(): bool
    {
        if (!$this->order) {
            return false;
        }

        $existing = [];

        foreach ($this->order->details as $detail) {

            $existing[] = [
                'service_id'      => $detail->service_id,
                'service_type_id' => $detail->service_type_id,
                'qty'             => (int) $detail->service_quantity,
                'color'           => $detail->color_code,
            ];
        }

        $current = [];

        foreach ($this->selservices as $key => $value) {

            $serviceType = ServiceType::find(
                $value['service_type']
            );

            $current[] = [
                'service_id'      => $value['service'],
                'service_type_id' => $value['service_type'],
                'qty'             => (int) $this->quantity[$key],
                'color'           => $this->colors[$key] ?? '',
            ];
        }

        sort($existing);
        sort($current);

        return $existing != $current;
    }



    #[Computed]
    public function totalItems()
    {
        return array_sum(
            array_map(
                fn($qty) => (int)$qty,
                $this->quantity ?? []
            )
        );
    }

    #[Computed]
    public function orderAdditionalChargesTotal()
    {
        return collect($this->orderAdditionalCharges)
            ->sum('amount');
    }

    public function addOrderAdditionalCharge()
    {
        $this->validate([
            'orderAdditionalChargeTypeId'  => 'required|integer',
            'orderAdditionalChargeAmount'  => 'required|numeric|gt:0',
            'orderAdditionalChargeRemarks' => 'nullable|string|max:255',
        ]);

        $chargeType = $this->getAdditionalChargeType(
            $this->orderAdditionalChargeTypeId
        );

        if (!$chargeType) {
            return;
        }

        $duplicate = collect($this->orderAdditionalCharges)
            ->contains(function ($charge) {
                return (int) $charge['charge_type_id']
                    === (int) $this->orderAdditionalChargeTypeId;
            });

        if ($duplicate) {

            $this->addError(
                'orderAdditionalChargeTypeId',
                'Charge already added.'
            );

            // Restore row if we were editing
            if ($this->editingAdditionalCharge !== null) {

                array_splice(
                    $this->orderAdditionalCharges,
                    $this->editingAdditionalChargePosition,
                    0,
                    [$this->editingAdditionalCharge]
                );

                $this->editingAdditionalCharge = null;
                $this->editingAdditionalChargePosition = null;
            }

            return;
        }

        $this->orderAdditionalCharges[] = [
            'charge_type_id' => (int) $chargeType['id'],
            'charge_name'    => $chargeType['charge_name'],
            'amount'         => (float) $this->orderAdditionalChargeAmount,
            'remarks'        => trim($this->orderAdditionalChargeRemarks ?? ''),
        ];

        $this->resetOrderAdditionalChargeForm();

        $this->calculateTotal();
    }

    private function resetOrderAdditionalChargeForm(): void
    {
        $this->editingAdditionalCharge = null;

        $this->editingAdditionalChargePosition = null;

        $this->orderAdditionalChargeTypeId = '';

        $this->orderAdditionalChargeAmount = '';

        $this->orderAdditionalChargeRemarks = '';

        $this->resetValidation([
            'orderAdditionalChargeTypeId',
            'orderAdditionalChargeAmount',
            'orderAdditionalChargeRemarks',
        ]);
    }

    private function getAdditionalChargeType($id): ?array
    {
        if (blank($id)) {
            return null;
        }

        return $this->additionalChargeTypeLookup[(int) $id] ?? null;
    }

    public function removeOrderAdditionalCharge($index)
    {
        if (!isset($this->orderAdditionalCharges[$index])) {
            return;
        }

        unset($this->orderAdditionalCharges[$index]);

        $this->orderAdditionalCharges = array_values(
            $this->orderAdditionalCharges
        );

        $this->calculateTotal();
    }

    public function getSelectableAdditionalChargeTypesProperty()
    {
        $selectedChargeTypeIds = collect($this->orderAdditionalCharges)
            ->pluck('charge_type_id');

        $editingChargeTypeId = $this->editingAdditionalCharge['charge_type_id'] ?? null;

        return collect($this->additionalChargeTypes)
            ->reject(function ($charge) use ($selectedChargeTypeIds, $editingChargeTypeId) {

                $chargeId = (int) data_get($charge, 'id');

                // Keep the currently edited charge in the dropdown
                if (
                    $editingChargeTypeId !== null &&
                    $chargeId === (int) $editingChargeTypeId
                ) {
                    return false;
                }

                // Remove all other already selected charges
                return $selectedChargeTypeIds->contains($chargeId);
            })
            ->values();
    }

    public function editOrderAdditionalCharge($index)
    {
        if (!isset($this->orderAdditionalCharges[$index])) {
            return;
        }

        $charge = $this->orderAdditionalCharges[$index];

        // Remove the row FIRST
        unset($this->orderAdditionalCharges[$index]);
        $this->orderAdditionalCharges = array_values($this->orderAdditionalCharges);

        // Then enter edit mode
        $this->editingAdditionalCharge = $charge;
        $this->editingAdditionalChargePosition = $index;

        // Then populate the form
        $this->orderAdditionalChargeTypeId = (int) $charge['charge_type_id'];
        $this->orderAdditionalChargeAmount = $charge['amount'];
        $this->orderAdditionalChargeRemarks = $charge['remarks'];

        $this->resetValidation([
            'orderAdditionalChargeTypeId',
            'orderAdditionalChargeAmount',
            'orderAdditionalChargeRemarks',
        ]);

        $this->calculateTotal();
    }

    #[Computed]
    public function categories()
    {
        return ServiceCategory::withCount([
            'services' => function ($query) {
                $query->where('is_active', 1);
            }
        ])
        ->where('is_active', 1)
        ->orderBy('sort_order')
        ->get();
    }

    #[Computed()]
    public function currentBalance()
    {
        return $this->getPaymentBalance();
    }

    public function getAdditionalChargeCountProperty(): int
    {
        return count($this->orderAdditionalCharges);
    }

    private function addonsChanged(): bool
    {
        if (!$this->order) {
            return false;
        }

        $existing = $this->order->addons
            ->pluck('addon_id')
            ->sort()
            ->values()
            ->toArray();

        $current = [];

        foreach ($this->selected_addons as $id => $selected) {
            if ($selected === true) {
                $current[] = (int)$id;
            }
        }

        sort($current);

        return $existing != $current;
    }

    private function loadOrderAdditionalCharges(Order $order): void
    {
        $this->orderAdditionalCharges = [];

        $order->loadMissing([
            'additionalCharges' => function ($query) {
                $query->where('is_deleted', false)
                    ->with('chargeType')
                    ->orderBy('charge_type_id');
            },
        ]);

        foreach ($order->additionalCharges as $charge) {
            $this->orderAdditionalCharges[] = [
                'charge_type_id' => (int)$charge->charge_type_id,
                'charge_name'    => optional($charge->chargeType)->charge_name,
                'amount'         => (float)$charge->amount,
                'remarks'        => $charge->remarks,
            ];
        }
    }

    private function saveOrderAdditionalCharges(Order $order): void
    {
        $relation = $order->additionalCharges();

        $relation
            ->where('is_deleted',0)
            ->update([
                'is_deleted' => true,
                'updated_by' => $this->currentUserId(),
            ]);

        $existingCharges = $relation
            ->whereIn('is_deleted', [0,1])
            ->get()
            ->keyBy('charge_type_id');

        foreach ($this->orderAdditionalCharges as $charge) {

            $existing = $existingCharges->get(
                $charge['charge_type_id']
            );

            if (!$existing) {

                $relation->create([
                    'charge_type_id' => $charge['charge_type_id'],
                    'amount'         => $charge['amount'],
                    'remarks'        => $charge['remarks'],
                    'created_by'     => $this->currentUserId(),
                    'updated_by'     => $this->currentUserId(),
                ]);

                continue;
            }

            $existing->update([
                'amount' => $charge['amount'],
                'remarks' => $charge['remarks'],
                'is_deleted' => false,
                'updated_by' => $this->currentUserId(),
            ]);
        }
    }

    private function currentUserId(): ?int
    {
        return Auth::id();
    }

    public function getAdditionalChargeSummaryProperty(): string
    {
        if (empty($this->orderAdditionalCharges)) {
            return 'No additional charges';
        }

        $names = collect($this->orderAdditionalCharges)
            ->pluck('charge_name')
            ->values();

        if ($names->count() === 1) {
            return $names->first();
        }

        if ($names->count() === 2) {
            return $names->implode(', ');
        }

        return sprintf(
            '%s, %s (+%d)',
            $names[0],
            $names[1],
            $names->count() - 2
        );
    }

    public function cancelEditAdditionalCharge()
    {
        if ($this->editingAdditionalCharge !== null) {

            array_splice(
                $this->orderAdditionalCharges,
                $this->editingAdditionalChargePosition,
                0,
                [$this->editingAdditionalCharge]
            );
        }

        $this->resetOrderAdditionalChargeForm();
        $this->calculateTotal();
    }

    #[Computed]
    public function additionalChargeCount()
    {
        return count($this->orderAdditionalCharges);
    }

    public function changeAdditionalChargeType($value): void
    {
        $this->orderAdditionalChargeTypeId = (int) $value;

        if (blank($value)) {
            $this->orderAdditionalChargeAmount = null;
            return;
        }

        $chargeType = $this->getAdditionalChargeType($value);

        if (!$chargeType) {
            $this->orderAdditionalChargeAmount = null;
            return;
        }

        // Preserve custom amount while editing
        if ($this->editingAdditionalCharge !== null) {
            return;
        }

        $this->orderAdditionalChargeAmount = (float) $chargeType['default_amount'];
    }
}
