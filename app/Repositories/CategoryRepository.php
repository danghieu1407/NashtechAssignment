<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository implements BaseInterface
{
    public function getAll()
    {
        return Category::all();
    }
    
    public function getById($id)
    {
        return Category::find($id);
    }
    
    public function create($data)
    {
        return Category::create($data);
    }
    
    public function update($id, $data)
    {
        $category = Category::find($id);
        $category->name = $data['name'];
        $category->save();
        return $category;
    }
    
    public function delete($id)
    {
        $category = Category::find($id);
        $category->delete();
        return $category;
    }
}

