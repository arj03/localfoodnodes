<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Str;

use App\Http\Requests;

class ResetPasswordController extends Controller
{
    # use RedirectsUsers;

    /**
     * Show reset password form action.
     */
    public function initForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send password reset link email action.
     */
    public function sendLink(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = $this->broker()->sendResetLink($request->only('email'));

        if ($response === Password::RESET_LINK_SENT) {
            $request->session()->flash('message', [trans('admin/messages.password_reset_email_sent')]);
            return redirect('/');
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
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password action.
     */
    public function reset(Request $request)
    {
        $rules = ['token' => 'required', 'password' => 'required|confirmed|min:8'];
        $this->validate($request, $rules);

        // Add email to request
        $email = DB::table('password_resets')->select('email')->where('token', $request->input('token'))->value('email');
        $request->merge(['email' => $email]);

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
            'password' => bcrypt($password),
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
        return redirect('/login');
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
        return redirect()->back()->withInput($request->only('email'))->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
