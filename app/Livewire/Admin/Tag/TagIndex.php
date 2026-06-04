<?php

namespace App\Livewire\Admin\Tag;

use Livewire\Component;
use App\Models\Tag;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.admin-app')]
class TagIndex extends Component
{
    use WithPagination;

    public $tagId;
    public $searchName = '';
    public $tag = '';

    public function render()
    {
        $data = array();
        $data['tagList'] = Tag::when($this->searchName, function ($query) {
                $query->where('name', 'like', '%' . $this->searchName . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(config('constants.pagination_limit'));
        //dd($data['tagList']);
                
        return view('livewire.admin.tag.tag-index', $data);
    }

    public function edit($id)
    {
        $this->dispatch('edit-tag-listener', tag_id: $id);
    }

    public function deleteConfirmModal($id)
    {
        $this->tagId = $id;
        Flux::modal('delete-tag')->show();
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
        $tag = Tag::findOrFail($this->tagId)->delete();

        // Redirect to the index page
        return redirect(request()->header('Referer'))->with('message', 'Tag deleted successfully.');
    }




}
