<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    //
    function getService()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
