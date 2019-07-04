<?php

namespace Colorize;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'email', 'password','password_confirmation', 'active', 'activation_token', 'avatar', 'role', 'phone' , 'city'
    ];
}
