<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    function create(Request $request, User $user){
        $val = Validator::make($request->all(),[
            'subject' => 'required',
            'message' => 'required'
        ]);
        if($val->fails())
            return Utility::returnError("Validation Error",implode("\n",$val->errors()->all()));
        if(!empty($user) && isset($user)){
            $con =  new Contact();
            $con->user_id = $user->id;
            $con->subject = $request->subject;
            $con->message = $request->message;
            if($con->save())
                return Utility::returnSuccess("Message Received, We will get back to you shortly",['user' => $user, 'data' => $request->all()]);

            //Send Mail to admin and auto reply to user
            else
                return Utility::returnError("Message Could not be sent");
        }
        return Utility::returnError("User Not Found",['user' => $user, 'data' => $request->all()]);

    }
}
