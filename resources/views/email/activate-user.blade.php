@extends('email.layout')

@section('content')
<h1>Welcome {{ $user->name }}</h1>
<table cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-bottom: 20px; background: #fff">
    <tr>
        <td style="padding: 20px;">
            Your account on localfoodnodes.org has been created and you need to activate it by confirming your email address. Click on the button below to confirm your email address.

            <a style="border: 1px solid #ccc; border-radius: 4px; padding: 10px; display: block; margin: 40px auto 0; width: 150px; text-align: center; text-decoration: none; background: #fff; color: #51b26e;" href="{{ app('url')->to('/account/user/activate/token/' . $token) }}">Activate account</a>
        </td>
    </tr>
</table>
@endsection
