<?php

namespace App\Livewire\Admin\Attribute;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Attribute;
use Flux\Flux;
use Str;
use Illuminate\Validation\Rule;

class AttributeEdit extends Component
{
    public $attributeId;
    public $attribute_name;
    public $slug;
    public $is_filter;
    public $is_variant;
    public $display_type;
    public $status;

    #[On('edit-attribute-listener')]
    public function handleedAttribute($attribute_id)
    {
        $attribute = Attribute::findOrFail($attribute_id);
        $this->attributeId = $attribute->id;
        $this->attribute_name = $attribute->attribute_name;
        $this->slug = $attribute->slug;
        $this->is_filter = (boolean) $attribute->is_filter;
        $this->is_variant = (boolean) $attribute->is_variant;
        $this->display_type = $attribute->display_type;
        $this->status = $attribute->status;

        Flux::modal('edit-attribute')->show();

    }

    protected function rules()
    {
        $rules = [
            'attribute_name' => [
                'required',
                'string',
                Rule::unique('attributes', 'attribute_name')
                    ->where(fn ($query) => $query->where('is_variant', $this->is_variant ? 1 : 0))
                    ->where(fn ($query) => $query->where('is_filter', $this->is_filter ? 1 : 0))
                    ->ignore($this->attributeId)
            ],
            'slug' => [
                'required',
                'string',
                Rule::unique('attributes', 'slug')
                    ->where(fn ($query) => $query->where('is_variant', $this->is_variant ? 1 : 0))
                    ->where(fn ($query) => $query->where('is_filter', $this->is_filter ? 1 : 0))
                    ->ignore($this->attributeId)
            ],
            'is_filter' => 'nullable|boolean',
            'is_variant' => 'nullable|boolean',
            'display_type' => 'required|string',
            'status'=>'required'
        ];


        return $rules;
    }


    public function EditAttribute()
    {

        $this->validate();

        // Ensure at least one of is_filter or is_variant is true
        if (!$this->is_filter && !$this->is_variant) {
            $this->addError('is_filter', 'At least one of Filter or Variant must be selected.');
            return;
        }

        $slug = $this->slug ? Str::slug($this->slug) : Str::slug($this->attribute_name);

        //save table data
        $attributeData = Attribute::findOrFail($this->attributeId);
        $attributeData->attribute_name = $this->attribute_name;
        $attributeData->slug = $slug;
        $attributeData->is_filter = $this->is_filter;
        $attributeData->is_variant = $this->is_variant;
        $attributeData->display_type = $this->display_type;
        $attributeData->status = $this->status;

        $attributeData->save();
        $this->reset();

        Flux::modal('edit-attribute')->close();

        return redirect(request()->header('Referer'))->with('message', 'Attribute updated successfully.');

    }

    public function render()
    {
        $data = array();
        return view('livewire.admin.attribute.attribute-edit', $data);
    }
}
