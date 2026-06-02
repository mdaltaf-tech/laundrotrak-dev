<?php

namespace App\Livewire\ServiceCategory;

use Livewire\Component;
use App\Models\ServiceCategory;
use App\Models\Translation;
use Livewire\Attributes\Title;

class ServiceCategoryList extends Component
{
    public $categories;
    public $search_query;
    public $lang;

    #[Title('Service Categories')]
    public function render()
    {
        return view(
            'livewire.service-category.service-category-list'
        );
    }

    public function mount()
    {
        $this->categories =
            ServiceCategory::withCount('services')
                ->orderBy('sort_order')
                ->get();

        if(session()->has('selected_language'))
        {
            $this->lang = Translation::where(
                'id',
                session()->get('selected_language')
            )->first();
        }
        else
        {
            $this->lang = Translation::where(
                'default',
                1
            )->first();
        }
    }

    public function updated($name,$value)
    {
        if(
            $name == 'search_query'
            && $value != ''
        )
        {
            $this->categories =
                ServiceCategory::where(
                    'category_name',
                    'like',
                    '%'.$value.'%'
                )
                ->orderBy('sort_order')
                ->get();
        }
        elseif(
            $name == 'search_query'
            && $value == ''
        )
        {
            $this->categories =
                ServiceCategory::orderBy(
                    'sort_order'
                )->get();
        }
    }

    public function delete($id)
    {
        $category =
            ServiceCategory::find($id);

        if(
            $category->services()
            ->count() > 0
        )
        {
            $this->dispatch(
                'alert',
                [
                    'type'=>'error',
                    'message'=>
                    'Cannot delete category. Services are mapped.'
                ]
            );

            return;
        }

        $category->delete();

        $this->categories =
            ServiceCategory::orderBy(
                'sort_order'
            )->get();

        $this->dispatch(
            'alert',
            [
                'type'=>'success',
                'message'=>'Category deleted successfully'
            ]
        );
    }
}
