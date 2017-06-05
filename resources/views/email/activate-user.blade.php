@extends('email.layout')

@section('content')
    <h1 style="margin: 0 0 20px; color: #333;">{{ trans('public/email.welcome') }} {{ $user->name }}</h1>
    <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <td bgcolor="#fff">
                <div style="background: #d6c69b;">
                    <div style="text-transform: uppercase; color: #fff; padding: 15px; font-weight: bold;">{{ trans('public/email.activate_your_account') }}</div>
                </div>
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td colspan="2" style="padding: 20px;">
                            {{ trans('public/email.activate_text') }}

                            <a style="border: 1px solid #8fb773; background-color: #8fb773; border-radius: 4px; padding: 10px; display: block; margin: 40px auto 0; width: 160px; text-align: center; text-decoration: none; color: #fff;" href="{{ app('url')->to('/account/user/activate/token/' . $token) }}">{{ trans('public/email.activate_your_account') }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
