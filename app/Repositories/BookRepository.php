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
    //10 Books on Sale
    public function getTheMostDiscountBooks()
    {
        $finalPrice = $this->finalPrice();
        $books = $this->bookModel
            ->joinSub($finalPrice, 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select(
            'check_final_price.id', 
            'check_final_price.book_price', 
            'check_final_price.book_cover_photo',
            'check_final_price.discount_price', 
            'check_final_price.discount_start_date', 
            'check_final_price.discount_end_date', 
            'author.author_name', 
            'check_final_price.final_price')
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
        ->selectRaw('book.id, round(count(review.id),0) as count, round(sum(review.rating_star),0) as sum')
        ->groupBy('book.id');

        $result = $this->bookModel
        ->joinSub($calculate, 'calculate', function ($join) {
            $join->on('book.id', '=', 'calculate.id');
        })
        ->select('book.*', 'calculate.count', 'calculate.sum')
        ->selectRaw('round(calculate.sum/calculate.count,0) as rating')

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
            ->select(
                'check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo','check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price', 'calculate.count', 'calculate.sum', 'calculate.rating')
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
            ->orderBy('final_price', 'asc')
            
            ->limit(8)
            ->get();
        return  $books;
    }

    public function filterByCategoryName( Request $params)
    {
        $pageIndex = $params['pageIndex'] ?? self::PAGE_INDEX_DEFAULT;
        $limit = $params['limit'] ?? self::LIMIT_DEFAULT;
        $offset = ($pageIndex - 1) * $limit;

        // sort product  by category name
        $books = $this->bookModel
            ->join('category', 'category.id', '=', 'book.category_id')
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('book.*', 'category_name')
            ->where('category_name', '=', $params['category_name'])
            ->joinSub($this->finalPrice(), 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->select(
                'check_final_price.id',
                'check_final_price.book_price',
                'check_final_price.book_cover_photo',
                'check_final_price.discount_price',
                'check_final_price.discount_start_date', 
                'check_final_price.discount_end_date', 
                'author.author_name', 
                'check_final_price.final_price', 
                'category_name','author.author_name',
                'book.book_title');
            if(isset($params['sort_by_on_sale'])){
                $books
                ->where('check_final_price.discount_price', '!=', null)
                ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                ->orderBy('check_final_price.final_price', 'asc');
            }       
            if(isset($params['sort_by_popular'])){
                $books 
                ->join('review', 'review.book_id', '=', 'book.id')
                ->selectRaw('book.id,count(review.id) as total_review')
                ->groupBy(
                    'book.id', 
                    'check_final_price.id' ,
                    'check_final_price.book_price',
                    'check_final_price.book_cover_photo',
                    'check_final_price.discount_price', 
                    'check_final_price.discount_start_date', 
                    'check_final_price.discount_end_date',
                    'author.author_name', 
                    'check_final_price.final_price')
                ->orderBy('check_final_price.final_price', 'asc')
                ->orderBy('total_review', 'desc');
            }
            if(isset($params['sort_by_price_asc'])){
                $books->orderBy('check_final_price.final_price', 'asc');
            }
            if(isset($params['sort_by_price_desc'])){
                $books->orderBy('check_final_price.final_price', 'desc');
            }

        $items = $books->offset($offset)->limit($limit)->get();

        return [
            'items' => $items,
            'total' => $books->count(),
            'pageIndex' => $pageIndex,
            'limit' => $limit,
        ];
    }

    public function filterByAuthor(Request $params)
    {
        //pagination
        $pageIndex = $params['pageIndex'] ?? self::PAGE_INDEX_DEFAULT;
        $limit = $params['limit'] ?? self::LIMIT_DEFAULT;
        $offset = ($pageIndex - 1) * $limit;
   
        $books = $this->bookModel
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('book.*', 'author.author_name')
            ->where('author.author_name', '=', $params['author_name'])
            ->joinSub($this->finalPrice(), 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->select(
            'check_final_price.id', 
            'check_final_price.book_price', 
            'check_final_price.book_cover_photo',
            'check_final_price.discount_price', 
            'check_final_price.discount_start_date', 
            'check_final_price.discount_end_date', 
            'author.author_name', 
            'check_final_price.final_price');
        if(isset($params['sort_by_on_sale'])){
            $books
            ->where('check_final_price.discount_price', '!=', null)
            ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
            ->orderBy('check_final_price.final_price', 'asc');
        }       
        if(isset($params['sort_by_popular'])){
            $books 
            ->join('review', 'review.book_id', '=', 'book.id')
            ->selectRaw('book.id,count(review.id) as total_review')
            ->groupBy(
                'book.id', 
                'check_final_price.id' ,
                'check_final_price.book_price',
                'check_final_price.book_cover_photo',
                'check_final_price.discount_price', 
                'check_final_price.discount_start_date', 
                'check_final_price.discount_end_date',
                'author.author_name', 
                'check_final_price.final_price')
            ->orderBy('check_final_price.final_price', 'asc')
            ->orderBy('total_review', 'desc');
        }
        if(isset($params['sort_by_price_asc'])){
            $books->orderBy('check_final_price.final_price', 'asc');
        }
        if(isset($params['sort_by_price_desc'])){
            $books->orderBy('check_final_price.final_price', 'desc');
        }

        $items = $books->offset($offset)->limit($limit)->get();

        return [
            'items' => $items,
            'total' => $books->count(),
            'pageIndex' => $pageIndex,
            'limit' => $limit,
        ];
    }
    public function sortByRattingReview($star , Request $params)
    {
        //pagination
        $pageIndex = $params['pageIndex'] ?? self::PAGE_INDEX_DEFAULT;
        $limit = $params['limit'] ?? self::LIMIT_DEFAULT;
        $offset = ($pageIndex - 1) * $limit;

        
        $books = $this->bookModel;
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
            ->select('check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo','check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price', 'calculate.count', 'calculate.sum', 'calculate.rating');

            

        $books = $books->where('calculate.rating', '=', $star);

        $items = $books->offset($offset)->limit($limit)->get();

        return [
            'items' => $items,
            'total' => $books->count(),
            'pageIndex' => $pageIndex,
            'limit' => $limit,
        ];

    }

    public function sortByPriceDes(Request $params){
        //pagination
        $pageIndex = $params['pageIndex'] ?? self::PAGE_INDEX_DEFAULT;
        $limit = $params['limit'] ?? self::LIMIT_DEFAULT;
        $offset = ($pageIndex - 1) * $limit;        
        $query= $this->bookModel;
  
        $books = $this->bookModel
            ->joinSub($this->finalPrice(), 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo','check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price')
            ->orderBy('final_price', 'desc');
            $items = $books->offset($offset)->limit($limit)->get();

            return [
                'items' => $items,
                'total' => $books->count(),
                'pageIndex' => $pageIndex,
                'limit' => $limit,
            ];
       
    }

    public function sortByPriceAsc(Request $params){
         //pagination
        $pageIndex = $params['pageIndex'] ?? self::PAGE_INDEX_DEFAULT;
        $limit = $params['limit'] ?? self::LIMIT_DEFAULT;
        $offset = ($pageIndex - 1) * $limit;
        $books = $this->bookModel
            ->joinSub($this->finalPrice(), 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo','check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price')
            ->orderBy('final_price', 'asc'); 
            $items = $books->offset($offset)->limit($limit)->get();

            return [
                'items' => $items,
                'total' => $books->count(),
                'pageIndex' => $pageIndex,
                'limit' => $limit,
            ];
    }

}
