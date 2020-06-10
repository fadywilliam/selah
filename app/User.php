<?php

namespace App;
use Laravel\Passport\HasApiTokens;  // added by fady
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
     use HasApiTokens, Notifiable; // added by fady

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                'type','name','national_id','phone','verification_phone_code','verification_phone_status', 'password',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
