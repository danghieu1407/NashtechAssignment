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
Route::get('/sortByCategoryName/{name}', 'ShopController@sortByCategoryName');
Route::get('/sortByAuthor/{name}/{pageIndex}/{limit}', 'ShopController@sortByAuthor');
Route::get('/sortByRattingReview/{star}', 'ShopController@sortByRattingReview');


