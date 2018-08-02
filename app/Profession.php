<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
     protected $hidden = [
        'updated_at', 'created_at',
    ];

}
