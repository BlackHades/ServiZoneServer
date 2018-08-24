<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

}
