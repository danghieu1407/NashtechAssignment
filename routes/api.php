<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//home controller
Route::get('/books', 'HomeController@getTheMostDiscountBooks');
Route::get('/books/{id}', 'HomeController@getById');
Route::get('/getAll', 'HomeController@getAll');
Route::get('/getTheMostRatingStartsBooks','HomeController@getTheMostRatingStartsBooks');
Route::get('/getTheMostReviewBooks','HomeController@getTheMostReviewBooks');
Route::get('/checkDiscount','HomeController@checkDiscount');




//shop Controller
Route::get('/filterBy', 'ShopController@filterByCategoryName_Author_RatingReview');
Route::get('/sortByPriceDes', 'ShopController@sortByPriceDes');
Route::get('/sortByPriceAsc', 'ShopController@sortByPriceAsc');
Route::get('/getAllCategoryName', 'ShopController@getAllCategoryName');
Route::get('/getAllAuthorName', 'ShopController@getAllAuthorName');
Route::get('/getRatingReview', 'ShopController@getRatingReview');

//Product controller
Route::get('/getBookByIdToProductPage', 'ProductController@getBookByIdToProductPage');
Route::get('/getBookByID', 'ProductController@getBookByID');
Route::get('/getBookByIDCustomerReview', 'ProductController@getBookByIDCustomerReview');
Route::get('/calculateReviewRating', 'ProductController@calculateReviewRating');
Route::get('/getBookReviewByID', 'ProductController@getBookReviewByID');
Route::get('/countReviewStar', 'ProductController@countReviewStar');





