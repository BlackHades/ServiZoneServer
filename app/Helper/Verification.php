<?php
/**
 * Created by PhpStorm.
 * User: Doraemon
 * Date: 8/10/2018
 * Time: 11:16 AM
 */

namespace App\Helper;


use App\Repository\VerifyRepository;
use App\ServiceVerification;
use App\Tokens;

class Verification
{
    public $sv;
    public function __construct()
    {
        $this->sv = new VerifyRepository();
    }

    public function tokenize($email){
        $s = $this->sv->getByEmail($email);
        if(!isset($s)){
            $s = new ServiceVerification();
            $s->email = $email;
        }
        $s->code = rand(10000000, 99999999);
        $s->save();
        return $s;
    }


    public function generateSessionToken($user_id){
        return $this->storeToken($user_id);
    }

    private function storeToken($user_id) {
        $t = Tokens::where('user_id', $user_id)->first();
//        Log::info('Tokena', [json_encode($t)]);
        if(!isset($t))
            $t = new Tokens();
        $t->user_id = $user_id;
        $t->token = str_random(64);
        $t->save();
        return $t->token;
    }
}