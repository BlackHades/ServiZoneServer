<?php

namespace App\Http\Controllers;

use App\Expert;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;

class ExpertController extends VoyagerBreadController {

    public function search(Request $request) {
        $circle_radius = 3959;
        $max_distance = 20;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $profession_id = $request->profession_id;

        $query = DB::select('SELECT * FROM
                    (SELECT users.id, users.name, users.avatar, age, gender, profession, about, address, mobile, latitude, longitude, (' . $circle_radius . ' * acos(cos(radians(' . $latitude . ')) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(' . $longitude . ')) +
                    sin(radians(' . $latitude . ')) * sin(radians(latitude))))
                    AS distance,
                    (select count(*) from `reviews` where `users`.`id` = `reviews`.`expert_id`) as `reviews_count`
                    FROM users inner join `professions` on `users`.`profession_id` = `professions`.`id` 
                    where `type` = "expert" 
                    and `is_blocked` = "0" 
                    and `users`.`profession_id` = "' . $profession_id . '") 
                    AS distances
                    ORDER BY distance');

//        echo $query;return;

        $experts = Expert::hydrate($query)->take(40);

        if (sizeof($query) > 0) {
            foreach ($experts as $expert) {
                $expert->averageRating = $expert->reviews()->avg('rating');
                $expert->about = substr(str_replace("error","mistake",$expert->about), 0, 100);
            }
            return response()->json($experts);
        } else {
            return response()->json(Utility::returnError("No expert found"));
        }
    }

    public function approve($id) {
        $ser = Service::find($id);
        $ser->verified = true;
        $ser->save();
//        Notification::send($user, new ExpertApproved($password));
        return redirect()->route('voyager.dashboard');
    }

}
