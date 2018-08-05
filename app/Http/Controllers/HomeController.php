<?php

namespace App\Http\Controllers;

use App\User;
use App\Expert;
use App\ReportedUsers;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;

class HomeController extends Controller {

    public function index(Request $request) {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $closest_experts = Expert::select('users.id', 'users.name', 'age', 'avatar', 'gender', 'mobile', 'profession', 'email', 'address', 'about','longitude','latitude')
            ->withCount('reviews')
            ->join('professions', 'users.profession_id', 'professions.id')
            ->take(10)
            ->orderBy('id', 'desc')
            ->get();
        foreach ($closest_experts as $expert) {
            $expert->averageRating = $expert->reviews()->avg('rating');
        }

        $top_experts = Expert::select('users.id', 'avatar', 'users.name', 'age', 'gender', 'mobile', 'profession', 'email', 'address', 'about')
            ->withCount('reviews')
            ->join('professions', 'users.profession_id', 'professions.id')
            ->take(10)
            ->inRandomOrder()
            ->get();
        foreach ($top_experts as $expert) {
            $expert->averageRating = $expert->reviews()->avg('rating');
        }

        $featured_experts = Expert::select('users.id','avatar', 'users.name', 'age', 'gender', 'mobile', 'profession', 'email', 'address', 'about')
            ->withCount('reviews')
            ->join('professions', 'users.profession_id', 'professions.id')
            ->take(10)
            ->inRandomOrder()
            ->get();
        foreach ($featured_experts as $expert) {
            $expert->averageRating = $expert->reviews()->avg('rating');
        }

        return response()->json(compact('closest_experts', 'top_experts', 'featured_experts'));
    }

    public function all(Request $request) {
        $e = Expert::all();
//        return response()->json(compact('e'));
    }
}
