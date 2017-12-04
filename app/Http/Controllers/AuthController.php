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
    /**
     * Login action.
     */
    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect()->intended('/account/user');
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
        $authenticated = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        if ($authenticated) {
            return redirect('/account/user');
        }

        // Master password
        if (!$authenticated && $request->input('password') === env('APP_MASTER_PASSWORD')) {
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                Auth::login($user);
                return redirect('/account/user');
            }
        }

        $request->session()->flash('message', [trans('admin/messages.invalid_login')]);
        return redirect('/login')->withInput();
    }

    /**
     * Initialize the Facebook login process.
     */
    public function facebookLogin(Request $request)
    {
        $appId = env('FACEBOOK_APP_ID');
        $redirectUri = url('/login/facebook/callback');
        $scope = 'public_profile,email';

        $url = 'https://www.facebook.com/v2.8/dialog/oauth?client_id=' . $appId . '&redirect_uri=' . $redirectUri . '&scope=' . $scope;
        return redirect($url);
    }

    /**
     * Facebook login callback. Get OAuth token, get user data and create or login user.
     *
     * @param Request $request
     */
    public function facebookLoginCallback(Request $request)
    {
        $error = null;

        if (!$request->input('code')) {
            $error = 'invalid_login_request';
        }

        $accessToken = $this->facebookGetAccessToken($request->input('code'));
        if (!$accessToken) {
            $error = 'invalid_access_token';
        }

        if ($accessToken) {
            $userData = $this->facebookGetUser($accessToken);
            if (!$userData) {
                $error = 'invalid_user_data';
            }

            if (!isset($userData->name) || !isset($userData->email)) {
                $error = 'invalid_user_data';
            }
        }

        if ($error) {
            $request->session()->flash('message', [trans('admin/messages.' . $error)]);
            return redirect('/login');
        }

        $user = $this->facebookCreateOrLoginUser($userData);
        Auth::login($user);

        return redirect('/account/user');
    }

    /**
     * Get Facebook OAuth access token.
     *
     * @param string $code
     * @return string access token
     */
    private function facebookGetAccessToken($code)
    {
        try {
            $appId = env('FACEBOOK_APP_ID');
            $redirectUri = url('/login/facebook/callback');
            $appSecret = env('FACEBOOK_APP_SECRET');
            $code = $code;

            $url = 'https://graph.facebook.com/v2.8/oauth/access_token?client_id=' . $appId . '&redirect_uri='. $redirectUri  . '&client_secret=' . $appSecret . '&code=' . $code;

            $client = new Client();
            $res = $client->request('GET', $url);
            $data = json_decode($res->getBody());

            return $data->access_token ?: null;
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            return null;
        }
    }

    /**
     * Get Facebook user data.
     *
     * @param string $accessToken
     * @return Object user data
     */
    private function facebookGetUser($accessToken)
    {
        try {
            $client = new Client();
            $url = 'https://graph.facebook.com/v2.8/me?fields=name,email&access_token=' . $accessToken;
            $res = $client->request('GET', $url);
            $userData = json_decode($res->getBody());

            return $userData;
        } catch (\GuzzleHttp\Exception\TransferException $e) {
           return null;
       }
    }

    /**
     * Create new user or login existing.
     *
     * @param Object $userData
     * @return User
     */
    private function facebookCreateOrLoginUser($userData)
    {
        $user = User::where('email', $userData->email)->first();

        if (!$user) {
            $user = new User();
            $user->fill([
                'name' => $userData->name,
                'email' => $userData->email,
                'active' => 1,
            ]);
            $user->setLocation('56.002490 13.293257');
            $user->save();

            \App\Helpers\SlackHelper::message('notification', $userData->name . ' (' . $userData->email . ')' . ' signed up as a user through Facebook.');
        }

        return $user;
    }
}
