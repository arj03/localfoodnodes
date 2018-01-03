<?php

namespace App\Http\Controllers\Api\v1\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User\User;

class UsersController extends \App\Http\Controllers\Controller
{
    public function users(Request $request)
    {
        if ($request->user()->tokenCan('users-read-all')) {
            if ($request->user()->tokenCan('users-read-emails')) {
                return User::all();
            } else {
                return User::exclude(['email'])->get();
            }
        } else {
            return response('Unauthorized', 403);
        }
    }

    public function self(Request $request)
    {
        if ($request->user()->tokenCan('users-read-self')) {
            return Auth::guard('api')->user();
        } else {
            return response('Unauthorized', 403);
        }
    }

    public function update(Request $request)
    {
        $user = User::find($request->input('id'));

        foreach ($request->input('fields') as $key => $value) {
            $user->{$key} = $value;
        }

        $user->save();

        return $user;
    }
}
