<?php

namespace App\Http\Controllers\Api;

use App\History;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{


    function getUserHistory(User $user)
    {
        $history = History::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        for ($i = 0; $i < count($history); $i++)
            $history[$i]->service = ServiceController::formatService($history[$i]->getService()->orderBy('id', 'DESC')->first());

        return response()->json(Utility::returnSuccess("Success", $history));
    }


    function getServiceHistory(Request $request)
    {
        $val = Validator::make($request->all(), [
            "service" => 'required|exists:services,id',
        ], [
            'service.required' => "Service Id is required",
        ]);

        if ($val->fails()) {
            return response()->json(Utility::returnError(implode("\n", $val->errors()->all())));
        }
        $history = History::where('service_id', $request->service)->orderBy('id', 'DESC')->get();
        for ($i = 0; $i < count($history); $i++)
            $history[$i]->user = $history[$i]->getUser()->select("name", "email")->orderBy('id', 'DESC')->get();

        return response()->json(Utility::returnSuccess("Success", $history));

    }


    function create(Request $request, User $user)
    {
        $val = Validator::make($request->all(), [
            "service" => 'required|exists:services,id',
        ], [
            'service.required' => "Service Id is required",
        ]);

        if ($val->fails()) {
            return response()->json(Utility::returnError(implode("\n", $val->errors()->all())));
        }

        $h = new History();
        $h->user_id = $user->id;
        $h->Service_id = $request->service;
        if ($h->save()) {
            return response()->json(Utility::returnSuccess());
        } else {
            return response()->json(Utility::returnError());
        }
    }
}
