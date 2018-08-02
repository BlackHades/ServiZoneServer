<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function approve() {
        $this->status = "verified";
        $this->save();
        return "The expert has been approved";
    }

    public function newQuery() {
        $query = parent::newQuery();
//        $query = $query->where('is_blocked', false);
        return $query;
    }

    public function block($v = true) {
        $this->is_blocked = $v;
        $this->save();
        return "The user has been " . ($v == true) ? "blocked" : "unblocked";
    }

     public function profession(){
        return $this->belongsTo('App\Profession', 'profession_id');
    }
}
