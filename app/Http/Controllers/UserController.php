<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;

class UserController extends VoyagerBreadController {

    public function block($id) {
        $user = User::find($id)->first();
        $user->is_blocked = 1;
        $user->save();
        $reported_expert = \App\ReportedUsers::where('expert_id', $id)->delete();
        $data = [
        'message' => "Successfully Blocked user",
        'alert-type' => 'success',
        ];
        return redirect()->back()->with($data);
    }

    public function unblock($id) {
        $user = User::find($id)->first();
        $user->is_blocked = 0;
        $user->save();
        $data = [
        'message' => "Successfully Unblocked user",
        'alert-type' => 'success',
        ];
        return redirect()->back()->with($data);
    }

    public function example() {
        $u = User::where('gender', '=', 'MALE')
                ->where('age', '>', 20)
                ->get();


        echo $u;
    }

}
