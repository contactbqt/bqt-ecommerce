<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use App\Models\Category;
use App\Models\CategoryProductAdditionalInfoMaster;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class CategoryAdditionalInfo extends Component
{
    public $categoryId;
    public $category;
    public $sections = [];

    public function mount($id)
    {
        $this->categoryId = $id;
        $this->category = Category::findOrFail($id);
        
        $masters = CategoryProductAdditionalInfoMaster::where('category_id', $id)->get();
        
        if ($masters->count() > 0) {
            foreach ($masters as $master) {
                $this->sections[] = [
                    'id' => $master->id,
                    'title' => $master->title,
                    'keys' => $master->additional_info, // This is an array of strings
                ];
            }
        } else {
            // Add one default empty section
            $this->addSection();
        }
    }

    public function addSection()
    {
        $this->sections[] = [
            'id' => null,
            'title' => '',
            'keys' => [''],
        ];
    }

    public function removeSection($index)
    {
        unset($this->sections[$index]);
        $this->sections = array_values($this->sections);
    }

    public function addKey($sectionIndex)
    {
        $this->sections[$sectionIndex]['keys'][] = '';
    }

    public function removeKey($sectionIndex, $keyIndex)
    {
        unset($this->sections[$sectionIndex]['keys'][$keyIndex]);
        $this->sections[$sectionIndex]['keys'] = array_values($this->sections[$sectionIndex]['keys']);
    }

    public function save()
    {
        $this->validate([
            'sections.*.title' => 'required|string|max:255',
            'sections.*.keys.*' => 'required|string|max:255',
        ], [
            'sections.*.title.required' => 'The title is required.',
            'sections.*.keys.*.required' => 'The key name is required.',
        ]);

        // Delete existing ones and re-insert or update
        // To keep it simple, we'll delete all and re-insert for this category
        CategoryProductAdditionalInfoMaster::where('category_id', $this->categoryId)->delete();

        foreach ($this->sections as $section) {
            CategoryProductAdditionalInfoMaster::create([
                'category_id' => $this->categoryId,
                'title' => $section['title'],
                'additional_info' => $section['keys'],
            ]);
        }

        session()->flash('message', 'Additional info templates saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.category.category-additional-info');
    }
}
