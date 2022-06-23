<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\Discount;
use DB;
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

    public function finalPrice()
    {
        $date = date('Y-m-d');
        return $this->bookModel
            ->leftJoin('discount', 'book.id', '=', 'discount.book_id')
            ->select('book.id', 'book.book_price', 'book.book_cover_photo', 'discount.discount_price', 'discount.discount_start_date', 'discount.discount_end_date')
            ->selectRaw('
        (CASE 
            WHEN discount.discount_price IS NULL 
            THEN book.book_price

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
            END) AS final_price', [$date, $date, $date, $date]);
    }
    //10 Books on Sale

    public function getTheMostDiscountBooks()
    {
        $date = date('Y-m-d');
        $finalPrice = $this->finalPrice();
        $books = $this->bookModel
            ->joinSub($finalPrice, 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo','check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price')
            ->selectRaw('book.book_price - check_final_price.discount_price as subprice')
            ->where('check_final_price.discount_price', '!=', null)
            ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
            ->orderBy('subprice', 'desc')
            ->limit(10)
            ->get();

        return response ()->json($books); 

    }



    public function calculateRating(){
        //ví dụ: 1 rate 2sao, 3 rate 5 sao => (số lượng rate x số sao + số lượng rate x số sao) : count = 17:4
        $calculate = $this->bookModel
        ->join('review', 'book.id', '=', 'review.book_id')
        ->selectRaw('book.id, round(count(review.id),2) as count, round(sum(review.rating_star),2) as sum')
        ->groupBy('book.id');

        $result = $this->bookModel
        ->joinSub($calculate, 'calculate', function ($join) {
            $join->on('book.id', '=', 'calculate.id');
        })
        ->select('book.*', 'calculate.count', 'calculate.sum')
        ->selectRaw('round(calculate.sum/calculate.count,2) as rating')

        ->orderBy( 'rating', 'desc');

        return  $result;

    }
    public function getTheMostRatingStartsBooks()
    {
        $finalPrice = $this->finalPrice();
        $ratingStar = $this->calculateRating();
        $books = $this->bookModel
            ->joinSub($finalPrice, 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->joinSub($ratingStar, 'calculate', function ($join) {
                $join->on('book.id', '=', 'calculate.id');
            })
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo','check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price', 'calculate.count', 'calculate.sum', 'calculate.rating')

            ->get();
        return  $books;
    }

    public function getTheMostReviewBooks()
    {
        $finalPrice = $this->finalPrice();
        $books = $this->bookModel
            ->join('review', 'review.book_id', '=', 'book.id')
            ->joinSub($finalPrice, 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo','check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price')
            ->selectRaw('book.*,count(review.id) as total_review')
            ->groupBy('book.id', 'check_final_price.id' ,'check_final_price.book_price', 'check_final_price.book_cover_photo','check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price')
            ->orderBy('total_review', 'desc')
            
            ->limit(8)
            ->get();
        return  $books;
    }

    public function sortByCategoryName($name,  Request $params)
    {
        $date = date('Y-m-d');
        //pagination
        $pageIndex = $pageIndex ?? self::PAGE_INDEX_DEFAULT;

        $limit = $limit ?? self::LIMIT_DEFAULT;
        $offset = ($pageIndex - 1) * $limit;

        // sort product  by category name
        $books = $this->bookModel
            ->join('category', 'category.id', '=', 'book.category_id')
            ->select('book.*', 'category_name')
            ->where('category_name', '=', $name)
            ->selectRaw(
                "book.* ,
        (CASE WHEN  
            EXISTS (
                SELECT discount.discount_price FROM discount 
                WHERE discount.book_id = book.id 
            
            )
            THEN (
             CASE WHEN
                EXISTS (
                    SELECT discount.discount_price FROM discount 
                    WHERE discount.book_id = book.id 
                    AND discount.discount_start_date <= '$date'
                    AND (discount.discount_end_date >= '$date'
                    OR discount.discount_end_date IS NULL)
                )
                THEN (
                    SELECT discount.discount_price FROM discount
                    WHERE discount.book_id = book.id
                    AND discount.discount_start_date <= '$date'
                    AND (discount.discount_end_date >= '$date'
                    OR discount.discount_end_date IS NULL)
                )
                ELSE (
                    book.book_price
                )
                END
            
            )
            ELSE (
                book.book_price
            )
            END) as finalprice"

            );



        $items = $books->offset($offset)->limit($limit)->get();

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
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('book.*', 'author.author_name')
            ->where('author.author_name', '=', $name);

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
            ->join('review', 'book.id', '=', 'review.book_id')
            ->selectRaw('book.*,avg(rating_star) as avg_rating_star')
            ->groupBy('book.id')
            ->orderBy('avg_rating_star', 'desc')
            ->limit(10)
            ->get();
        return  $books;
    }

    public function checkDiscount()
    {
        $now = date('Y-m-d');
        $books = $this->bookModel
            ->join('discount', 'book.id', '=', 'discount.book_id')
            ->selectRaw('book.*', 'discount.*', `IF(discount_start_date <=  {$now} && discount_end_date >=  {$now} ,  book.book_price - discount.discount_prince as total , book.book_price as total) as sub_price`)
            ->orderBy('sub_price', 'desc')
            ->limit(10)
            ->get();
        return  $books;
    }
}
