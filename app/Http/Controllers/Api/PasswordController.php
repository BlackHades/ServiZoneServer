<?php

namespace App\Http\Controllers\Api;

use App\Helper\Verification;
use App\Helper\WebConstant;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\Repository\UserRepository;
use App\ServiceVerification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Snowfire\Beautymail\Beautymail;

//use Snowfire\Beautymail\Beautymail;

class PasswordController extends Controller
{
    private  $users;
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    function change(Request $request, User $user){
        $val = Validator::make($request->all(),[
            'current_password' => 'required',
            'new_password' => 'required',
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

    function changeNoToken(Request $request, User $user){
        $val = Validator::make($request->all(),[
            'new_password' => 'required',
        ]);
        if($val->fails())
            return response()->json(Utility::returnError(implode("\n", $val->errors()->all()), implode("\n", $val->errors()->all())));

        if(isset($user) || !empty($user)){
            $user->password = bcrypt($request->new_password);
            $user->save();
            return response()->json(Utility::returnSuccess("Password Successfully Changed"));
        }
        return response()->json(Utility::returnError("Validation Error", "User not founds"));

    }

    function forgot(Request $request){
        //Log::info('here',[]);
        $val = Validator::make($request->all(),[
            'email' => 'required|exists:users,email'
        ],[
            'email.required' => 'Email Address is required',
            'email.exists' => 'User with email not found'
        ]);
        if($val->fails())
            return response()->json(Utility::returnError(implode("\n", $val->errors()->all()), implode("\n", $val->errors()->all())));

        $val = (new Verification())->tokenize($request->email);
        try{
            $this->sendMail($this->users->getByEmail($request->email), $val);
        }
        catch (\Exception $exception){
            return response()->json($exception);
        }
        return response()->json(Utility::returnSuccess("A Verification Code Has been sent to your email", $val));

    }

    private function sendMail(User $user, ServiceVerification $verify){
        $data = [];
        $data['name'] = $user->name;
        $data['code'] = $verify->code;
        Log::info("Mailer", [$data]);
        $beautymail = app()->make(Beautymail::class);
        $beautymail->send('emails.code', $data, function($message) use($user)
        {
            $message
                ->from(WebConstant::$DO_NOT_REPLY_MAIL)
                ->to($user->email, explode(' ', $user->name)[0])
                ->subject('Verification');
        });
        Log::info("Sent", [$data]);

    }
}
