@extends('public.layout-page', [
    'header' => trans('public/login.title'),
    'image' => '/images/shutterstock_326785574.jpg'
])

@section('title', trans('public/login.title'))

@section('page-content')
    @if (Request::has('error'))
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="card error-message">
                        <div class="card-header">{{ trans('public/login.error_' . Request::input('error')) }}</div>
                        <div class="card-body">{{ trans('public/login.error_' . Request::input('error') . '_content') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Request::has('message'))
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="card success-message">
                        <div class="card-header">{{ trans('public/login.message_' . Request::input('message')) }}</div>
                        <div class="card-body">{{ trans('public/login.message_' . Request::input('message') . '_content') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container top-container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card login-card">
                    <div class="card-header">
                        <a href="/login/facebook" class="btn btn-facebook"><i class="fa fa-facebook-square"></i> {{ trans('public/nav.facebook') }}</a>
                    </div>
                    <div class="card-body">
                        <form action="/authenticate" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email">{{ trans('public/login.email') }}</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="{{ trans('public/login.email') }}" value="{{ Request::old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="password">{{ trans('public/login.password') }}</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="{{ trans('public/login.password') }}">
                            </div>
                            <button type="submit" class="btn btn-success">{{ trans('public/login.login') }}</button>
                        </form>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="/account/user/create">{{ trans('public/login.create') }}</a>
                        <a href="/password/reset">{{ trans('public/login.forgot') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
