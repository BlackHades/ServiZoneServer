<?php

namespace App\Http\Controllers\Api;

use App\Helper\Verification;
use App\Helper\WebConstant;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\Service;
use App\ServiceVerification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Snowfire\Beautymail\Beautymail;

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
                $s->profession = $s->getProfession->profession;
                $verify = (new Verification())->tokenize($s->email);
//                Mail::to($s->email)->send(new ServiceVerification($verify));
                $this->sendMail($s, $verify);
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
        $services = self::formatServiceCollection($services);
        return Utility::returnSuccess("Success", $services);
    }

    static function formatService(Service $service)
    {
        $service->profession = $service->getProfession->profession;
        $service->review = count($service->reviews) == 0 ? 0 : $service->reviews()->avg('rating');
        return $service;
    }

    static function formatServiceCollection($services)
    {
        for($i = 0; $i < count($services); $i++){
            $services[$i] = self::formatService($services[$i]);
        }

        return $services;
    }
    function updateDetails(Request $request, User $user)
    {
        $val = Validator::make($request->all(), [
            "service" => 'required|exists:services,id',
            'name' => 'required',
            'email' => 'required|email',
            'profession_id' => 'required|exists:professions,id',
            'mobile' => 'required',
            'about' => 'required',
        ], [
            'service.required' => "Service Id is required",
            'service.exists' => "Service does not exist",
            'name.required' => 'Service Name is required',
            'email.required' => 'Service Email is required',
            'mobile.required' => 'Service Mobile Number is Required',
            'profession_id.required' => "Profession is required",
            'profession_id.exists' => "invalid profession",
            'about.required' => "Service Description is required",
        ]);
        if ($val->fails()) {
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));
        }
        $service = Service::find($request->service);
        if ($user->id != $service->user_id)
            return response()->json(Utility::returnError("You are not authorised to edit service"));

        $service->profession_id = $request->profession_id;
        $service->name = $request->name;
        $service->email = $request->email;
        $service->about = $request->about;
        $service->mobile = $request->mobile;
        if ($service->save()) {
            $service->profession = $service->getProfession->profession;
            return response()->json(Utility::returnSuccess("Service Successfully Updated", $service));
        } else {
            return response()->json(Utility::returnError("Could not update service at this time. Try Again"));
        }
    }

    function updateAddress(Request $request, User $user)
    {
        $val = Validator::make($request->all(), [
            "service" => 'required|exists:services,id',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ], [
            'service.required' => "Service Id is required",
            'service.exists' => "Service does not exist",
            'address.required' => 'Service Address is required',
            'latitude.required' => "latitude is required",
            'longitude.required' => "longitude is required",
        ]);
        if ($val->fails()) {
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));
        }

        $service = Service::find($request->service);
        if ($user->id != $service->user_id)
            return response()->json(Utility::returnError("You are not authorised to edit service"));
        $service->address = $request->address;
        $service->latitude = $request->latitude;
        $service->longitude = $request->longitude;
        if ($service->save()) {
            $service->profession = $service->getProfession->profession;
            return response()->json(Utility::returnSuccess("Service Address Updated", $service));
        } else {
            return response()->json(Utility::returnError("Could not register service at this time. Try Again"));
        }
    }


    public function uploadAvatar(Request $request, User $user)
    {

        $val = Validator::make($request->all(), [
            "service" => 'required|exists:services,id',
            "avatar" => 'required'
        ], [
            'service.required' => "Service Id is required",
            'service.exists' => "Service does not exist",
            'avatar.required' => 'An Avatar is required'
        ]);
        if ($val->fails()) {
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));
        }

        if ($request->hasFile('avatar')) {
            $service = Service::find($request->service);
            if ($user->id != $service->user_id)
                return response()->json(Utility::returnError("You are not authorised to edit service"));
            $file = $request->file('avatar');
            $filename = time() . "@$service->email-avatar." . $file->getClientOriginalExtension();
            $file->move(public_path("uploads/"), $filename);
            $service->avatar = $filename;
            $service->save();
            return Utility::returnSuccess($filename);
        }
        return Utility::returnError("Avatar Not Found");

    }


    function delete(Request $request, User $user)
    {
        $val = Validator::make($request->all(), [
            "service" => 'required|exists:services,id',
        ], [
            'service.required' => "Service Id is required",
            'service.exists' => "Service does not exist",
        ]);

        if ($val->fails()) {
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));
        }

        $service = Service::find($request->service);
        if ($user->id != $service->user_id)
            return response()->json(Utility::returnError("You are not authorised to edit service"));

        try {
            $service->delete();
            return response()->json(Utility::returnSuccess("Service Deleted Successfully"));
        } catch (\Exception $e) {
            return response()->json(Utility::returnError("Unable to delete service at this moment", $e->getMessage()));
        }
    }


    private function sendMail(Service $service, ServiceVerification $verify){
        $data = [];
        $data['name'] = $service->name;
        $data['code'] = $verify->code;
        Log::info("Mailer", [$data]);
        $beautymail = app()->make(Beautymail::class);
        $beautymail->send('emails.code', $data, function($message) use($service)
        {
            $message
                ->from(WebConstant::$DO_NOT_REPLY_MAIL)
                ->to($service->email, $service->name)
                ->subject('Service Email Verification');
        });
        Log::info("Sent", [$data]);

    }

    public function search(Request $request)
    {
        $search = null;
        if (isset($request->name) && $request->profession != 0) {
            $search = Service::where('name', "LIKE", "%{$request->name}%")
                ->where("profession_id", $request->profession)
                ->orderBy("name", 'ASC')
                ->get();
        } elseif (isset($request->name) && $request->profession == 0) {
            $search = Service::where('name', "LIKE", "%{$request->name}%")
                ->orderBy("name", 'ASC')
                ->get();
        } elseif (!isset($request->name) && $request->profession == 0) {
            $search = Service::orderBy("name", 'ASC')
                ->get();
        } elseif (!isset($request->name) && $request->profession != 0) {
            $search = Service::orderBy("name", 'ASC')
                ->where("profession_id", $request->profession)
                ->get();
        }

        $search = self::formatServiceCollection($search);
        return Utility::returnSuccess('Success', $search);
    }

    public function close(Request $request)
    {
        $val = Validator::make($request->all(), [
            "latitude" => 'required',
            "longitude" => 'required'
        ], [
            'latitude.required' => "Latitude is required",
            'longitude.required' => "Longitude is required",
        ]);

        if ($val->fails()) {
            return response()->json(Utility::returnError("Validation Error", implode("\n", $val->errors()->all())));
        }

        $longitude = $request->longitude;
        $latitude = $request->latitude;
//        echo $query;return;

        $services = Service::nearest($latitude, $longitude)->take(20);

        if (count($services) == 0)
            return response()->json(Utility::returnError("No Services found"));


        $services = self::formatServiceCollection($services);
        //return $services;
        return Utility::returnSuccess("Done", $services);
    }
}
