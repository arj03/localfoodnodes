@extends('public.layout')

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">{{ trans('admin/user.reset_password') }}</div>
                    <div class="card-block">
                        <form method="post" action="/password/email">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="email" class="form-control-label">
                                    {{ trans('admin/user.email') }}
                                    @include('account.field-error', ['field' => 'email'])
                                </label>
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success"><i class="fa fa-mail"></i> {{ trans('admin/user.send_password_reset_link') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
