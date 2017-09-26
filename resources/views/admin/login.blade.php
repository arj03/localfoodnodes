@extends('admin.layout')

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
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form action="/admin/authenticate" method="post" class="mb-5">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ Request::old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                            </div>
                            <div class="row">
                                <div class="col login-helper">
                                    <a href="/admin/password/reset">Reset password</a>
                                </div>
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-success">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
