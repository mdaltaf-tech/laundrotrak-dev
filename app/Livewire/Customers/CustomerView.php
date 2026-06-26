<?php

namespace App\Livewire\Customers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
    public $settlement_amount;
    public $settlement_payment_type;
    public $settlement_payment_date;
    public $settlement_notes;

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

    public function openSettlementModal()
    {
        $this->resetErrorBag();
        $this->settlement_amount =
            $this->creditOutstanding;
        $this->settlement_payment_type = '';
        $this->settlement_payment_date =
            now()->format('Y-m-d');
        $this->settlement_notes = '';
    }

    public function settleCustomerCredit()
    {
        $this->validate([
            'settlement_amount' => 'required|numeric|min:0.01',
            'settlement_payment_type' => 'required',
            'settlement_payment_date' => 'required|date',
        ]);

        if ($this->settlement_amount > $this->creditOutstanding) {

            $this->addError(
                'settlement_amount',
                'Amount cannot exceed outstanding credit.'
            );

            return;
        }

        $remainingAmount = $this->settlement_amount;

        $creditOrders = Order::active()
            ->where('customer_id', $this->customer->id)
            ->where('status', Order::STATUS_DELIVERED)
            ->where(
                'payment_status',
                Order::PAYMENT_CREDIT
            )
            ->where('balance_amount', '>', 0)
            ->orderBy('delivery_date')
            ->orderBy('id')
            ->get();

        DB::beginTransaction();

        try {

            foreach ($creditOrders as $order) {
                if ($remainingAmount <= 0) {
                    break;
                }

                $allocation = min(
                    $remainingAmount,
                    $order->balance_amount
                );

                Payment::create([
                    'payment_date' =>
                        $this->settlement_payment_date,
                    'customer_id' =>
                        $this->customer->id,
                    'customer_name' =>
                        $this->customer->name,
                    'payment_note' =>
                        $this->settlement_notes,
                    'order_id' =>
                        $order->id,
                    'payment_type' =>
                        $this->settlement_payment_type,
                    'financial_year_id' =>
                        getFinancialYearId(),
                    'received_amount' =>
                        $allocation,
                    'created_by' =>
                        Auth::id(),
                ]);

                $order->refreshPaymentStatus();
                $order->refresh();
                $remainingAmount -= $allocation;
            }

            DB::commit();
            $this->loadCustomerFinancials();
            $this->loadCustomerCreditSummary();
            $this->dispatch('refreshCustomerInvoices');
            $this->dispatch('refreshCustomerPayments');

            $this->settlement_amount = '';
            $this->settlement_payment_type = '';
            $this->settlement_payment_date = now()->format('Y-m-d');
            $this->settlement_notes = '';

            $this->dispatch('closemodal');

            $this->dispatch(
                'alert',
                [
                    'type' => 'success',
                    'message' => 'Credit settlement completed successfully.'
                ]
            );

        } catch (\Exception $e) {

            DB::rollBack();

            logger()->error(
                'Credit Settlement Error',
                [
                    'message' => $e->getMessage()
                ]
            );

            $this->dispatch(
                'alert',
                [
                    'type' => 'error',
                    'message' => 'Unable to settle credit.'
                ]
            );
        }
    }

    private function loadCustomerCreditSummary()
    {
        $creditOrders = Order::active()
            ->where('customer_id', $this->customer->id)
            ->where(
                'payment_status',
                Order::PAYMENT_CREDIT
            )
            ->where(
                'status',
                Order::STATUS_DELIVERED
            )
            ->where('balance_amount', '>', 0);

        $this->creditOutstanding =
            (clone $creditOrders)->sum('balance_amount');

        $this->creditOrderCount =
            (clone $creditOrders)->count();

        $oldestCreditDate =
            (clone $creditOrders)
                ->orderBy('credit_delivered_at')
                ->value('credit_delivered_at');

        $this->oldestCreditDays =
            $oldestCreditDate
                ? Carbon::parse($oldestCreditDate)
                    ->startOfDay()
                    ->diffInDays(today())
                : 0;
    }

    private function loadCustomerFinancials()
    {
        $customerId = $this->customer->id;

        $this->invoice_amount =
            Order::where('customer_id', $customerId)
                ->sum('total');

        $this->invoice_count =
            Order::where('customer_id', $customerId)
                ->count();

        $this->payment =
            Payment::active()
                ->where('customer_id', $customerId)
                ->sum('received_amount');

        $this->balance =
            $this->invoice_amount - $this->payment;
    }
}
