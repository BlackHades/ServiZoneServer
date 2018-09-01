<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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


    public static function nearest($latitude, $longitude)
    {
        $circle_radius = 3959;
        $query = DB::select('SELECT * FROM
                    (SELECT services.id, services.name, services.avatar, services.profession_id, mobile, latitude, longitude, (' . $circle_radius . ' * acos(cos(radians(' . $latitude . ')) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(' . $longitude . ')) +
                    sin(radians(' . $latitude . ')) * sin(radians(latitude))))
                    AS distance
                    FROM services 
                    where `is_blocked` = "0") 
                    AS distances
        ORDER BY distance');

//        echo $query;return;

        return Service::hydrate($query);
    }

}
