<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

     public function view(User $userlogado)
    {
        if($userlogado->tipo_user == "Dono"){
            return true;
        }
            return false;
    }
}
