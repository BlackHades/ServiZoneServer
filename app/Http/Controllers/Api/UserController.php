<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Utility;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function edit(User $user, Request $request) {
//        $user = User::find($request->user_id);
        $val = Validator::make($request->all(),[
            'name' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'mobile' => 'required',
        ],[
            'name.required' => 'Fullname is required',
            'dob.required' => 'Date of Birth is required',
            'gender.required' => 'Gender is required',
            'mobile.required' => 'Mobile Number is Required',
        ]);

        if($val->fails()){
            return response()->json(Utility::returnError("Validation Error", implode(",\n", $val->errors()->all())));
        }
        $user->name = $request->name;
        $user->age = $request->dob;
        $user->gender = $request->gender;
        $user->mobile = $request->mobile;
        $user->save();
        $user->token = $request->token;
        return response()->json(Utility::returnSuccess("Data Update Successfully", $user));
    }

    public function uploadAvatar(User $user, Request $request){

        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $filename = time() . "@$user->email-avatar." .$file->getClientOriginalExtension();
            $file->move(public_path("uploads/"), $filename);
            $user->avatar = $filename;
            $user->save();
            return Utility::returnSuccess("Image Uploaded Successfully",$filename);
        }
        return Utility::returnError("Image Not Found");

    }
}
