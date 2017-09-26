<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;

class AdminAuthController extends \App\Http\Controllers\Controller
{
    /**
     * Login action.
     */
    public function login(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin');
        }

        return view('admin.login');
    }

    /**
     * Logout action.
     */
    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect('/admin');
    }

    /**
     * User authenticate action.
     */
    public function authenticate(Request $request)
    {
        $authenticated = Auth::guard('admin')->attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if ($authenticated) {
            return redirect('/admin');
        }

        $request->session()->flash('message', [trans('admin/messages.invalid_login')]);

        return redirect('/admin/login')->withInput();
    }
}
