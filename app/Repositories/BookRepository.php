<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Book;
use App\Models\Discount;
use DB;
use Illuminate\Http\Request;


class BookRepository implements BaseInterface
{
    //pagination
    const LIMIT_DEFAULT = 5;
    const PAGE_INDEX_DEFAULT = 1;
    protected  Book $bookModel;



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

        $books = $this->bookModel->finalPriceOfBook()
            ->join('author', 'book.author_id', '=', 'author.id')
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
        $calculate = $this->bookModel
        ->join('review', 'book.id', '=', 'review.book_id')
        ->selectRaw('book.id, round(count(review.id),1) as count, round(sum(review.rating_star),1) as sum')
        ->groupBy('book.id');
        $result = $this->bookModel
        ->joinSub($calculate, 'calculate', function ($join) {
            $join->on('book.id', '=', 'calculate.id');
        })
        ->select('book.*', 'calculate.count', 'calculate.sum')
        ->selectRaw('round(calculate.sum/calculate.count,1) as rating')
        ->orderBy('rating', 'desc');
        return  $result;    
    }
    public function getTheMostRatingStartsBooks()
    {
        $ratingStar = $this->calculateRating();
        $books = $this->bookModel->finalPriceOfBook()
            ->joinSub($ratingStar, 'calculate', function ($join) {
                $join->on('book.id', '=', 'calculate.id');
            })
            ->join('author', 'book.author_id', '=', 'author.id')
            ->select(
                'check_final_price.id',
                'check_final_price.id',
                'check_final_price.book_title',
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
        $books = $this->bookModel->finalPriceOfBook()
            ->join('review', 'review.book_id', '=', 'book.id')
            ->join('author', 'book.author_id', '=', 'author.id')
            ->selectRaw('book.*,count(review.id) as total_review')
            ->groupBy(
                'book.id',
                'check_final_price.id',
                'check_final_price.book_price', 
                'check_final_price.book_cover_photo', 
                'check_final_price.discount_price', 
                'check_final_price.discount_start_date',
                'check_final_price.discount_end_date', 
                'author.author_name', 
                'check_final_price.final_price', 
                'check_final_price.book_title'
                )
            ->orderBy('total_review', 'desc')
            ->orderBy('final_price', 'asc')
            ->limit(8)
            ->get();
        return  $books;
    }

    public function filterByCategoryName_Author_RatingReview(Request $params)
    {
        $per_page = request()->per_page ?? self::LIMIT_DEFAULT;
        $ratingStar = $this->calculateRating();
        //sort when it doesn't have prams
        if (!isset($params['category_name']) && (!isset($params['author_name']) && (!isset($params['rating_star'])))) {
            $books = $this->bookModel->finalPriceOfBook()
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
                ->selectRaw('book.book_price - check_final_price.discount_price as subprice');
            if (isset($params['sort_by_popularity'])) {
                $books
                    ->join('review', 'review.book_id', '=', 'book.id')
                    ->joinSub($ratingStar, 'calculate', function ($join) {
                        $join->on('book.id', '=', 'calculate.id');
                    })
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
                        'check_final_price.final_price',
                        'check_final_price.book_title',
                        'calculate.count',
                        'calculate.sum',
                        'calculate.rating'
                    )
                    ->orderBy('total_review', 'desc')
                    ->orderBy('check_final_price.final_price', 'asc');
            } else if (isset($params['sort_by_price_desc'])) {
                $books->orderBy('check_final_price.final_price', 'desc');
            } else if (isset($params['sort_by_price_asc'])) {
                $books->orderBy('check_final_price.final_price', 'asc');
            } else {
                $books
                    ->where('check_final_price.discount_price', '!=', null)
                    ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                    ->orderBy('check_final_price.final_price', 'asc')
                    ->orderBy('check_final_price.discount_price', 'desc');
            }

            return $books->paginate($per_page);
        }
        //sort when it has param category_name
        else if ((!isset($params['author_name']) && isset($params['category_name']) && (!isset($params['rating_star'])))) {
            $books = $this->bookModel->finalPriceOfBook()
                ->join('author', 'book.author_id', '=', 'author.id')
                ->join('category', 'book.category_id', '=', 'category.id')
                ->select('book.*', 'author.author_name')
                ->where('category.category_name', '=', $params['category_name'])
                ->joinSub($ratingStar, 'calculate', function ($join) {
                    $join->on('book.id', '=', 'calculate.id');
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
                            'check_final_price.final_price',
                            'check_final_price.book_title',
                            'category.category_name',
                            'calculate.count',
                            'calculate.sum',
                            'calculate.rating'
                        )
                        ->orderBy('total_review', 'desc')
                        ->orderBy('check_final_price.final_price', 'asc');
                } else if (isset($params['sort_by_price_desc'])) {
                    $books->orderBy('check_final_price.final_price', 'desc');
                } else if (isset($params['sort_by_price_asc'])) {
                    $books->orderBy('check_final_price.final_price', 'asc');
                } else {
                    $books
                        ->where('check_final_price.discount_price', '!=', null)
                        ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                        ->orderBy('check_final_price.final_price', 'asc')
                        ->orderBy('check_final_price.discount_price', 'desc');
                }
            return $books->paginate($per_page);
        }
        //sort when it has params author_name
        else if ((isset($params['author_name']) && !isset($params['category_name']) && (!isset($params['rating_star'])))) {
            $books = $this->bookModel->finalPriceOfBook()
                ->join('author', 'book.author_id', '=', 'author.id')
                ->select('book.*', 'author.author_name')
                ->where('author.author_name', '=', $params['author_name'])
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
                    'check_final_price.final_price'
                );
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
                            'check_final_price.final_price',
                            'check_final_price.book_title',
                            'calculate.count',
                            'calculate.sum',
                            'calculate.rating'
                        )
                        ->orderBy('total_review', 'desc')
                        ->orderBy('check_final_price.final_price', 'asc');
                } else if (isset($params['sort_by_price_desc'])) {
                    $books->orderBy('check_final_price.final_price', 'desc');
                } else if (isset($params['sort_by_price_asc'])) {
                    $books->orderBy('check_final_price.final_price', 'asc');
                } else {
                    $books
                        ->where('check_final_price.discount_price', '!=', null)
                        ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                        ->orderBy('check_final_price.final_price', 'asc')
                        ->orderBy('check_final_price.discount_price', 'desc');
                }
            return $books->paginate($per_page);
        }
        //sort when it has params author_name && category_name
        else if ((isset($params['author_name']) && isset($params['category_name']) && (!isset($params['rating_star'])))) {
            $books = $this->bookModel->finalPriceOfBook()
                ->join('author', 'book.author_id', '=', 'author.id')
                ->join('category', 'book.category_id', '=', 'category.id')
                ->select('book.*', 'author.author_name')
                ->where('author.author_name', '=', $params['author_name'])
                ->where('category.category_name', '=', $params['category_name'])

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
                    'check_final_price.final_price',
                    'category.category_name'
                );
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
                            'check_final_price.final_price',
                            'check_final_price.book_title',
                            'category.category_name',
                            'calculate.count',
                            'calculate.sum',
                            'calculate.rating'
                        )
                        ->orderBy('total_review', 'desc')
                        ->orderBy('check_final_price.final_price', 'asc');
                } else if (isset($params['sort_by_price_desc'])) {
                    $books->orderBy('check_final_price.final_price', 'desc');
                } else if (isset($params['sort_by_price_asc'])) {
                    $books->orderBy('check_final_price.final_price', 'asc');
                } else {
                    $books
                        ->where('check_final_price.discount_price', '!=', null)
                        ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                        ->orderBy('check_final_price.final_price', 'asc')
                        ->orderBy('check_final_price.discount_price', 'desc');
                }
            return $books->paginate($per_page);
        }
        //sort when it has params rating_star
        else if ((!isset($params['author_name']) && !isset($params['category_name']) && (isset($params['rating_star'])))) {
            $rating_star = $params['rating_star'];
            $ratingStar = $this->calculateRating();
            $books = $this->bookModel->finalPriceOfBook()
                ->join('category', 'book.category_id', '=', 'category.id')
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
                );

                if ($rating_star == 1) {
                    $books->whereBetween('calculate.rating', [0, 1]);
                } else if ($rating_star == 2) {
                    $books->whereBetween('calculate.rating', [1, 2]);
                } else if ($rating_star == 3) {
                    $books->whereBetween('calculate.rating', [2, 3]);
                } else if ($rating_star == 4) {
                    $books->whereBetween('calculate.rating', [3, 4]);
                } else if ($rating_star == 5) {
                    $books->whereBetween('calculate.rating', [4, 5]);
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
                            'check_final_price.final_price',
                            'check_final_price.book_title',
                            'category.category_name',
                            'calculate.count',
                            'calculate.sum',
                            'calculate.rating'
                        )
                        ->orderBy('total_review', 'desc')
                        ->orderBy('check_final_price.final_price', 'asc');
                } else if (isset($params['sort_by_price_desc'])) {
                    $books->orderBy('check_final_price.final_price', 'desc');
                } else if (isset($params['sort_by_price_asc'])) {
                    $books->orderBy('check_final_price.final_price', 'asc');
                } else {
                    $books
                        ->where('check_final_price.discount_price', '!=', null)
                        ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                        ->orderBy('check_final_price.final_price', 'asc')
                        ->orderBy('check_final_price.discount_price', 'desc');
                }
            return $books->paginate($per_page);
        }
        //sort when it has params author_name and rating_star
        else if (!isset($params['category_name']) && (isset($params['author_name']) && (isset($params['rating_star'])))) {
            $rating_star = $params['rating_star'];
            $ratingStar = $this->calculateRating();
            $books = $this->bookModel->finalPriceOfBook()
                ->joinSub($ratingStar, 'calculate', function ($join) {
                    $join->on('book.id', '=', 'calculate.id');
                })
                ->join('author', 'book.author_id', '=', 'author.id')
                ->join('category', 'book.category_id', '=', 'category.id')
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
                ->where('author.author_name', '=', $params['author_name']);
                if ($rating_star == 1) {
                    $books->whereBetween('calculate.rating', [0, 1]);
                } else if ($rating_star == 2) {
                    $books->whereBetween('calculate.rating', [1, 2]);
                } else if ($rating_star == 3) {
                    $books->whereBetween('calculate.rating', [2, 3]);
                } else if ($rating_star == 4) {
                    $books->whereBetween('calculate.rating', [3, 4]);
                } else if ($rating_star == 5) {
                    $books->whereBetween('calculate.rating', [4, 5]);
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
                            'check_final_price.final_price',
                            'check_final_price.book_title',
                            'category.category_name',
                            'calculate.count',
                            'calculate.sum',
                            'calculate.rating'
                        )
                        ->orderBy('total_review', 'desc')
                        ->orderBy('check_final_price.final_price', 'asc');
                } else if (isset($params['sort_by_price_desc'])) {
                    $books->orderBy('check_final_price.final_price', 'desc');
                } else if (isset($params['sort_by_price_asc'])) {
                    $books->orderBy('check_final_price.final_price', 'asc');
                } else {
                    $books
                        ->where('check_final_price.discount_price', '!=', null)
                        ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                        ->orderBy('check_final_price.final_price', 'asc')
                        ->orderBy('check_final_price.discount_price', 'desc');
                }
            return $books->paginate($per_page);
        } 
        else {
            $books = $this->bookModel->finalPriceOfBook()
                ->join('category', 'category.id', '=', 'book.category_id')
                ->join('author', 'book.author_id', '=', 'author.id')
                ->select('book.*', 'category_name')
                ->where('category_name', '=', $params['category_name']);
                if (isset($params['category_name']) && !isset($params['rating_star'])) {
                    $books->where('calculate.rating', '=', $params['rating_star'])
                        ->joinSub($ratingStar, 'calculate', function ($join) {
                            $join->on('book.id', '=', 'calculate.id');
                        });
                }
                if (isset($params['author_name'])) {
                    $books->where('author_name', '=', $params['author_name']);
                }
                if (isset($params['rating_star'])) {
                    $books->where('calculate.rating', '=', $params['rating_star'])
                        ->joinSub($ratingStar, 'calculate', function ($join) {
                            $join->on('book.id', '=', 'calculate.id');
                        });
                }


                if (isset($params['rating_star'])) {
                    $rating_star = $params['rating_star'];
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
                        'calculate.rating',
                        'category.category_name'
                    );

                    if ($rating_star == 1) {
                        $books->whereBetween('calculate.rating', [0, 1]);
                    } else if ($rating_star == 2) {
                        $books->whereBetween('calculate.rating', [1, 2]);
                    } else if ($rating_star == 3) {
                        $books->whereBetween('calculate.rating', [2, 3]);
                    } else if ($rating_star == 4) {
                        $books->whereBetween('calculate.rating', [3, 4]);
                    } else if ($rating_star == 5) {
                        $books->whereBetween('calculate.rating', [4, 5]);
                    }
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
                            'check_final_price.final_price',
                            'check_final_price.book_title',
                            'category.category_name',
                            'calculate.count',
                            'calculate.sum',
                            'calculate.rating'
                        )
                        ->orderBy('total_review', 'desc')
                        ->orderBy('check_final_price.final_price', 'asc');
                } else if (isset($params['sort_by_price_desc'])) {
                    $books->orderBy('check_final_price.final_price', 'desc');
                } else if (isset($params['sort_by_price_asc'])) {
                    $books->orderBy('check_final_price.final_price', 'asc');
                } else {
                    $books
                        ->where('check_final_price.discount_price', '!=', null)
                        ->whereRaw('check_final_price.discount_price = check_final_price.final_price')
                        ->orderBy('check_final_price.final_price', 'asc')
                        ->orderBy('check_final_price.discount_price', 'desc');
                }
            return $books->paginate($per_page);
        }
    }

    public function  getBookByIDCustomerReview(Request $params)
    {

        $books = $this->bookModel->finalPriceOfBook()
            ->join('category', 'category.id', '=', 'book.category_id')
            ->join('author', 'book.author_id', '=', 'author.id')
            ->where('book.id', '=', $params['id'])
            ->select(
                'book.id', 
                'category.category_name', 
                'book.book_title',
                'author.author_name',
                'check_final_price.book_cover_photo',
                'check_final_price.book_price',
                'check_final_price.discount_price',
                'check_final_price.discount_start_date',
                'check_final_price.discount_end_date',
                'check_final_price.final_price',
                'book.book_summary')
            ->get();
        return $books;
    }

    public function getBookReviewByID(Request $params)
    {
        $per_page = request()->per_page ?? self::LIMIT_DEFAULT;
        $ratingStar  = $this->calculateRating();
        $books = $this->bookModel
            ->join('review', 'review.book_id', '=', 'book.id')
            ->joinSub($ratingStar, 'calculate', function ($join) {
                        $join->on('book.id', '=', 'calculate.id');
                    })
            ->where('book.id', '=', $params['id'])
            ->select('book.id')
            ->selectRaw(
                'book.id,
                review.review_title ,
                review.review_details,
                review.rating_star,
                review.review_date'
            )
            ->groupBy(
                'book.id',
                'review.review_title',
                'review.review_details',
                'review.rating_star',
                'review.review_date'
            );
        
            if(isset($params['one_star'])){
                $books->where('review.rating_star','=',1);
            }
            if(isset($params['two_star'])){
                $books->where('review.rating_star','=',2);
            }
            if(isset($params['three_star'])){
                $books->where('review.rating_star','=',3);
            }
            if(isset($params['four_star'])){
                $books->where('review.rating_star','=',4);
            }
            if(isset($params['five_star'])){
                $books->where('review.rating_star', '=', 5 );
            }
            if(isset($params['total'])){
                $books->get();
            }
            

            if (!isset($params['sort_by_date_desc']) && !isset($params['sort_by_date_asc'])) {
                $books->orderBy('review.review_date', 'desc');
            }
            if (isset($params['sort_by_date_desc'])) {
                $books->orderBy('review.review_date', 'desc');
            }
            if (isset($params['sort_by_date_asc'])) {
                $books->orderBy('review.review_date', 'asc');
            }

        return $books->paginate($per_page);
    }

    public function countReviewStar(Request $params){
        $ratingStar  = $this->calculateRating();
        $books = $this->bookModel->finalPriceOfBook()
            ->join('author', 'book.author_id', '=', 'author.id')
            ->join('review', 'review.book_id', '=', 'book.id')
            ->joinSub($ratingStar, 'calculate', function ($join) {
                $join->on('book.id', '=', 'calculate.id');
            })
            ->where('book.id', '=', $params['id'])
            ->select(
                'book.id',
                'calculate.rating',
            )
             ->withCount([
                'review AS 1_Star' => function (Builder $query) {
                    $query->where('rating_star', 1);
                },
                'review AS 2_Star' => function (Builder $query) {
                    $query->where('rating_star', 2);
                },
                'review AS 3_Star' => function (Builder $query) {
                    $query->where('rating_star', 3);
                },
                'review AS 4_Star' => function (Builder $query) {
                    $query->where('rating_star', 4);
                },
                'review AS 5_Star' => function (Builder $query) {
                    $query->where('rating_star', 5);
                },
                'review AS count_review',
            ])
            ->groupBy( 
                'book.id',
                'calculate.rating',
            )
            ->get();
        return $books ;
    }
}
