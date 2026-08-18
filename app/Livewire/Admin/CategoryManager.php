<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryManager extends Component
{
    public $categories;
    public $parentCategories;
    public $name;
    public $parent_id = null;
    public $editingCategoryId;

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        // For hierarchical display in view
        $this->categories = Category::whereNull('parent_id')->with('children')->get();
        // For parent category selection dropdown
        $this->parentCategories = Category::whereNull('parent_id')->get();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        
        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'parent_id' => $this->parent_id ?: null,
        ]);

        $this->reset(['name', 'parent_id']);
        $this->loadCategories();
        session()->flash('status', 'Catégorie / sous-catégorie créée.');
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.admin.category-manager');
    }
}
