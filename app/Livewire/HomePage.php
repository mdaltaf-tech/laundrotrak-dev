<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderArticle;
use App\Models\Payment;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    #[Title('Dashboard')]
    public $pending_count,$processing_count,$ready_count,$delivered_count,$orders,$array,$search_query,$order_filter,$lang;
    public $totalOverdueOrders = 0;
    public $tomorrowGarments = 0;
    public $totalTomorrowOrders = 0;
    public $overduePickups = 0;
    public $delayedGarments = 0;
    public $totalDelayedOrders = 0;
    public $overdueGarments = 0;
    public $delayedOrderList;
    public $overduePickupList;
    public $unpaid_count;
    public $today_collection;
    public $todayCash = 0;
    public $todayUpi = 0;
    public $pendingCollection = 0;
    public $unpaidOrderCount = 0;
    public $todayDelivered = 0;
    public $monthlyOrders = 0;

    public function render()
    {
        $this->pending_count = Order::active()
            ->where('status', Order::STATUS_NEW)
            ->count();

        $this->processing_count = Order::active()
            ->where('status', Order::STATUS_PROCESSING)
            ->count();

        $this->ready_count = Order::active()
            ->where('status', Order::STATUS_READY)
            ->count();

        $this->delivered_count = Order::active()
            ->where(
                'status',
                Order::STATUS_DELIVERED
            )
            ->count();

        return view('livewire.home-page');
    }

    /* process before mount */
    public function mount()
    {
        $this->todayDelivered = Order::active()
            ->where('status', Order::STATUS_DELIVERED)
            ->whereDate('updated_at', today())
            ->count();

        $tomorrowOrdersQuery = Order::active()
            ->whereDate(
                'delivery_date',
                Carbon::tomorrow()->toDateString()
            )
            ->whereNotIn(
                'status',
                [
                    Order::STATUS_DELIVERED,
                    Order::STATUS_RETURNED
                ]
            );

        $this->totalTomorrowOrders =
            (clone $tomorrowOrdersQuery)->count();

        $allTomorrowOrderIds =
            (clone $tomorrowOrdersQuery)->pluck('id');

        $this->tomorrowGarments =
            OrderArticle::active()
                ->whereIn('order_id', $allTomorrowOrderIds)
                ->count();

        $this->orders =
            (clone $tomorrowOrdersQuery)
                ->orderBy('status')
                ->orderBy('delivery_date')
                ->limit(4)
                ->get();

        $this->orders->each(function ($order) {
            $order->garment_count =
                OrderArticle::active()
                    ->where('order_id', $order->id)
                    ->count();
        });

        $delayedQuery = Order::active()
            ->whereDate('delivery_date', '<', today())
            ->whereIn(
                'status',
                [
                    Order::STATUS_NEW,
                    Order::STATUS_PROCESSING
                ]
            );

        $this->totalDelayedOrders =
            (clone $delayedQuery)->count();

        $allDelayedOrderIds =
            (clone $delayedQuery)->pluck('id');

        $this->delayedGarments =
            OrderArticle::active()
                ->whereIn('order_id', $allDelayedOrderIds)
                ->count();

        $this->delayedOrderList =
            (clone $delayedQuery)
                ->orderBy('delivery_date')
                ->limit(4)
                ->get();

        $this->delayedOrderList->each(function ($order) {

            $order->garment_count =
                OrderArticle::active()
                    ->where('order_id', $order->id)
                    ->count();

        });

        $overdueQuery = Order::active()
            ->whereDate('delivery_date', '<', today())
            ->where(
                'status',
                Order::STATUS_READY
            );

        $this->totalOverdueOrders =
            (clone $overdueQuery)->count();

        $allOverdueOrderIds =
            (clone $overdueQuery)->pluck('id');

        $this->overduePickups = $this->totalOverdueOrders;

        $this->overdueGarments =
            OrderArticle::active()
                ->whereIn('order_id', $allOverdueOrderIds)
                ->count();

        $this->overduePickupList =
            (clone $overdueQuery)
                ->orderBy('delivery_date')
                ->limit(4)
                ->get();

        $this->overduePickupList->each(function ($order) {
            $order->garment_count =
                OrderArticle::active()
                    ->where('order_id', $order->id)
                    ->count();
        });

        $pendingOrders = Order::active()
            ->where('balance_amount', '>', 0);

        $this->pendingCollection =
            (clone $pendingOrders)->sum('balance_amount');

        $this->unpaidOrderCount =
            (clone $pendingOrders)->count();

        $this->unpaid_count = Order::active()
            ->where(
                'payment_status',
                Order::PAYMENT_UNPAID
            )
            ->count();

        $this->today_collection = Payment::active()
            ->whereDate('payment_date', today())
            ->sum('received_amount');

        $this->todayCash = Payment::active()
            ->whereDate('payment_date', today())
            ->where('payment_type', 1)
            ->sum('received_amount');

        $this->todayUpi = Payment::active()
            ->whereDate('payment_date', today())
            ->where('payment_type', 2)
            ->sum('received_amount');

        $this->monthlyOrders = Order::active()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
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
