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

    public function filterByCategoryName( Request $params)
    {
        $books = $this->bookRepository->filterByCategoryName($params);
        return $books;
    }
    public function filterByAuthor( Request $params){
        $books = $this->bookRepository->filterByAuthor($params);
        return $books;
    }

    public function sortByRattingReview($star, Request $params){
        $books = $this->bookRepository->sortByRattingReview($star, $params);
        return $books;
    }
    
    public function sortByPriceDes(Request $params){
        $books = $this->bookRepository->sortByPriceDes($params);
        return $books;
    }

    public function sortByPriceAsc(Request $params){
        $books = $this->bookRepository->sortByPriceAsc($params);
        return $books;
    }

}
