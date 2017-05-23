@extends('email.layout')

@section('content')
<h1>Hi {{ $user->name }}</h1>
<table cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-bottom: 20px; background: #fff">
    <tr>
        <td style="padding: 20px;">
            You recently requested to reset your password for your account on localfoodnodes.org. Click the button below to reset it.

            <a style="border: 1px solid #ccc; border-radius: 4px; padding: 10px; display: block; margin: 40px auto 40px; width: 150px; text-align: center; text-decoration: none; background: #fff; color: #51b26e;" href="{{ app('url')->to('/password/reset/' . $token) }}">Reset password</a>

            If you did not request a password reset, please ignore this email.
        </td>
    </tr>
</table>
@endsection
