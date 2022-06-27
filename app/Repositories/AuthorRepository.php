<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepository implements BaseInterface
{
    public function getAll()
    {
        return Author::all();
    }
    
    public function getById($id)
    {
        return Author::find($id);
    }
    
    public function create($data)
    {
        return Author::create($data);
    }
    
    public function update($id, $data)
    {
        $author = Author::find($id);
        $author->name = $data['name'];
        $author->save();
        return $author;
    }
    
    public function delete($id)
    {
        $author = Author::find($id);
        $author->delete();
        return $author;
    }
}