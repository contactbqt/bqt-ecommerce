<?php

namespace App\Livewire\Admin\Attribute;

use Livewire\Component;
use App\Models\Attribute;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.admin-app')]
class AttributeIndex extends Component
{
    use WithPagination;

    public $attributeId;
    public $searchName = '';
    public $attribute = '';
    public $status = '';

    public function render()
    {
        $data = array();
        $data['attributeList'] = Attribute::with('attribute_values')->when($this->searchName, function ($query) {
                $query->where('attribute_name', 'like', '%' . $this->searchName . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(config('constants.pagination_limit'));
        //dd($data['attributeList']);
                
        return view('livewire.admin.attribute.attribute-index', $data);
    }

    public function edit($id)
    {
        $this->dispatch('edit-attribute-listener', attribute_id: $id);
    }

    public function deleteConfirmModal($id)
    {
        $this->attributeId = $id;
        Flux::modal('delete-attribute')->show();
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
        $attribute = Attribute::findOrFail($this->attributeId)->delete();

        // Redirect to the index page
        return redirect(request()->header('Referer'))->with('message', 'Attribute deleted successfully.');
    }




}
