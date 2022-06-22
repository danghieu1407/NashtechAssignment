<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'book';

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Author()
    {
        return $this->belongsTo(Author::class);
    }

    public function Discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function Review()
    {
        return $this->hasMany(Review::class);
    }

    public function Order_item()
    {
        return $this->hasMany(Order_item::class);
    }
}
