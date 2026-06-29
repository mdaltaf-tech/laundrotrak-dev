<?php

namespace App\Livewire\Service;

use Livewire\Component;
use App\Models\ServiceCategory;
use App\Models\Translation;
use Livewire\Attributes\Title;

class ServiceCategoryList extends Component
{
    public $category_id;
    public $category_name = '';
    public $sort_order = 1;
    public $is_active = true;
    public $search_query = '';
    public $lang;

    protected function rules()
    {
        return [
            'category_name' => 'required|max:255|unique:service_categories,category_name,' . $this->category_id,
            'sort_order' => 'required|integer|min:1|unique:service_categories,sort_order,' . $this->category_id,
            'is_active'     => 'required|boolean',
        ];
    }

    public function resetFields()
    {
        $this->resetValidation();
        $this->resetErrorBag();

        $this->reset([
            'category_id',
            'category_name',
        ]);

        $this->sort_order = 1;
        $this->is_active = true;
    }

    #[Title('Service Categories')]
    public function render()
    {
        $categories = ServiceCategory::withCount('services')
            ->when($this->search_query,function($query){
                $query->where(function($q){
                    $q->where('category_name','like','%'.$this->search_query.'%')
                    ->orWhere('sort_order',$this->search_query);
                });
            })
            ->orderBy('sort_order')
            ->get();

        return view('livewire.service.service-category-list', [
            'categories' => $categories,
        ]);
    }

    public function mount()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('service_category_list')) {
            abort(404);
        }

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

    public function delete($id)
    {
        $category = ServiceCategory::withCount('services')->findOrFail($id);

        if ($category->services()->exists()) {

            $this->dispatch(
                'alert',
                [
                    'type'=>'error',
                    'message'=>'Category deletion restricted! Move the services before deleting.'
                ]
            );
            return;
        }

        $category->delete();

        $this->dispatch(
            'alert',
            [
                'type'=>'success',
                'message'=>'Category Deleted successfully.'
            ]
        );
    }

    public function create()
    {
        $this->validate();

        ServiceCategory::create([
            'category_name' => $this->category_name,
            'sort_order'    => $this->sort_order,
            'is_active'     => $this->is_active,
        ]);

        $this->resetFields();
        $this->dispatch('closemodal');

        $this->dispatch(
            'alert',
            [
                'type'=>'success',
                'message'=>'Category created successfully.'
            ]
        );
    }

    public function edit($id)
    {
        $category = ServiceCategory::findOrFail($id);
        $this->resetValidation();
        $this->resetErrorBag();
        $this->category_id   = $category->id;
        $this->category_name = $category->category_name;
        $this->sort_order    = $category->sort_order;
        $this->is_active     = $category->is_active;
    }

    public function update()
    {
        $this->validate();

        $category = ServiceCategory::findOrFail($this->category_id);

        $category->update([
            'category_name' => $this->category_name,
            'sort_order'    => $this->sort_order,
            'is_active'     => $this->is_active,
        ]);

        $this->resetFields();
        $this->dispatch('closemodal');

        $this->dispatch(
            'alert',
            [
                'type'=>'success',
                'message'=>'Category updated successfully.'
            ]
        );
    }
}
