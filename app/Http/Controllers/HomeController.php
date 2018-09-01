<?php

namespace App\Http\Controllers;

use App\Expert;
use App\Service;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller {


    public function nearestService(Request $request){
        $latitude = 0.0;
        $longitude = 0.0;

        $cities = User::selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( latitude ) ) ) ) AS distance',
            [$latitude, $longitude, $latitude])
            ->orderBy('distance')
            ->take(10)
            ->get();
    }

    public function index(Request $request) {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

//        $closest_experts = Expert::select('users.id', 'users.name', 'age', 'avatar', 'gender', 'mobile', 'profession', 'email', 'address', 'about','longitude','latitude')
//            ->withCount('reviews')
//            ->join('professions', 'users.profession_id', 'professions.id')
//            ->take(10)
//            ->orderBy('id', 'desc')
//            ->get();

        //Use Lat ad Log Difference
        $closest_experts = Service::nearest($latitude, $longitude)
            ->take(15);
        if (count($closest_experts) < 15) {
            $other_close = Service::inRandomOrder()->take(15 - count($closest_experts))->get();
            $closest_experts = $closest_experts->merge($other_close);
        }


        foreach ($closest_experts as $expert) {
            $expert->profession = $expert->getProfession->profession;
            $expert->review = count($expert->reviews) == 0 ? 0 : $expert->reviews()->avg('rating');
        }



        //By Ratings
//        $top_experts = Expert::select('users.id', 'avatar', 'users.name', 'age', 'gender', 'mobile', 'profession', 'email', 'address', 'about')
//            ->withCount('reviews')
//            ->join('professions', 'users.profession_id', 'professions.id')
//            ->take(10)
//            ->inRandomOrder()
//            ->get();

        $top_experts = Service::withCount('reviews')
            ->take(15)
            ->inRandomOrder()
            ->get();

        if (count($top_experts) < 15) {
            $other_close = Service::inRandomOrder()->take(15 - count($top_experts))->get();
            $top_experts = $top_experts->merge($other_close);
        }
        foreach ($top_experts as $expert) {
            $expert->profession = $expert->getProfession->profession;
            $expert->review = count($expert->reviews) == 0 ? 0 : $expert->reviews()->avg('rating');
        }





        //By Advert
//        $featured_experts = Service::select('users.id','avatar', 'users.name', 'age', 'gender', 'mobile', 'profession', 'email', 'address', 'about')
//            ->withCount('reviews')
//            ->join('professions', 'users.profession_id', 'professions.id')
//            ->take(10)
//            ->inRandomOrder()
//            ->get();

        $featured_experts = Service::withCount('reviews')
            ->join('professions', 'services.profession_id', 'professions.id')
            ->take(15)
            ->inRandomOrder()
            ->get();

        if (count($featured_experts) < 15) {
            $other_close = Service::inRandomOrder()->take(15 - count($featured_experts))->get();
            $featured_experts = $featured_experts->merge($other_close);
        }

        foreach ($featured_experts as $expert) {
            $expert->profession = $expert->getProfession->profession;
            $expert->review = count($expert->reviews) == 0 ? 0 : $expert->reviews()->avg('rating');
        }
        return response()->json(Utility::returnSuccess("Done", compact('closest_experts', 'top_experts', 'featured_experts')));
    }

    public function all(Request $request) {
        $e = Expert::all();
//        return response()->json(compact('e'));
    }
}
