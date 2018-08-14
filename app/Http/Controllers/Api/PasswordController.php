<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    function change(Request $request, User $user){
        $val = Validator::make($request->all(),[
            'current_password' => 'required',
            'new_password' => 'required',
            'noToken' => 'required'
        ]);
        if($val->fails())
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));

        if(Hash::check($request->current_password, $user->password)){
            $user->password = bcrypt($request->new_password);
            $user->save();
            return response()->json(Utility::returnSuccess("Password Successfully Changed"));
        }else{
            return response()->json(Utility::returnError("Validation Error", "Current Password is incorrect"));
        }
    }
}
