<?php

namespace App\Livewire\Garments;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\OrderArticle;

class GarmentTracking extends Component
{
    #[Title('Garment Tracking')]

    public $search = '';

    public $articles = [];

    public function updatedSearch()
    {
        if(empty($this->search))
        {
            $this->articles = [];
            return;
        }

        $this->articles =
        OrderArticle::active()

        ->with('order')

        ->where(function($q){

            $q->where(
                'tag_number',
                'like',
                '%'.$this->search.'%'
            )

            ->orWhereHas(
                'order',
                function($query){

                    $query
                    ->where(
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

                }
            );

        })

        ->limit(100)
        ->get();
    }

    public function updateStatus($articleId,$status)
    {
        $article = OrderArticle::find($articleId);

        if(!$article){
            return;
        }

        // prevent changing cancelled items
        if(
            $article->status ==
            OrderArticle::STATUS_CANCELLED
        ){
            return;
        }

        $article->status = $status;

        // store timestamps
        if(
            $status ==
            OrderArticle::STATUS_PROCESSING
        ){
            $article->processing_at = now();
        }

        if(
            $status ==
            OrderArticle::STATUS_READY
        ){
            $article->ready_at = now();
        }

        if(
            $status ==
            OrderArticle::STATUS_DELIVERED
        ){
            $article->delivered_at = now();
        }

        $article->save();

        // refresh search results
        $this->updatedSearch();

        $this->dispatch(
            'focus-search'
        );

        $this->dispatch(
            'alert',
            [
                'type'=>'success',
                'title'=>'Updated',
                'message'=>'Garment status updated'
            ]
        );
    }

    public function render()
    {
        return view(
            'livewire.garments.garment-tracking'
        );
    }
}
