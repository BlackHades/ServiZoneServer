<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingExperts extends Model {

    protected $table = 'users';

    public function newQuery() {
        $query = parent::newQuery();
        $query = $query->where('status', 'pending')
                ->where('is_blocked', false);
        return $query;
    }
    
    public function professions() {
        return $this->hasMany('App\ExpertsProfession', 'expert_id', 'id');
    }

}
