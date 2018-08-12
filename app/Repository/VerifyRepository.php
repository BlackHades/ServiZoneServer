<?php
/**
 * Created by PhpStorm.
 * User: Doraemon
 * Date: 8/10/2018
 * Time: 11:22 AM
 */

namespace App\Repository;


use App\ServiceVerification;

class VerifyRepository
{
    function getByEmail($email){
        return ServiceVerification::where('email', $email)->first();
    }
}