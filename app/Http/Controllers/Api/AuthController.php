<?php

namespace App\Http\Controllers\Api;

use App\Helper\WebConstant;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\Notifications\NewExpertAdmin;
use App\Repository\UserRepository;
use App\Tokens;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
    /*

      |--------------------------------------------------------------------------

      | Login Controller

      |--------------------------------------------------------------------------

      |

      | This controller handles authenticating users for the application and

      | redirecting them to your home screen. The controller uses a trait

      | to conveniently provide its functionality to your applications.

      |

     */

    protected $users;

    public function __construct(UserRepository $userRepository)
    {
        $this->users = $userRepository;
    }

    public function login(Request $request) {
        $username = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $username, 'password' => $password])) {
            $user = Auth::user();
            $token = $this->storeToken($user->id);
            $user->token = $token;

            if ($user->status == "pending") {
                return response(Utility::returnError("Your Servizone expert account has not been verified yet.   You'll get an Email with your login details as soon as you have been verified."));
            }

            return response()->json(Utility::returnSuccess("Logged in successfully", $user));
        } else if (Auth::attempt(['mobile' => $username, 'password' => $password])){
            $user = Auth::user();
            $token = $this->storeToken($user->id);
            $user->token = $token;

            if ($user->status == "pending") {
                return response(Utility::returnError("Your Servizone expert account has not been verified yet.   You'll get an Email with your login details as soon as you have been verified."));
            }

            return response(Utility::returnSuccess("Logged in successfully", $user));
        } else {
            return response(Utility::returnError("Invalid login details"));
        }
    }

    public function register(Request $request) {
        $val = Validator::make($request->all(),[
            'name' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'mobile' => 'required',
            'email' => 'required|unique:users,email'
        ],[
            'name.required' => 'Fullname is required',
            'dob.required' => 'Date of Birth is required',
            'gender.required' => 'Gender is required',
            'mobile.required' => 'Mobile Number is Required',
            'email.required' => "Email Address is required"
        ]);

        if($val->fails()){
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));
        }
        $user = new User();
        $user->name = $request->name;
        $user->age = $request->dob;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
//        $user->address = $request->address;
        $user->password = bcrypt($request->password);
        $user->longitude = $request->longitude;
        $user->latitude = $request->latitude;
        try {
            $user->save();
            $token = $this->storeToken($user->id);
            $user->token = $token;
        } catch (\Exception $ex){
            return response(Utility::returnError('An error occurred. Details: '.$ex->getMessage()));
        }

        /*
        |----------------------------------------------------
        | Send Notification to all Admins
        |---------------------------------------------------- */
        $admin = User::where('role_id', 1)->get();
        Notification::send($admin, new NewExpertAdmin($user));

        return response(Utility::returnSuccess('Registration successful',$user));
    }

    /* This method generates and stores the token 
      then returns the generated token */

    function storeToken($user_id) {
        $t = Tokens::where('user_id', $user_id)->first();
        Log::info('Tokena', [json_encode($t)]);
        if(!isset($t))
            $t = new Tokens();
        $t->user_id = $user_id;
        $t->token = $this->generateToken();
        $t->save();
        return $t->token;
    }

    function logout(User $user, Request $request){
        if(!empty($user)){
            $user->token()->delete();
        }
        return Utility::returnSuccess('Sign Out Successful');

    }

    function generateToken() {
        return str_random(64);
    }


    function social(Request $request)
    {
        $val = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required'
        ], [
            'name.required' => 'Fullname is required',
            'email.required' => "Email Address is required"
        ]);

        if ($val->fails())
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));
        $user = $this->users->getByEmail($request->email);
        if (!isset($user)) {
            $user = new User();
            $user->name = $request->name;
            $user->password = bcrypt(WebConstant::$DEFAULT_PASSWORD);
            $user->email = $request->email;
            if ($request->avatar != "") {
                $user->avatar = $request->avatar;
            }
            $user->save();
        }
        $user->token = $this->storeToken($user->id);

        return Utility::returnSuccess("Success", $user);
    }

}
