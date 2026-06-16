<?php

namespace App\Livewire\Orders;

use Livewire\Attributes\Title;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\OrderArticle;
use App\Models\OrderAddonDetail;
use Auth;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;
use Livewire\Component;

class OrdersList extends Component
{
    public $orders;
    public $paid_amount, $customer, $customer_name, $search_query;
    public $order, $amount_to_pay, $note, $balance, $payment_mode, $order_filter, $lang;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;
    public $paid_filter;
    public $overdue_filter;
    public $quick_filter;

    #[Title('Orders')]
    public function render()
    {
        return view('livewire.orders.orders-list');
    }

    /* process before render */
    public function mount()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('order_list')) {
            abort(404);
        }
        $this->order_filter = request('status');
        $this->overdue_filter = request('overdue');
        $this->quick_filter = request('quick_filter');
        $this->paid_filter = request('paid_filter');

        $this->orders = new EloquentCollection();

        $this->loadOrders();
        if (session()->has('selected_language')) {   /* if session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            /* if session has no selected language */
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function updated($name, $value)
    {
        if (
            in_array(
                $name,
                [
                    'search_query',
                    'order_filter',
                    'paid_filter',
                    'quick_filter'
                ]
            )
        ) {
            $this->reloadOrders();
        }
    }

    /* get paid informatiion */
    public function payment($id)
    {
        $this->order = Order::where('id', $id)->first();
        $this->customer = Customer::where('id', $this->order->customer_id)->first();
        $this->customer_name = $this->customer->name ?? null;
        $this->paid_amount =
            Payment::active()
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
    }
    /* add paymentinformation */
    public function addPayment()
    {
        /* if balance is < 0 */
        if ($this->balance < 0) {
            $this->addError('balance', 'Pls Provide Valid Amount.');
            return 0;
        }
        /* if the balance is > order total */
        if ($this->balance > $this->order->total) {
            $this->addError('balance', 'Paid Amount cannot be greater than total.');
            return 0;
        }
        if ($this->order->status == 4) {
            return 0;
        }
        $this->validate([
            'payment_mode' => 'required',
        ]);
        /* if any balance */
        if ($this->balance) {
            \App\Models\Payment::create([
                'payment_date'  => \Carbon\Carbon::today()->toDateString(),
                'customer_id'   => $this->customer->id ?? null,
                'customer_name' => $this->customer->name ?? null,
                'order_id'  => $this->order->id,
                'payment_type'  => $this->payment_mode,
                'payment_note'  => $this->note,
                'financial_year_id' => getFinancialYearId(),
                'received_amount' => (float)$this->balance,
                'created_by'    => Auth::user()->id,
            ]);

            $this->order->refreshPaymentStatus();
            $this->resetInputFields();
            $this->dispatch('closemodal');
            $this->dispatch(
                'alert',
                ['type' => 'success',  'message' => 'Payment recorded successfully!']
            );
        }
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
        $this->resetCursorPagination();
        $this->loadOrders();
    }

    public function filterdata()
    {
        $orders = Order::active();

        // Restrict normal users to their own orders
        if (Auth::user()->user_type != 1) {
            $orders->where(
                'created_by',
                Auth::user()->id
            );
        }

        // Search
        if (!empty($this->search_query)) {

            $search = $this->search_query;

            $orders->where(function ($q) use ($search) {

                $q->where(
                    'order_number',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'customer_name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'phone_number',
                    'like',
                    '%' . $search . '%'
                );

            });
        }

        // Order Status Filter
        if (
            $this->order_filter !== null &&
            $this->order_filter !== ''
        ) {
            $orders->where(
                'status',
                $this->order_filter
            );
        }

       if (
            $this->paid_filter !== null &&
            $this->paid_filter !== ''
        ) {

            $orders->where(
                'payment_status',
                $this->paid_filter
            );
        }

        if ($this->overdue_filter) {
            $orders->whereDate(
                'delivery_date',
                '<',
                now()->toDateString()
            )
            ->whereNotIn(
                'status',
                [3,4]
            );
        }

        // Quick Filters
        if ($this->quick_filter == 'tomorrow') {

            $orders->whereDate(
                'delivery_date',
                \Carbon\Carbon::tomorrow()->toDateString()
            )->whereNotIn(
                'status',
                [
                    Order::STATUS_DELIVERED,
                    Order::STATUS_RETURNED
                ]
            );
        }

        elseif ($this->quick_filter == 'delayed') {

            $orders->whereDate(
                'delivery_date',
                '<',
                today()
            )->whereIn(
                'status',
                [
                    Order::STATUS_NEW,
                    Order::STATUS_PROCESSING
                ]
            );
        }

        elseif ($this->quick_filter == 'pickup_overdue') {

            $orders->whereDate(
                'delivery_date',
                '<',
                today()
            )->where(
                'status',
                Order::STATUS_READY
            );
        }

        elseif ($this->quick_filter == 'unpaid') {

            $orders->where(
                'payment_status',
                Order::PAYMENT_UNPAID
            );
        }

        return $orders
            ->latest('id')
            ->cursorPaginate(
                10,
                ['*'],
                'cursor',
                Cursor::fromEncoded(
                    $this->nextCursor
                )
            );
    }

    public function deleteOrder($order)
    {
        $order = Order::find($order);

        if($order)
        {
            $order->update([
                'is_deleted'=>1
            ]);

            OrderDetail::where(
                'order_id',
                $order->id
            )->update([
                'is_deleted'=>1
            ]);

            OrderAddonDetail::where(
                'order_id',
                $order->id
            )->update([
                'is_deleted'=>1
            ]);

            Payment::where(
                'order_id',
                $order->id
            )->update([
                'is_deleted'=>1
            ]);

            OrderArticle::where(
                'order_id',
                $order->id
            )->update([
                'status'=>OrderArticle::STATUS_CANCELLED
            ]);

            $this->reloadOrders();
        }

        $this->dispatch(
            'alert',
            [
                'type'=>'success',
                'message'=>'Order archived successfully'
            ]
        );
    }

    private function resetCursorPagination()
    {
        $this->orders = new EloquentCollection();
        $this->nextCursor = null;
        $this->currentCursor = null;
        $this->hasMorePages = null;
    }
}
