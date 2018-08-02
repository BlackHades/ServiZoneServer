<?php

namespace App\Http\Controllers;

use App\User;
use App\Expert;
use App\ReportedUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\ExpertApproved;
use Illuminate\Support\Facades\Notification;
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

    public function report(Request $request) {
        $user_id = $request->user_id;
        $message = $request->message;
        $expert_id = $request->expert_id;

        $report = new ReportedUsers();
        $report->user_id = $user_id;
        $report->message = $message;
        $report->expert_id = $expert_id;
        $report->save();

        $report->status = "success";
        return response()->json($report);
    }

    public function approve($id) {
        $user = User::where('id', $id)
                ->first();
        $password = Utility::generateRsandomChar(6);
        $encrypted = bcrypt($password);
        $user->status = "verified";
        $user->password = $encrypted;
        $user->save();
        Notification::send($user, new ExpertApproved($password));
        return redirect()->back();
    }

}
