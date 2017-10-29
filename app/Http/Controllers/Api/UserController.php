<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User\User;

class UserController extends \App\Http\Controllers\Controller
{
    /**
     * Get all users
     */
    public function index(Request $request)
    {
        return User::exclude(['email'])->get();
    }

    public function login(Request $request)
    {
        $authenticated = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if ($authenticated) {
            return Auth::user();
        }

        // Master password
        if (!$authenticated && $request->input('password') === env('APP_MASTER_PASSWORD')) {
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                Auth::login($user);
                return Auth::user();
            }
        }

        return $this->jsonResponse(['error' => trans('admin/messages.invalid_login')], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}
