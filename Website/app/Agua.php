<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agua extends Authenticatable
{
  
    protected $fillable = [
        'id', 'qtdAgua', 'data'
    ];

}
