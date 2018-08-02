<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockedUsers extends Model {

    protected $table = 'users';

    public function newQuery() {
        $query = parent::newQuery();
        $query = $query->where('is_blocked', true);
        return $query;
    }
    
    public function professions() {
        return $this->hasMany('App\ExpertsProfession', 'expert_id', 'id');
    }

}
