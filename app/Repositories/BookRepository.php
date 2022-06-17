<?php
namespace App\Repositories;

use App\Models\Book;

class BookRepository implements BookInterface
{
    protected $bookModel;
    public function __construct(Book $bookModel)
    {
        $this->bookModel = $bookModel;
    }
    public function getAll()
    {
        return $this->bookModel->all();
    }
    public function getById($id)
    {
        return $this->bookModel->find($id);
    }
    public function create($data)
    {
        return $this->bookModel->create($data);
    }
    public function update($id, $data)
    {
        $book = $this->bookModel->find($id);
        $book->update($data);
        return $book;
    }
    public function delete($id)
    {
        $book = $this->bookModel->find($id);
        $book->delete();
        return $book;
    }

    
    public function getTheMostDiscountBooks()
    {
        $books =$this->bookModel
        ->join('discount','book.id','=','discount.book_id')
        ->select('book.*','discount.*')
        ->orderBy('discount.discount_price','desc')
        ->limit(10)
        ->get();
        return  $books;
    }

    public function getTheMostRatingStartsBooks()
    {
        //select "book".*, "review"."rating_star" from "book" inner join "review" on "book"."id" = "review"."book_id" order by "review"."rating_star" desc limit 8

        $books =$this->bookModel
        ->join('review','book.id','=','review.book_id')
        ->select('book.*','review.rating_star')
        ->orderBy('review.rating_star','desc')
        ->limit(8)
        ->get();
        return  $books;
    }

    public function getTheMostReviewBooks()
    {
        // Popular: get top 8 books with most reviews - total 
        // number review of a book and lowest final price
        
    }



}