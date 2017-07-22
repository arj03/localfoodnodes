@extends('email.layout')

@section('content')
    <div style="margin-bottom: 20px;">
        <h1 style="margin: 0 0 20px; color: #333;">{{ trans('public/email.welcome') }} {{ $user->name }}</h1>
    </div>

    <div class="order-date" style="margin-bottom: 40px; background: #fff; box-shadow: #ccc 0 1px 8px -2px;">
        <div style="background: #d6c69b; overflow: hidden; text-transform: uppercase; color: #fff; padding: 15px; font-weight: bold;">
            {{ trans('public/email.activate_your_account') }}
        </div>

        <div style="overflow: hidden; padding: 20px;">
            {{ trans('public/email.activate_text') }}
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <a style="background: #8fb773; color: #fff; padding: 10px; border-radius: 4px; text-transform: uppercase;" href="{{ app('url')->to('/account/user/activate/token/' . $token) }}">{{ trans('public/email.activate_your_account') }}</a>
    </div>
@endsection
