@extends('public.layout')

@section('title', trans('admin/user.reset_password'))

@section('content')
<div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.reset_password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email" class="form-control-label">
                                {{ trans('admin/user.email') }}
                                @include('account.field-error', ['field' => 'email'])
                            </label>
                            <input id="email" type="text" class="form-control" name="email" value="{{ Request::old('email') }}">
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-control-label">
                                {{ trans('admin/user.new_password') }}
                                @include('account.field-error', ['field' => 'password'])
                            </label>
                            <input id="password" type="password" class="form-control" name="password">
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="form-control-label">
                                {{ trans('admin/user.confirm_password') }}
                                @include('account.field-error', ['field' => 'password'])
                            </label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">{{ trans('admin/user.reset_password') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
