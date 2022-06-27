<?php

namespace App\Repositories;

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
}