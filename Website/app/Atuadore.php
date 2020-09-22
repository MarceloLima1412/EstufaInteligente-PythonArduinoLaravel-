<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Atuadore extends Authenticatable
{
  
    protected $fillable = [
        'id', 'tipo', 'ativo'
    ];

}
