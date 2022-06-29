<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\ReviewRepository;



use Illuminate\Http\Request;

class ShopController extends Controller
{
    private BookRepository $bookRepository;
    public function __construct(BookRepository $bookRepository, CategoryRepository $categoryRepository, AuthorRepository $authorRepository, ReviewRepository $reviewRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authorRepository = $authorRepository;
        $this->reviewRepository = $reviewRepository;
    }
   

    public function filterByCategoryName_Author_RatingReview( Request $params)
    {
        $books = $this->bookRepository->filterByCategoryName_Author_RatingReview($params);
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

    public function getRatingReview(){
        $rating = $this->reviewRepository->getRatingReview();
        return $rating;
    }

}
