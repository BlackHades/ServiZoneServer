<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\Review;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function getByServiceId(Request $request)
    {
        $service_id = $request->service;
        $rating = Review::with('user', 'service')->where('service_id', $service_id)
            ->orderBy('id', 'DESC')->get();

        return response()->json(Utility::returnSuccess("Success", $rating));
    }

    public function add(Request $request, User $user)
    {
        Log::info("User", [json_encode($user)]);
        $val = Validator::make($request->all(), [
            "service" => 'required|exists:services,id',
            'rating' => 'required',
            'message' => 'required',
        ], [
            'service.required' => "Service Id is required",
            'service.exists' => "Service does not exist",
        ]);

        if ($val->fails())
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));


        $service = Service::find($request->service);
        if ($user->id == $service->user_id)
            return response()->json(Utility::returnError("You can't make a review on this service"));

        $review = new Review();
        $review->rating = $request->rating;
        $review->user_id = $user->id;
        $review->message = $request->message;
        $review->service_id = $request->service;

        if ($review->save())
            return response()->json(Utility::returnSuccess("Thank you for your review"));
        return response()->json(Utility::returnError("Unable to create review at this moment"));
    }
}
