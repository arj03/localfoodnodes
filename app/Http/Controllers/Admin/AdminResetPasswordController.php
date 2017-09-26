<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;

class AdminResetPasswordController extends \App\Http\Controllers\Controller
{
    # use RedirectsUsers;

    /**
     * Show reset password form action.
     */
    public function initForm()
    {
        return view('admin.auth.passwords.email');
    }

    /**
     * Send password reset link email action.
     */
    public function sendLink(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // if (\App\User\Admin::where('email', '=', $request->get('email'))->exists() === false) {
        //     $request->session()->flash('message', ['User is not an administrator']);
        //     return redirect()->back()->withInput();
        // }

        $response = $this->broker()->sendResetLink($request->only('email'));

        if ($response === Password::RESET_LINK_SENT) {
            $request->session()->flash('message', [trans('admin/messages.password_reset_email_sent')]);
            return redirect()->back();
        }

        return redirect()->back()->withErrors(
            ['email' => trans($response)]
        );
    }

    /**
     * Show reset password form action.
     */
    public function resetForm(Request $request, $token = null)
    {
        return view('admin.auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password action.
     */
    public function reset(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed|min:8'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Add email to request
        $dbResetRow = DB::table('password_resets')->select('email', 'token')->where('email', $request->input('email'))->first();

        if (!Hash::check($request->input('token'), $dbResetRow->token)) {
            $request->session()->flash('error', [trans('admin/messages.reset_link_expired')]);
            return redirect()->back();
        }

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET ? $this->sendResetResponse($request, $response) : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => \Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\Response
     */
    protected function sendResetResponse($request, $response)
    {
        $request->session()->flash('message', [trans('admin/messages.user_password_changed')]);
        return redirect('/admin/login');
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        $request->session()->flash('message', [trans('admin/messages.user_password_not_changed')]);
        return redirect()->back()->withInput($request->only('email'))->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admin');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}
