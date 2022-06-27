<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AuthorRepository;


use Illuminate\Http\Request;

class ShopController extends Controller
{
    private BookRepository $bookRepository;
    public function __construct(BookRepository $bookRepository, CategoryRepository $categoryRepository, AuthorRepository $authorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authorRepository = $authorRepository;
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

    public function filterByRattingReview(Request $params){
        $books = $this->bookRepository->filterByRattingReview($params);
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

    public function getAllCategoryName(){
        $category = $this->categoryRepository->getAll();
        return $category;
    }   

    public function getAllAuthorName(){
        $author = $this->authorRepository->getAll();
        return $author;
    }

}
