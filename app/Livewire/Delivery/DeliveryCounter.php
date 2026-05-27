<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Order;
use App\Models\OrderArticle;

class DeliveryCounter extends Component
{
    #[Title('Delivery Counter')]

    public $search='';
    public $orders=[];
    public $selectedOrder=null;
    public $selectedOrderId = null;
    public $deliveryArticles=[];
    public $selectedArticles=[];
    public $selectAll=false;

    public function updatedSearch()
    {
        $this->loadOrders();
    }

    public function render()
    {
        return view(
            'livewire.delivery.delivery-counter'
        );
    }

    public function openDelivery($orderId)
    {
        $this->selectedOrderId = $orderId;

        $this->deliveryArticles =
            OrderArticle::where('order_id',$orderId)
                ->where('status',OrderArticle::STATUS_READY)
                ->get();

        $this->selectedArticles=[];
        $this->selectAll=false;

        $this->dispatch(
            'open-delivery-modal'
        );
    }

    public function updatedSelectAll($value)
    {
        if ($value) {

            $this->selectedArticles = collect($this->deliveryArticles)
                ->pluck('id')
                ->toArray();

        } else {

            $this->selectedArticles = [];
        }
    }

    public function updatedSelectedArticles()
    {
        $this->selectAll =
            count($this->deliveryArticles) > 0
            &&
            count($this->selectedArticles)
            ==
            count($this->deliveryArticles);
    }

    public function deliverSelected()
    {
        if(empty($this->selectedArticles))
        {
            return;
        }

        OrderArticle::whereIn(
            'id',
            $this->selectedArticles
        )->update([
            'status' => OrderArticle::STATUS_DELIVERED
        ]);

        // Close modal FIRST
        $this->dispatch('close-delivery-modal');

        // Reset values AFTER
        $this->selectedArticles=[];
        $this->selectAll=false;

        // Refresh ready garments
        $this->deliveryArticles =
            OrderArticle::where(
                'order_id',
                $this->selectedOrderId
            )
            ->where(
                'status',
                OrderArticle::STATUS_READY
            )
            ->get();

        // Refresh order counts
        $this->loadOrders();

        $this->dispatch(
            'success',
            'Garments delivered successfully'
        );
    }

    public function loadOrders()
    {
        if(empty($this->search))
        {
            $this->orders=[];

            return;
        }

        $this->orders =
            Order::active()

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

            ->limit(20)
            ->get();
    }
}
