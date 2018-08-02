<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\Controller;

class UserController extends Controller {

    public function block($id) {
        $user = User::where('id', $id)
                ->first()
                ->block();
        return redirect()->back();
    }

    public function unblock($id) {
        $user = User::where('id', $id)
                ->first()
                ->block(false);
        return redirect()->back();
    }

    public function edit(Request $request) {
        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->age = $request->age;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->type = $request->type;
        $user->about = $request->about;
        $user->mobile = $request->mobile;
        $user->profession_id = $request->profession_id;
        $avatar = $request->input("avatar");

        if ($avatar != null) {
            $file = base64_decode($avatar);
            $safeName = $user->name . "-" . time() . '.' . 'png';
            $destinationPath = storage_path() . "/finco-data/users/";
            file_put_contents($destinationPath . $safeName, $file);
            $user->avatar = "/finco-data/users/" . $safeName;
        }

        $user->save();
        return response()->json($user);
    }
}
