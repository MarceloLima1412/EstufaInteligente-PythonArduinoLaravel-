<?php

namespace App;

use App\Config;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Config extends Authenticatable
{
    protected $fillable = [
        'id', 'descricao', 'variavel'
    ];

}
