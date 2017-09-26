<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User\User;
use DateTime;

class UserController extends \App\Http\Controllers\Controller
{
    /**
     * Get all users
     */
    public function index(Request $request)
    {
        return User::all();
    }
}
