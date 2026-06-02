<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Order;
use App\Models\Payment;

class PickupCounter extends Component
{
    #[Title('Pickup Counter')]

    public $search='';

    public $orders=[];

    public function mount()
    {
        $this->loadOrders();
    }

    public function updatedSearch()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        if(empty($this->search))
        {
            $this->orders=[];

            return;
        }

        $this->orders = Order::query()

        ->where(function($q){

            $q->where(
                'order_number',
                'like',
                '%'.$this->search.'%'
            )

            ->orWhere(
                'customer_name',
                'like',
                '%'.$this->search.'%'
            )

            ->orWhere(
                'phone_number',
                'like',
                '%'.$this->search.'%'
            );

        })

        ->with([
            'articles',
            'payments'
        ])

        ->get()

        ->filter(function($order){

            return $order->articles
            ->where('status',2)
            ->count()>0;

        });

    }

    public function render()
    {
        return view(
            'livewire.delivery.pickup-counter'
        );
    }
}
