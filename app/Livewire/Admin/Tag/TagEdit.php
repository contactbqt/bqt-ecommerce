<?php

namespace App\Livewire\Admin\Tag;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Tag;
use Flux\Flux;
use Str;
use Illuminate\Validation\Rule;

class TagEdit extends Component
{
    public $tagId;
    public $tag_name;
    public $slug;


    #[On('edit-tag-listener')]
    public function handleedTag($tag_id)
    {
        $tag = Tag::findOrFail($tag_id);
        $this->tagId = $tag->id;
        $this->tag_name = $tag->name;
        $this->slug = $tag->slug;
        Flux::modal('edit-tag')->show();

    }

    protected function rules()
    {
        $rules = [
            'tag_name' => [
                'required',
                'string',
                Rule::unique('tags', 'name')->ignore($this->tagId),
                Rule::unique('tags', 'slug')
                    ->ignore($this->tagId)
            ]
        ];

        return $rules;
    }


    public function EditTag()
    {

        $this->validate();

        $slug = $this->slug ? Str::slug($this->slug) : Str::slug($this->tag_name);

        //save table data
        $tagData = Tag::findOrFail($this->tagId);
        $tagData->name = $this->tag_name;
        $tagData->slug = $slug;
        $tagData->save();
        $this->reset();

        Flux::modal('edit-tag')->close();

        return redirect(request()->header('Referer'))->with('message', 'Tag updated successfully.');
    }

    public function render()
    {
        $data = array();
        return view('livewire.admin.tag.tag-edit', $data);
    }
}
