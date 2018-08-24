<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $table = "services";
    protected $dates = ['deleted_at'];
//    protected $hidden = [
//        'is_blocked',
//    ];



    public function approve() {
        $this->verified = true;
        $this->save();
        return "This Services has been approved";
    }

    public function getProfession(){
        return $this->belongsTo(Profession::class, 'profession_id');
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'service_id');
    }

    public function averageRating(){
        $reviews = $this->reviews;
        $ratings = $reviews->average('rating');
        return $ratings;
    }

}
