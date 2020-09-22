<?php

namespace App;

use App\Image;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Image extends Authenticatable
{
  
    protected $fillable = [
        'id', 'image', 'data'
    ];

}
