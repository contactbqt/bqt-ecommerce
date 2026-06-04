<?php

namespace App\Livewire\Admin\Attribute;

use Flux\Flux;
use Livewire\Component;
use App\Models\AttributeValue;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Str;

class AttributeValueCreate extends Component
{
    public $attribute_id;
    public $value_name;
    public $slug;
    public $sort_order;
    public $hexa_color_code;
    public $status;

    public function mount($attribute_id)
    {
        $this->attribute_id = $attribute_id;
    }

    protected function rules()
    {
        return [
            'value_name' => [
                'required',
                'string',
                Rule::unique('attribute_values', 'value_name')->where(fn ($query) => $query->where('attribute_id', $this->attribute_id))
            ],
            'slug' => [
                'required',
                'string',
                Rule::unique('attribute_values', 'slug')->where(fn ($query) => $query->where('attribute_id', $this->attribute_id))
            ],
            'sort_order' => 'required|integer',
            'hexa_color_code' => 'string|nullable',
            'status'=>'required'
        ];
    }

    public function createAttributeValue()
    {
        $this->validate();

        $slug = Str::slug($this->value_name);

        AttributeValue::create([
            'attribute_id' => $this->attribute_id,
            'value_name' => $this->value_name,
            'slug' => $slug,
            'sort_order' => $this->sort_order,
            'hexa_color_code' => $this->hexa_color_code,
            'status' => $this->status
        ]);


        //Reset the name after creation
        $this->reset(['value_name', 'slug', 'sort_order', 'hexa_color_code', 'status']);

        // Close the modal
        Flux::modal('create-attribute-value')->close();

        // Redirect to the index page
        return redirect()->route('admin.attribute.values', ['id' => $this->attribute_id])->with('message', 'Attribute Value created successfully!');

    }

    public function render()
    {
        $data = array();
        return view('livewire.admin.attribute.attribute-value-create', $data);
    }
}
