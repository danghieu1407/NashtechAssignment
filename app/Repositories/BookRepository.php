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
                'check_final_price.book_title',
                'check_final_price.book_price',
                'check_final_price.book_cover_photo',
                'check_final_price.discount_price',
                'check_final_price.discount_start_date',
                'check_final_price.discount_end_date',
                'author.author_name',
                'check_final_price.final_price'
            )
            ->selectRaw('book.book_price - check_final_price.discount_price as subprice')
            ->where('check_final_price.discount_price', '!=', null)
            ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
            ->orderBy('subprice', 'desc')
            ->limit(10)
            ->get();

        return response()->json($books);
    }



    public function calculateRating()
    {
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

            ->orderBy('rating', 'desc');

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
                'check_final_price.id',
                'check_final_price.book_price',
                'check_final_price.book_cover_photo',
                'check_final_price.discount_price',
                'check_final_price.discount_start_date',
                'check_final_price.discount_end_date',
                'author.author_name',
                'check_final_price.final_price',
                'calculate.count',
                'calculate.sum',
                'calculate.rating'
            )
            ->limit(8)
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
            ->select('check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo', 'check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price')
            ->selectRaw('book.*,count(review.id) as total_review')
            ->groupBy('book.id', 'check_final_price.id', 'check_final_price.book_price', 'check_final_price.book_cover_photo', 'check_final_price.discount_price', 'check_final_price.discount_start_date', 'check_final_price.discount_end_date', 'author.author_name', 'check_final_price.final_price')
            ->orderBy('total_review', 'desc')
            ->orderBy('final_price', 'asc')

            ->limit(8)
            ->get();
        return  $books;
    }

    public function filterByCategoryName_Author_RatingReview(Request $params)
    {
        $per_page = request()->per_page ?? self::LIMIT_DEFAULT;
        $finalPrice = $this->finalPrice();
        $ratingStar = $this->calculateRating();

        if (!isset($params['category_name']) && (!isset($params['author_name']) && (!isset($params['rating_star'])))) {
            $books = $this->bookModel
                ->joinSub($finalPrice, 'check_final_price', function ($join) {
                    $join->on('book.id', '=', 'check_final_price.id');
                })
                ->join('author', 'book.author_id', '=', 'author.id')
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
                )
                ->selectRaw('book.book_price - check_final_price.discount_price as subprice')
                ->where('check_final_price.discount_price', '!=', null)
                ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                ->orderBy('subprice', 'desc')
                ->paginate($per_page);
            return $books;
        }
        else if ((isset($params['author_name']) && !isset($params['category_name']) && (!isset($params['rating_star']) ))){
            $books = $this->bookModel
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('book.*', 'author.author_name')
            ->where('author.author_name', '=', $params['author_name'])
            ->joinSub($this->finalPrice(), 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->joinSub($ratingStar, 'calculate', function ($join) {
                $join->on('book.id', '=', 'calculate.id');
            })
            ->select(
            'check_final_price.id', 
            'check_final_price.book_price', 
            'check_final_price.book_cover_photo',
            'check_final_price.discount_price', 
            'check_final_price.discount_start_date', 
            'check_final_price.discount_end_date', 
            'author.author_name', 
            'check_final_price.final_price')
            ->paginate($per_page);
            return $books;
        } 
        else if ((isset($params['author_name']) && isset($params['category_name']) && (!isset($params['rating_star']) ))){
            $books = $this->bookModel
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select('book.*', 'author.author_name')
            ->where('author.author_name', '=', $params['author_name'])
            ->joinSub($this->finalPrice(), 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->joinSub($ratingStar, 'calculate', function ($join) {
                $join->on('book.id', '=', 'calculate.id');
            })
            ->select(
            'check_final_price.id', 
            'check_final_price.book_price', 
            'check_final_price.book_cover_photo',
            'check_final_price.discount_price', 
            'check_final_price.discount_start_date', 
            'check_final_price.discount_end_date', 
            'author.author_name', 
            'check_final_price.final_price')
            ->paginate($per_page);
            return $books;
        }
        else if ((!isset($params['author_name']) && !isset($params['category_name']) && (isset($params['rating_star']) ))){
        $finalPrice = $this->finalPrice();
        $ratingStar = $this->calculateRating();
        $books = $this->bookModel
            ->where('calculate.rating', '=', $params['rating_star'])
            ->joinSub($finalPrice, 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            })
            ->joinSub($ratingStar, 'calculate', function ($join) {
                $join->on('book.id', '=', 'calculate.id');
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
                'check_final_price.final_price', 
                'calculate.count', 
                'calculate.sum', 
                'calculate.rating')
            ->paginate($per_page);
            return $books;
            }
        else {
           
            $books = $this->bookModel
                ->join('category', 'category.id', '=', 'book.category_id')
                ->join('author', 'book.author_id', '=', 'author.id')
                ->select('book.*', 'category_name')
                ->where('category_name', '=', $params['category_name']);
            if (isset($params['author_name'])) {
                $books->where('author_name', '=', $params['author_name']);
            }
            if (isset($params['rating_star'])) {
                $books->where('calculate.rating', '=', $params['rating_star'])
                    ->joinSub($ratingStar, 'calculate', function ($join) {
                        $join->on('book.id', '=', 'calculate.id');
                    });
            }

            $books->joinSub($this->finalPrice(), 'check_final_price', function ($join) {
                $join->on('book.id', '=', 'check_final_price.id');
            });
            if (isset($params['rating_star'])) {
                $books->select(
                    'check_final_price.id',
                    'check_final_price.book_price',
                    'check_final_price.book_cover_photo',
                    'check_final_price.discount_price',
                    'check_final_price.discount_start_date',
                    'check_final_price.discount_end_date',
                    'author.author_name',
                    'check_final_price.final_price',
                    'calculate.count',
                    'calculate.sum',
                    'calculate.rating'
                );
            } else {
                $books->select(
                    'check_final_price.id',
                    'check_final_price.book_price',
                    'check_final_price.book_cover_photo',
                    'check_final_price.discount_price',
                    'check_final_price.discount_start_date',
                    'check_final_price.discount_end_date',
                    'author.author_name',
                    'check_final_price.final_price',
                    'category_name',
                    'author.author_name',
                    'book.book_title',
                );
            }

            if (isset($params['sort_by_on_sale'])) {
                $books
                    ->where('check_final_price.discount_price', '!=', null)
                    ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                    ->orderBy('check_final_price.final_price', 'asc')
                    ->orderBy('check_final_price.discount_price', 'desc');
            }
            if (isset($params['sort_by_popularity'])) {
                $books
                    ->join('review', 'review.book_id', '=', 'book.id')
         
                    ->selectRaw('book.id,count(review.id) as total_review')
                    ->groupBy(
                        'book.id',
                        'check_final_price.id',
                        'check_final_price.book_price',
                        'check_final_price.book_cover_photo',
                        'check_final_price.discount_price',
                        'check_final_price.discount_start_date',
                        'check_final_price.discount_end_date',
                        'author.author_name',
                        'check_final_price.final_price','category_name',
                        'calculate.count',
                        'calculate.sum',
                        'calculate.rating'
                    )
                    ->orderBy('check_final_price.final_price', 'asc')
                    ->orderBy('total_review', 'desc');
            }
            if (isset($params['sort_by_price_asc'])) {
                $books->orderBy('check_final_price.final_price', 'asc');
            }
            if (isset($params['sort_by_price_desc'])) {
                $books->orderBy('check_final_price.final_price', 'desc');
            }
            return $books->paginate($per_page);
        }
    }
}
