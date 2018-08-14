<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utility;
use App\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller {

    public function all() {
        $professions = Profession::orderBy('profession')->get();
        return response()->json(Utility::returnSuccess("Success",$professions));
    }

    public function search(Request $request) {
        $professions = Profession::where('name', 'LIKE', '%' + $request->q)->get();
        return response()->json($professions);
    }

}
