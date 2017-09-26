<?php

namespace App\User;

use App\Mail\ResetPassword as ResetPasswordNotification;
use Mail;

class Admin extends \App\User\BaseUser
{
    protected $validationRules = [
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:8',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Override laravel default reset password email.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::send('email.admin-reset-password', ['token' => $token], function($message) {
            $message->to($this->email)->subject('Reset password');
        });
    }
}
