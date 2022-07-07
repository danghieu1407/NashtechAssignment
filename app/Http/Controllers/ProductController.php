<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use App\Repositories\ReviewRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private BookRepository $bookRepository;
    public function __construct(BookRepository $bookRepository, ReviewRepository $reviewRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->reviewRepository = $reviewRepository;
    }
  
    public function getAll()
    {
        $books = $this->bookRepository->getAll();
        return response()->json($books);
    }

    public function getBookByIDCustomerReview(Request $params){
        $books = $this->bookRepository->getBookByIDCustomerReview($params);
        return $books;
    }

    public function getBookReviewByID(Request $params){
        $books = $this->bookRepository->getBookReviewByID($params);
        return $books;
    }

    public function countReviewStar(Request $params){
        $books = $this->bookRepository->countReviewStar($params);
        return $books;
    }

    public function createReview(Request $params){
        $review = $this->reviewRepository->createReview($params);
        return $review;
    }

}
