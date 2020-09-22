<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tempehumid extends Authenticatable
{
  
    protected $fillable = [
        'id', 'temperatura', 'humidade', 'data'
    ];

}
