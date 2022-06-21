<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Repositories\BookRepository;


class HomeController extends Controller
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
    public function getById($id)
    {
        $book = $this->bookRepository->getById($id);
        return response()->json($book);
    }
    public function create(Request $request)
    {
        $book = $this->bookRepository->create($request->all());
        return response()->json($book);
    }
    public function update($id, Request $request)
    {
        $book = $this->bookRepository->update($id, $request->all());
        return response()->json($book);
    }
    public function delete($id)
    {
        $book = $this->bookRepository->delete($id);
        return response()->json($book);
    }

    public function getTheMostDiscountBooks()
    {
        $books = $this->bookRepository->getTheMostDiscountBooks();
        return $books;
    
    }
    public function getTheMostReviewBooks()
    {
        $books = $this->bookRepository->getTheMostReviewBooks();
        return $books;
    }

    public function getTheMostRatingStartsBooks()
    {
        $books = $this->bookRepository->getTheMostRatingStartsBooks();
        return $books;
    }



    



}
