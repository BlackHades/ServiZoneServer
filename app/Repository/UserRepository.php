<?php
/**
 * Created by PhpStorm.
 * User: Doraemon
 * Date: 8/15/2018
 * Time: 8:36 AM
 */

namespace App\Repository;


use App\User;

class UserRepository
{
    function getByEmail($email){
        return User::where('email', $email)->first();
    }
}