<?php

namespace App\Livewire\Admin\Attribute;

use Livewire\Component;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.admin-app')]
class AttributeValueIndex extends Component
{
    use WithPagination;

    public $attribute_id;
    public $attributeValueId;
    public $attribute = [];
    public $searchName = '';

    public function mount($id)
    {
        $this->attribute_id = $id;
    }

    public function render()
    {
        $data = array();
        $this->attribute = Attribute::findOrFail($this->attribute_id);
        $data['valueList'] = AttributeValue::where('attribute_id', $this->attribute_id)
            ->when($this->searchName, function ($query) {
                $query->where('value_name', 'like', '%' . $this->searchName . '%');
            })
            ->orderBy('sort_order', 'asc')
            ->paginate(config('constants.pagination_limit'));
        //dd($data['valueList']);
                
        return view('livewire.admin.attribute.attribute-value-index', $data);
    }

    public function edit($id)
    {
        $this->dispatch('edit-attribute-value-listener', attribute_id: $id);
    }

    public function deleteConfirmModal($id)
    {
        $this->attributeValueId = $id;
        Flux::modal('delete-attribute-value')->show();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->searchName = '';
        $this->resetPage();
    }

    public function delete()
    {
        $attributeValue = AttributeValue::findOrFail($this->attributeValueId)->delete();

        // Redirect to the index page
        return redirect(request()->header('Referer'))->with('message', 'Attribute Value deleted successfully.');
    }




}
