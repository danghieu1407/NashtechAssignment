<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    private BookRepository $bookRepository;
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function sortByCategoryName($name, Request $params)
    {
        $books = $this->bookRepository->sortByCategoryName($name, $params);
        return $books;
    }
    public function sortByAuthor($name, Request $params){
        $books = $this->bookRepository->sortByAuthor($name, $params);
        return $books;
    }

    public function sortByRattingReview($star){
        $books = $this->bookRepository->sortByRattingReview($star);
        return $books;
    }

}
