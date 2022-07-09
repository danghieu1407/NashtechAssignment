<?php

namespace App\Repositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


use App\Models\Review;

class ReviewRepository implements BaseInterface
{

    public function __construct(Review $reviewModal)
    {
        $this->reviewModal = $reviewModal;
    }
    public function getAll()
    {
        return Review::all()
        ->groupBy('rating_star');
    }
    
    public function getById($id)
    {
        return Review::find($id);
    }
    
    public function create($data)
    {
        return Review::create($data);
    }
    
    public function update($id, $data)
    {
        $review = Review::find($id);
        $review->rating = $data['rating'];
        $review->review = $data['review'];
        $review->save();
        return $review;
    }
    
    public function delete($id)
    {
        $review = Review::find($id);
        $review->delete();
        return $review;
    }

    public function getRatingReview(){
        $reviews = $this->reviewModal
        ->selectRaw('rating_star')
        ->groupBy('rating_star')
        ->orderBy('rating_star', 'asc')
        ->get();
        return $reviews;
        
        
    }

    public function createReview(Request $request){
        $message = [
            'review_title.required' => 'Please input title',
            'rating_star.required' => 'Please input review star',
            'review_title.max' => 'Title must be less than 120 characters',
           
        ];
        $validate = Validator::make($request->all(), 
            [
                'review_title' => 'required',
                'rating_star' => 'required',
                'review_title' => 'max:120',
            ], $message);
        if($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }
        $currentTime = Carbon::now()->toDateTimeString();
        $review = new Review();
        $review->book_id = $request->book_id;
        $review->review_title = $request->review_title;
        $review->review_details = $request->review_details;
        $review->rating_star = $request->rating_star;
        $review->review_date = $currentTime ;
        $review->save();

        return response()->json(
            ['message' => 'Create Review Success ! ',
            'review' => $review,
            ], 200);

    }
}