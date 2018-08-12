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
}