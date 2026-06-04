<?php

namespace App\Livewire\Admin\Tag;

use Flux\Flux;
use Livewire\Component;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Str;

class TagCreate extends Component
{
    public $tag_name;
    public $slug;

    protected function rules()
    {
        return [
            'tag_name' => [
                'required',
                'string'
            ]
        ];
    }

    public function createTag()
    {
        
        $this->validate();

        $slug = ($this->slug != '') ? $this->slug : Str::slug($this->tag_name);

        Tag::create([
            'name' => $this->tag_name,
            'slug' => $slug
        ]);


        //Reset the name after creation
        $this->reset();

        // Close the modal
        Flux::modal('create-tag')->close();


        // Redirect to the index page
        return redirect()->route('admin.tag.index')->with('message', 'Tag created successfully!');

    }

    public function render()
    {
        $data = array();
        return view('livewire.admin.tag.tag-create', $data);
    }
}
