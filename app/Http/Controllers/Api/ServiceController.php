<?php

namespace App\Http\Controllers\Api;

use App\Helper\Verification;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\Mail\ServiceVerification;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function create(Request $request, User $user){
        $val = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'profession_id' => 'required|exists:professions,id',
            'mobile' => 'required',
            'about' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ],[
            'name.required' => 'Service Name is required',
            'email.required' => 'Service Email is required',
            'address.required' => 'Service Address is required',
            'mobile.required' => 'Service Mobile Number is Required',
            'profession_id.required' => "Profession is required",
            'profession_id.exists' => "invalid profession",
            'about.required' => "Service Description is required",
            'latitude.required' => "latitude is required",
            'longitude.required' => "longitude is required",
        ]);

        if($val->fails()){
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));
        }

        if(!empty($user) && isset($user)){
            $s = new Service();
            $s->user_id = $user->id;
            $s->profession_id = $request->profession_id;
            $s->name = $request->name;
            $s->email = $request->email;
            $s->address =$request->address;
            $s->about = $request->about;
            $s->mobile = $request->mobile;
            $s->latitude =$request->latitude;
            $s->longitude = $request->longitude;
            if($s->save()){
//            if(true){
                $verify = (new Verification())->tokenize($s->email);
                Mail::to($s->email)->send(new ServiceVerification($verify));
                return response()->json(Utility::returnSuccess("Service Successfully Registered", $s));
            }else{
                return response()->json(Utility::returnError("Could not register service at this time. Try Again"));
            }
        }else{
            return response()->json(Utility::returnError("User Not Found"));
        }
    }


    public function getByUserId(User $user){
        Log::info("Here",[$user]);
        $services = Service::where('user_id', $user->id)->orderBy('id','DESC')->get();
//        foreach ($services as $service){
//            $services->profession = $service->profession->profession;
//        }

        for($i = 0; $i < count($services); $i++){
            $services[$i]->profession = $services[$i]->getProfession->profession;
        }
        return Utility::returnSuccess("Success", $services);
    }
}
