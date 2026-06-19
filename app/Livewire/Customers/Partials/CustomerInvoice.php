<?php

namespace App\Livewire\Customers\Partials;

use \Carbon\Carbon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Auth;
use App\Models\Translation;

class CustomerInvoice extends Component
{
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;
    public $customer;
    public $orders;
    public $order, $amount_to_pay, $note, $balance, $payment_mode, $payment_date, $order_filter, $lang;
    public $paid_amount, $customer_name, $search_query;


    public function render()
    {
        return view('livewire.customers.partials.customer-invoice');
    }

    public function mount(Customer $customer){
        $this->customer = $customer;
        $this->orders = new EloquentCollection();
        $this->loadOrders();

        $this->payment_date = now()->format('Y-m-d');

        if (session()->has('selected_language')) { /* if session has selected laugage*/
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function filterdata(){
        $orders = Order::where('customer_id',$this->customer->id)->latest()->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $orders;
    }

    public function loadOrders()
    {
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }
        $myorder = $this->filterdata();
        $this->orders->push(...$myorder->items());
        if ($this->hasMorePages = $myorder->hasMorePages()) {
            $this->nextCursor = $myorder->nextCursor()->encode();
        }
        $this->currentCursor = $myorder->cursor();
    }
    public function reloadOrders()
    {
        $this->orders = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->orders->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    /* get paid informatiion */
    public function payment($id)
    {
        $this->order = Order::where('id', $id)->first();
        $this->customer = Customer::where('id', $this->order->customer_id)->first();
        $this->customer_name = $this->customer->name ?? null;
        $this->paid_amount = Payment::active()
            ->where(
                'order_id',
                $this->order->id
            )
            ->sum('received_amount');

        $this->balance =
            $this->order->total -
            $this->paid_amount;
    }
    /* reset input fields */
    private function resetInputFields()
    {
        $this->balance = '';
        $this->order = '';
        $this->customer = '';
        $this->note = '';
        $this->payment_mode = "";
        $this->payment_date = now()->format('Y-m-d');
    }
    /* add paymentinformation */
    public function addPayment()
    {
        if($this->order->status == 4)
        {
            return 0;
        }
        $this->validate([
            'paid_amount'   => 'required',
            'payment_mode'  => 'required',
            'payment_date' => 'required|date',
        ]);

        /* if balance is < 0 */
        if ($this->balance < 0) {
            $this->addError('balance', 'Pls Provide Valid Amount.');
            return 0;
        }

        /* if paid amount > balance */
        if($this->paid_amount > $this->balance)
        {
            $this->addError('payment_type','Amount cannot be greater than balance');
            return 0;
        }

        /* if the balance is > order total */
        if ($this->balance > $this->order->total) {
            $this->addError('balance', 'Paid Amount cannot be greater than total.');
            return 0;
        }

        $orderDate = Carbon::parse(
            $this->order->order_date
        )->startOfDay();

        $paymentDate = Carbon::parse(
            $this->payment_date
        )->startOfDay();

        if ($paymentDate->lt($orderDate)) {

            $this->addError(
                'payment_date',
                'Payment date cannot be earlier than order booking date.'
            );

            return;
        }

        /* if any balance */
        if ($this->balance) {
            \App\Models\Payment::create([
                'payment_date' => Carbon::parse($this->payment_date),
                'customer_id'   => $this->customer->id ?? null,
                'customer_name' => $this->customer->name ?? null,
                'order_id'  => $this->order->id,
                'payment_type'  => $this->payment_mode,
                'payment_note'  => $this->note,
                'financial_year_id' => getFinancialYearId(),
                'received_amount' => (float)$this->paid_amount,
                'created_by'    => Auth::user()->id,
            ]);

            $this->order->refreshPaymentStatus();
            $this->reloadOrders();
            $this->resetInputFields();
            $this->dispatch('closemodal');
            $this->dispatch(
                'alert',
                ['type' => 'success',  'message' => 'Payment recorded successfully!']
            );
        }
    }
}
