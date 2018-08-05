<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expert extends Model {

    protected $table = 'users';

    public function newQuery() {
        $query = parent::newQuery();
        $query = $query->where('type', 'expert')
                ->where('is_blocked', false);
        return $query;
    }
    
    public function approve() {
        $this->status = "verified";
        $this->save();
        return "The expert has been approved";
    }
    
    public function profession(){
        return $this->belongsTo('App\Profession', 'profession_id');
    }
    
    public function reviews(){
        return $this->hasMany('App\Review','expert_id');
    }
    
    public function averageRating(){
        $reviews = $this->reviews;
        $ratings = $reviews->average('rating');
        return $ratings;
    }
}