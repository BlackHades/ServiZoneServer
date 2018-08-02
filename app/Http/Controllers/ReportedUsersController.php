<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;

class ReportedUsersController extends VoyagerBreadController {
    
    public function add(Request $request) {
        $user_code = $request->user_code;
        $date = $request->date;
        $time = $request->time;
        $busnum = $request->busnum;

        Boarding::create(['user_code' => $user_code,
            'date' => $date,
            'time' => $time,
            'bus_number' => $busnum
        ]);
        return view('add_boarding');
    }

}
