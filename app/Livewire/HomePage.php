<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Translation;
use App\Models\Payment;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    #[Title('Dashboard')]
    public $pending_count,$processing_count,$ready_count,$delivered_count,$orders,$array,$search_query,$order_filter,$lang;
    public $delayedOrders = 0;
    public $tomorrowGarments = 0;
    public $totalTomorrowOrders = 0;
    public $overduePickups = 0;
    public $delayedOrderList;
    public $overduePickupList;
    public $unpaid_count;
    public $today_collection;

    public function render()
    {
        $this->pending_count = Order::where('status',0)->count();
        $this->processing_count = Order::where('status',1)->count();
        $this->ready_count = Order::where('status',2)->count();
        $this->delivered_count = Order::where('status',3)->count();
        return view('livewire.home-page');
    }

    /* process before mount */
    public function mount()
    {
        $this->pending_count = Order::where('status',0)->count();
        $this->processing_count = Order::where('status',1)->count();
        $this->ready_count = Order::where('status',2)->count();
        $returned_count =  Order::where('status',4)->count();

        $this->delivered_count = Order::active()
            ->where(
                'status',
                Order::STATUS_DELIVERED
            )
            ->count();

        $this->unpaid_count = Order::active()
            ->where(
                'payment_status',
                Order::PAYMENT_UNPAID
            )
            ->count();

        $this->today_collection = Payment::active()
            ->whereDate(
                'payment_date',
                today()
            )
            ->sum(
                'received_amount'
            );

        $this->delayedOrders = Order::active()
            ->whereDate(
                'delivery_date',
                '<',
                \Carbon\Carbon::today()->toDateString()
            )
            ->whereIn(
                'status',
                [
                    Order::STATUS_NEW,
                    Order::STATUS_PROCESSING
                ]
            )
            ->count();

        $tomorrowOrdersQuery = Order::active()
            ->whereDate(
                'delivery_date',
                \Carbon\Carbon::tomorrow()->toDateString()
            )
            ->whereNotIn(
                'status',
                [
                    Order::STATUS_DELIVERED,
                    Order::STATUS_RETURNED
                ]
            );

        $this->totalTomorrowOrders = $tomorrowOrdersQuery->count();

        $this->orders = $tomorrowOrdersQuery
            ->orderByDesc('status')
            ->orderBy('delivery_date')
            ->limit(8)
            ->get();

        $this->orders->each(function ($order) {
            $order->garment_count =
                \App\Models\OrderArticle::active()
                    ->where('order_id', $order->id)
                    ->count();
        });

        $allTomorrowOrderIds = $tomorrowOrdersQuery
            ->pluck('id');

        $this->tomorrowGarments =
            \App\Models\OrderArticle::active()
                ->whereIn(
                    'order_id',
                    $allTomorrowOrderIds
                )
                ->count();

        $this->overduePickups = Order::active()
            ->whereDate(
                'delivery_date',
                '<',
                \Carbon\Carbon::today()->toDateString()
            )
            ->whereIn(
                'status',
                [
                    Order::STATUS_READY,
                ]
            )
            ->count();

        $this->delayedOrderList = Order::active()
            ->whereDate(
                'delivery_date',
                '<',
                now()->toDateString()
            )
            ->whereIn(
                'status',
                [
                    Order::STATUS_NEW,
                    Order::STATUS_PROCESSING
                ]
            )
            ->orderBy(
                'delivery_date'
            )
            ->limit(5)
            ->get();

        $this->delayedOrderList->each(function ($order) {
            $order->garment_count =
                \App\Models\OrderArticle::active()
                    ->where('order_id', $order->id)
                    ->count();
        });

        $this->overduePickupList = Order::active()
            ->whereDate(
                'delivery_date',
                '<',
                today()
            )
            ->where(
                'status',
                Order::STATUS_READY
            )
            ->orderBy('delivery_date')
            ->limit(5)
            ->get();

        $this->overduePickupList->each(function ($order) {
            $order->garment_count =
                \App\Models\OrderArticle::active()
                    ->where('order_id', $order->id)
                    ->count();
        });

        if(session()->has('selected_language'))
        {
            /* if the session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if the session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
        $this->array = json_encode(array($this->pending_count,$this->processing_count,$this->ready_count,$this->delivered_count,$returned_count));
    }
    /* process while update the element */
    public function updated($name,$value)
    {
        /*if the updated element is search_query and value is not empty */
        if($name == 'search_query' && $value != '')
        {
            if($this->order_filter == '')
            {
                $this->orders = \App\Models\Order::whereDate('delivery_date',\Carbon\Carbon::today()->toDateString())
                                            ->where(function($q) use ($value) {
                                                $q->where('order_number','like','%'.$value.'%')
                                                    ->orwhere('customer_name','like','%'.$value.'%');
                                                })
                                            ->latest()
                                            ->get();
            }
            else{
                $this->orders = \App\Models\Order::where('status',$this->order_filter)
                                            ->whereDate('delivery_date',\Carbon\Carbon::today()->toDateString())
                                            ->where(function($q) use ($value) {
                                                $q->where('order_number','like','%'.$value.'%')
                                                ->orwhere('customer_name','like','%'.$value.'%');
                                            })
                                            ->latest()
                                            ->get();
            }
        }
        elseif($name == 'search_query' && $value == '')
        {
            /* if the updated element is search_query and value is empty */
            if($this->order_filter == '')
            {  /* if the order filter value is empty */
                $this->orders = \App\Models\Order::whereDate('delivery_date',\Carbon\Carbon::today()->toDateString())->latest()->get();
            }
            else{
                /* if the order filter value is not empty */
                $this->orders = \App\Models\Order::whereDate('delivery_date',\Carbon\Carbon::today()->toDateString())->where('status',$value)->latest()->get();

            }
        }
        /* if the updated value is order filter */
        if($name == 'order_filter')
        {
            $this->search_query = '';
            if($value == '')
            {    /* if the order filter value is empty */
                $this->orders = \App\Models\Order::whereDate('delivery_date',\Carbon\Carbon::today()->toDateString())->latest()->get();
            }
            else{
                /* if the order filter value is empty */
                $this->orders = \App\Models\Order::whereDate('delivery_date',\Carbon\Carbon::today()->toDateString())->where('status',$value)->latest()->get();
            }
        }
    }
}
