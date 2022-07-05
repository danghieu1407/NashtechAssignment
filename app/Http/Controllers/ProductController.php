<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private BookRepository $bookRepository;
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
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

}
