<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\MetaManagement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin-app')]
class CategoryIndex extends Component
{
    use WithFileUploads;

    // Form properties
    public $category_name = '';
    public $slug = '';
    public $parent_id = 0;
    public $image;
    public $is_featured = false;
    public $status = 1;
    public $remove_image = false;

    // Meta properties
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';

    // Edit mode
    public $editMode = false;
    public $editingCategoryId = null;

    // Categories list
    public $categories = [];
    public $parentCategories = [];

    public $category = [];

    protected function rules()
    {
        return [
            'category_name' => 'required|string|max:255',
            'slug' => $this->editMode
                ? 'required|string|max:255|unique:categories,slug,' . $this->editingCategoryId
                : 'required|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_featured' => 'boolean',
            'status' => 'required|in:1,0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::with('children')->where('parent_id', 0)->orderBy('sort_order')->get();
        $this->parentCategories = Category::where('parent_id', 0)->orderBy('category_name')->get();
    }

    public function updatedCategoryName($value)
    {
        if (!$this->editMode) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'category_name' => $this->category_name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id ?? 0,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'sort_order' => 0,
        ];

        if ($this->image) {
            if ($this->editMode) {
                $data['image'] = $this->image;
                //delete old image
                if ($this->category->image && Storage::disk('public_uploads')->exists($this->category->image)) {
                    Storage::disk('public_uploads')->delete($this->category->image);
                }

            }
            $imagePath = $this->image->store('categories', 'public_uploads');
            $data['image'] = $imagePath;
        } elseif ($this->remove_image && $this->editMode) {
            if ($this->category->image && Storage::disk('public_uploads')->exists($this->category->image)) {
                Storage::disk('public_uploads')->delete($this->category->image);
            }
            $data['image'] = null;
        }

        if ($this->editMode) {
            $this->category->update($data);
            $categoryId = $this->category->id;
            session()->flash('message', 'Category updated successfully!');
        } else {
            $category = Category::create($data);
            $categoryId = $category->id;
            session()->flash('message', 'Category created successfully!');
        }

        // Save Meta Data
        MetaManagement::updateOrCreate(
            [
                'section' => 'category',
                'item_id' => $categoryId,
            ],
            [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
            ]
        );

        $this->resetForm();
        $this->loadCategories();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editMode = true;
        $this->editingCategoryId = $id;
        $this->category = $category;
        $this->category_name = $category->category_name;
        $this->slug = $category->slug;
        $this->parent_id = $category->parent_id;
        $this->is_featured = $category->is_featured == 1;
        $this->status = $category->status;

        // Load Meta Data
        $meta = MetaManagement::where('section', 'category')->where('item_id', $id)->first();
        if ($meta) {
            $this->meta_title = $meta->meta_title;
            $this->meta_description = $meta->meta_description;
            $this->meta_keywords = $meta->meta_keywords;
        } else {
            $this->meta_title = '';
            $this->meta_description = '';
            $this->meta_keywords = '';
        }
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        // Delete image if exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        // Delete meta data
        MetaManagement::where('section', 'category')->where('item_id', $id)->delete();

        $category->delete();
        session()->flash('message', 'Category deleted successfully!');
        $this->loadCategories();
    }

    public function resetForm()
    {
        $this->reset(['category_name', 'slug', 'parent_id', 'image', 'is_featured', 'status', 'editMode', 'editingCategoryId', 'remove_image', 'meta_title', 'meta_description', 'meta_keywords']);
    }

    public function updateOrder($items)
    {
        $category = new Category();
        $category->saveItems($items);
        $this->loadCategories();
    }

    public function saveOrder()
    {
        // This will be called from JavaScript with the order data
        $this->dispatch('saveOrder');
        session()->flash('message', 'Category order saved successfully!');
    }

    public function render()
    {
        return view('livewire.admin.category.category-index');
    }
}
