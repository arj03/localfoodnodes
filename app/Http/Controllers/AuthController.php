<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use GuzzleHttp\Client;

use App\Http\Requests;
use App\User\User;

class AuthController extends Controller
{
    private $loginFallbackUrl = '/account/user';


    /**
     * Login action.
     */
    public function login(Request $request)
    {
        $request->session()->put('login_url', \URL::previous());

        if (Auth::check()) {
            $redirectUrl = $request->session()->get('login_url', $this->loginFallbackUrl);
            return redirect($redirectUrl);
        }

        return view('public.login');
    }

    /**
     * Logout action.
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }

    /**
     * User authenticate action.
     */
    public function authenticate(Request $request)
    {
        $redirectUrl = $request->session()->get('login_url', $this->loginFallbackUrl);

        $authenticated = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if ($authenticated) {
            return redirect($redirectUrl);
        }

        // Master password
        if (!$authenticated && $request->input('password') === env('APP_MASTER_PASSWORD')) {
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                Auth::login($user);
                return redirect($redirectUrl);
            }
        }

        $request->session()->flash('message', [trans('admin/messages.invalid_login')]);
        return redirect('/login')->withInput();
    }
}
