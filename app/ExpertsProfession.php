<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertsProfession extends Model
{
    protected $fillable = [
        'name', 'email', 'password',
    ];
    
    public function profession() {
        return $this->belongsTo('App\Profession');
    }
    
    public function expert() {
        return $this->belongsTo('App\Expert');
    }

}
