<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profession;
class ProfessionController extends Controller {

    public function index() {
        $professions = Profession::orderBy('profession')->get();
        return response()->json($professions);
    }

    public function search(Request $request) {
        $professions = Profession::where('name', 'LIKE', '%' + $request->q)->get();
        return response()->json($professions);
    }

}
