<?php
namespace App\Repositories;

use App\Models\Book;
use App\Models\Discount;

use Illuminate\Http\Request;


class BookRepository implements BaseInterface
{
    //pagination
    const LIMIT_DEFAULT = 5;
    const PAGE_INDEX_DEFAULT = 1;
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
        
        //sub_price = book_price@books table – discount_price@discounts table
        $books = $this->bookModel
        ->join('discount', 'discount.book_id', '=', 'book.id')
        ->select('book.*', "discount.*",
            \DB::raw("(book.book_price - discount.discount_price) as sub_price")
        )
        
        ->where('discount.discount_start_date', '<=', date('Y-m-d'))
        ->where('discount.discount_end_date', '>=', date('Y-m-d')) 
        ->orWhereNull('discount.discount__date')
        ->orderBy('sub_price', 'desc')
        ->limit(10)
        ->get();
        
        return $books;
    }

    


    public function getTheMostRatingStartsBooks()
    {   
        //get discount price in $discount_price
    
        $date = date('Y-m-d');
        $books = $this->bookModel
        ->join('review','book.id','=','review.book_id')
        ->selectRaw('book.*,round(avg(rating_star),2) as avg_rating_star')
        ->selectRaw('discount.*, book.*,(book.book_price - discount.discount_price) as sub_price')
        ->selectRaw("book.*, 
        (CASE WHEN  
            EXISTS (
                SELECT discount.discount_price FROM discount 
                WHERE discount.book_id = book.id 
                AND discount.discount_start_date <= '$date'
                AND discount.discount_end_date >= '$date'
                
            )
            THEN (
                 book.book_price 
            )
            ELSE (
                sub_price
            )
            END
            ) as finalprice"
            )
        ->groupBy('book.id')
        ->orderBy('avg_rating_star','desc')
        ->orderBy('finalprice','asc')
        ->limit(10)
        ->get();
            
        return  $books;
    }

    public function getTheMostReviewBooks()
    {
        // Popular: get top 8 books with most reviews - total 
        // number review of a book and lowest final price
        $books = $this->bookModel
        ->join('review','book.id','=','review.book_id')
        ->selectRaw('book.*,count(review.id) as total_review')
        ->groupBy('book.id')
        ->orderBy('total_review','desc')
        ->limit(8)
        ->get();
        return  $books;
        
    }

    public function sortByCategoryName($name, Request $params) 
    {
        //pagination
        $pageIndex = $params['pageIndex'] ?? self::PAGE_INDEX_DEFAULT;

        $limit = $params['limit'] ?? self::LIMIT_DEFAULT;
        $offset = ($pageIndex - 1) * $limit;
        
        // sort by category name
         $books = $this->bookModel
        ->join('category','book.category_id','=','category.id')
        ->select('book.*','category.category_name')
        ->where('category.category_name','=',$name)
        ->get();
        $items = $books->slice($offset, $limit);
        
        return [
            'items' => $items,
            'total' => $books->count(),
            'pageIndex' => $pageIndex,
            'limit' => $limit,
        ];
    }

    public function sortByAuthor($name, $pageIndex, $limit) 
    {
        //pagination
        $pageIndex = $pageIndex ?? self::PAGE_INDEX_DEFAULT;

        $limit = $limit ?? self::LIMIT_DEFAULT;
        $offset = ($pageIndex - 1) * $limit;
        
        // sort by author name
     
        $books = $this->bookModel
        ->join('author','book.author_id','=','author.id')
        ->select('book.*','author.author_name')
        ->where('author.author_name','=',$name);
        
        $items = $books->offset($offset)->limit($limit)->get();
        
        return [
            'items' => $items,
            'total' => $books->count(),
            'pageIndex' => $pageIndex,
            'limit' => $limit,
        ];
    }
    public function sortByRattingReview($star)
    {
    
        $books = $this->bookModel
        ->join('review','book.id','=','review.book_id')
        ->selectRaw('book.*,avg(rating_star) as avg_rating_star')
        ->groupBy('book.id')
        ->orderBy('avg_rating_star','desc')
        ->limit(10)
        ->get();
        return  $books;
    }

    public function checkDiscount(){
        $now = date('Y-m-d');
        $books =$this->bookModel
        ->join('discount','book.id','=','discount.book_id')
        ->selectRaw('book.*','discount.*', `IF(discount_start_date <=  {$now} && discount_end_date >=  {$now} ,  book.book_price - discount.discount_prince as total , book.book_price as total) as sub_price`)
        ->orderBy('sub_price','desc')
        ->limit(10)
        ->get();
        return  $books;
    }
    

}