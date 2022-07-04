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
    public function finalPrice()
    {
        $date = date('Y-m-d');
        return Book::query()
            ->leftJoin('discount', 'book.id', '=', 'discount.book_id')
            ->select('book.id', 'book.book_title', 'book.book_price', 'book.book_cover_photo', 'discount.discount_price', 'discount.discount_start_date', 'discount.discount_end_date')
            ->selectRaw('
        (CASE 
            WHEN discount.discount_price IS NULL 
            THEN book.book_price

            WHEN discount.discount_price IS NOT NULL
            AND discount.discount_end_date < ?
            THEN book_price
            
            WHEN  discount.discount_price IS NOT NULL
            AND discount.discount_start_date <= ?
            THEN discount_price

            WHEN  discount.discount_price IS NOT NULL
            AND discount.discount_start_date IS NULL
            AND discount.discount_end_date >= ?
            THEN discount_price

            WHEN discount.discount_price IS NOT NULL
            AND discount.discount_start_date < ?
            AND ( discount.discount_end_date > ? OR discount.discount_end_date IS NULL)
            THEN discount_price

      

            ELSE book.book_price
            END) AS final_price', [$date, $date, $date, $date, $date]);
    }
    public function scopeFinalPriceOfBook($query){
        $finalPrice = $this->finalPrice();
        return $query  
        ->joinSub($finalPrice, 'check_final_price', function ($join) {
            $join->on('book.id', '=', 'check_final_price.id');
        })
        ->select(
            'check_final_price.id',
            'check_final_price.book_title',
            'check_final_price.book_price',
            'check_final_price.book_cover_photo',
            'check_final_price.discount_price',
            'check_final_price.discount_start_date',
            'check_final_price.discount_end_date',
            'author.author_name',
            'check_final_price.final_price'
        );

    }
}
