<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportedUsers extends Model {

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function expert() {
        return $this->belongsTo('App\User')->where('type', 'expert');
    }

}
