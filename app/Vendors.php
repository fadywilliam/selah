<?php

namespace App;
use Laravel\Passport\HasApiTokens;  // added by fady
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\Vendors as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendors extends Authenticatable
{
     use HasApiTokens, Notifiable; // added by fady

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','password',
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
