<?php

namespace App\Http\Controllers\Api\v1\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends \App\Http\Controllers\Controller
{
    public function users(Request $request)
    {
        return Auth::guard('api')->user();

        return User::exclude(['email'])->get();
    }

    public function nodes(Request $request)
    {
        $user = Auth::guard('api')->user();
        $nodeLinks = $user->nodeLinks();

        return $nodeLinks->map(function($nodeLink) {
            return $nodeLink->getNode();
        });
    }
}
