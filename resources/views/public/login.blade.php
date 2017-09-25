@extends('public.layout')

@section('title', trans('public/login.title'))

@section('content')
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">{{ trans('public/login.title') }}</div>
                    <div class="card-body">
                        <form action="/authenticate" method="post" class="mb-5">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email">{{ trans('public/login.email') }}</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ Request::old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="password">{{ trans('public/login.password') }}</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                            </div>
                            <div class="row">
                                <div class="col login-helper">
                                    <a href="/account/user/create">{{ trans('public/login.create') }}</a>
                                    <a href="/password/reset">{{ trans('public/login.forgot') }}</a>
                                </div>
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-success">{{ trans('public/login.login') }}</button>
                                </div>
                            </div>
                        </form>
                        <a href="/login/facebook" class="btn btn-success"><i class="fa fa-facebook-square"></i> {{ trans('public/nav.facebook') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
