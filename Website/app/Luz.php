<?php

namespace App;

use App\Luz;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Luz extends Authenticatable
{
    protected $fillable = [
        'id', 'qtdLuz', 'data'
    ];

}
