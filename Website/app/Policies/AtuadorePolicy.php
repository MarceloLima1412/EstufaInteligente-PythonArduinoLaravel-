<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtuadorePolicy
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

   public function view(User $user, Atuadore $atuadore)
    {
        if($user == null ){
            return false;
        }
        if($user->tipo_user == "Dono" || $user->tipo_user == "Funcionario"){
            return true;
        }
            return false;
    }

}
