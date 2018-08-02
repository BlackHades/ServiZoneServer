<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profession;
use App\Review;

class ReviewController extends Controller {

    public function index(Request $request) {
        $expert_id = $request->expert_id;
        $rating = Review::where('expert_id', $expert_id)
                ->with('user')
                ->paginate(40);
        
        return response()->json($rating);
    }

    public function add(Request $request) {
        $user_id = $request->user_id;
        $rating = $request->rating;
        $message = $request->message;
        $expert_id = $request->expert_id;
        
        $review = new Review();
        $review->rating = $rating;
        $review->user_id = $user_id;
        $review->message = $message;
        $review->expert_id = $expert_id;
        $review->save();
        
        return response()->json($review);
    }

}
