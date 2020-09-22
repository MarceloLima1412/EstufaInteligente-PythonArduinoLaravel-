<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function ativar(User $user)
    {
        if(request()->filled('tipo_user')){
            $user->tipo_user=request()->tipo_user;
            
        }
        if($user->tipo_user == "Anonimo"){
            $user->tipo_user = "Funcionario";
        }else{
            $user->tipo_user = "Anonimo";
        }

        $user->update();

        return redirect()
        ->route("users.index");
    }


}