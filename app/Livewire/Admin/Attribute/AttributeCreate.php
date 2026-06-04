<?php

namespace App\Livewire\Admin\Attribute;

use Flux\Flux;
use Livewire\Component;
use App\Models\Attribute;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Str;

class AttributeCreate extends Component
{
    public $attribute_name;
    public $slug;
    public $is_filter;
    public $is_variant;
    public $display_type;
    public $status;

    protected function rules()
    {
        return [
            'attribute_name' => [
                'required',
                'string',
                Rule::unique('attributes', 'attribute_name')
                    ->where(fn ($query) => $query->where('is_variant', $this->is_variant ? 1 : 0))
                    ->where(fn ($query) => $query->where('is_filter', $this->is_filter ? 1 : 0))
            ],
            'slug' => [
                'required',
                'string',
                Rule::unique('attributes', 'slug')
                    ->where(fn ($query) => $query->where('is_variant', $this->is_variant ? 1 : 0))
                    ->where(fn ($query) => $query->where('is_filter', $this->is_filter ? 1 : 0))
            ],
            'is_filter' => 'nullable|boolean',
            'is_variant' => 'nullable|boolean',
            'display_type' => 'required|string',
            'status'=>'required'
        ];
    }

    public function createAttribute()
    {
        
        $this->validate();

        // Ensure at least one of is_filter or is_variant is true
        if (!$this->is_filter && !$this->is_variant) {
            $this->addError('is_filter', 'At least one of Filter or Variant must be selected.');
            return;
        }

        $slug = Str::slug($this->attribute_name);

        Attribute::create([
            'attribute_name' => $this->attribute_name,
            'slug' => $slug,
            'is_filter' => $this->is_filter ? 1 : 0,
            'is_variant' => $this->is_variant ? 1 : 0,
            'display_type' => $this->display_type,
            'status' => $this->status
        ]);


        //Reset the name after creation
        $this->reset();

        // Close the modal
        Flux::modal('create-attribute')->close();


        // Redirect to the index page
        return redirect()->route('admin.attribute.index')->with('message', 'Attribute created successfully!');

    }

    public function render()
    {
        $data = array();
        return view('livewire.admin.attribute.attribute-create', $data);
    }
}
