<?php

namespace App\Livewire\Admin\Attribute;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Flux\Flux;
use Str;
use Illuminate\Validation\Rule;

class AttributeValueEdit extends Component
{
    public $attribute_id;
    public $attribute_value_id;
    public $value_name;
    public $slug;
    public $sort_order;
    public $hexa_color_code = '';
    public $status;

    public function mount($attribute_id)
    {
        $this->attribute_id = $attribute_id;
    }

    #[On('edit-attribute-value-listener')]
    public function handleedAttributeValue($attribute_id)
    {
        $attributeValue = AttributeValue::findOrFail($attribute_id);
        $this->attribute_value_id = $attributeValue->id;

        $this->value_name = $attributeValue->value_name;
        $this->slug = $attributeValue->slug;
        $this->sort_order = $attributeValue->sort_order;
        $this->hexa_color_code = $attributeValue->hexa_color_code;
        $this->status = $attributeValue->status;

        Flux::modal('edit-attribute-value')->show();

    }

    protected function rules()
    {
        $rules = [
            'value_name' => [
                'required',
                'string',
                Rule::unique('attribute_values', 'value_name')
                    ->where(fn ($query) => $query->where('attribute_id', $this->attribute_id))
                    ->ignore($this->attribute_value_id)
            ],
            'slug' => [
                'required',
                'string',
                Rule::unique('attribute_values', 'slug')
                    ->where(fn ($query) => $query->where('attribute_id', $this->attribute_id))
                    ->ignore($this->attribute_value_id)
            ],
            'sort_order' => 'required|integer',
            'hexa_color_code' => 'nullable|string',
            'status'=>'required'
        ];


        return $rules;
    }


    public function EditAttributeValue()
    {

        $this->validate();

        $slug = $this->slug ? Str::slug($this->slug) : Str::slug($this->value_name);

        //save table data
        $attributeValueData = AttributeValue::findOrFail($this->attribute_value_id);
        $attributeValueData->value_name = $this->value_name;
        $attributeValueData->slug = $slug;
        $attributeValueData->sort_order = $this->sort_order;
        $attributeValueData->hexa_color_code = ($this->hexa_color_code) ? $this->hexa_color_code : null;
        $attributeValueData->status = $this->status;

        $attributeValueData->save();
        $this->reset();

        Flux::modal('edit-attribute-value')->close();

        return redirect(request()->header('Referer'))->with('message', 'Attribute value updated successfully.');

    }

    public function render()
    {
        $data = array();
        return view('livewire.admin.attribute.attribute-value-edit', $data);
    }
}
