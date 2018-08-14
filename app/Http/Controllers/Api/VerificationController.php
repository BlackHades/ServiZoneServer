<?php

namespace App\Http\Controllers\Api;

use App\Helper\Verification;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\Repository\VerifyRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    private $ver;
    public function __construct(VerifyRepository $verifyRepository)
    {
        $this->ver = $verifyRepository;
    }

    function token(Request $request){
        $val = Validator::make($request->all(),[
           'email' => 'required|exists:users,email',
           'code' => 'required'
        ],['email.exists' => 'User with Email Not Found']);

        if($val->fails())
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));

        $temp = $this->ver->getByEmailAndToken($request->email, $request->code);
        Log::info('here');
        if(isset($temp)){
            $user  = User::where('email', $request->email)->first();
            $token = (new Verification())->generateSessionToken($user->id);
            return response()->json(Utility::returnSuccess($token));
        }
        return response()->json(Utility::returnError("Invalid Data", "User Match Could Not Be Found"));
    }
}
