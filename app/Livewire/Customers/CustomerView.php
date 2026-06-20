<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Translation;
use Livewire\Attributes\Title;
use Livewire\Component;

class CustomerView extends Component
{
    public $customer, $invoice_amount, $payment, $invoice_count, $orders, $balance, $order, $customer_name, $paid_amount, $payment_mode, $search_query, $order_filter, $note,$lang;
    public $creditOutstanding = 0;
    public $creditOrderCount = 0;
    public $oldestCreditDays = 0;

    #[Title('View Customer')]
    public function render()
    {
        return view('livewire.customers.customer-view');
    }

    /* process before render */
    public function mount($id)
    {
        if(!\Illuminate\Support\Facades\Gate::allows('customer_view')){
            abort(404);
        }
        $this->customer = Customer::find($id);

        if (!($this->customer)) {
            abort(404);
        }
        if(session()->has('selected_language'))
        { /* if session has selected laugage*/
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }
        $this->invoice_amount = Order::where('customer_id', $id)->sum('total');
        $this->invoice_count = Order::where('customer_id', $id)->count();
        $this->payment = Payment::where('customer_id', $id)->sum('received_amount');
        $this->balance = $this->invoice_amount - $this->payment;
        $creditOrders = Order::active()
            ->where('customer_id', $id)
            ->where('was_delivered_on_credit', 1)
            ->where('balance_amount', '>', 0)
            ->get();

        $this->creditOutstanding =
            $creditOrders->sum('balance_amount');

        $this->creditOrderCount =
            $creditOrders->count();

        if ($creditOrders->count() > 0) {

            $oldestOrder =
                $creditOrders->sortBy('credit_delivered_at')
                    ->first();

            $this->oldestCreditDays =
                \Carbon\Carbon::parse(
                    $oldestOrder->credit_delivered_at
                )
                ->startOfDay()
                ->diffInDays(
                    today()->startOfDay()
                );
        }
    }
}
