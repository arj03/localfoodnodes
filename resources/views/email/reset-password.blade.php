@extends('email.layout')

@section('content')
    <h1 style="margin: 0 0 20px; color: #333;">Hi {{ $user->name }}</h1>
    <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <td bgcolor="#fff">
                <div style="background: #d6c69b;">
                    <div style="text-transform: uppercase; color: #fff; padding: 15px; font-weight: bold;">Reset your password</div>
                </div>
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td colspan="2" style="padding: 20px;">
                            You recently requested to reset your password for your account on localfoodnodes.org. Click on the button below to continue to the reset password page.

                            <a style="border: 1px solid #8fb773; background-color: #8fb773; border-radius: 4px; padding: 10px; display: block; margin: 40px auto 20px; width: 160px; text-align: center; text-decoration: none; color: #fff;"  href="{{ app('url')->to('/password/reset/' . $token) }}">Reset password</a>

                            If you did not request a password reset, please ignore this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
