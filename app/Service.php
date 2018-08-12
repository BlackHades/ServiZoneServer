<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $table = "services";
    protected $dates = ['deleted_at'];



    public function approve() {
        $this->verified = true;
        $this->save();
        return "This Services has been approved";
    }

    public function profession(){
        return $this->belongsTo(Profession::class, 'profession_id');
    }

    public function reviews(){
        return $this->hasMany(Review::class,'expert_id');
    }

    public function averageRating(){
        $reviews = $this->reviews;
        $ratings = $reviews->average('rating');
        return $ratings;
    }

}
