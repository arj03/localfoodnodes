@extends('public.layout')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">Reset Password</div>

                <div class="card-block">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="form-control-label">New password</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="form-control-label">Confirm password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
