@extends('public.layout')

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">Reset Password</div>
                    <div class="card-block">
                        <form method="post" action="/password/email">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="form-control-label">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success"><i class="fa fa-mail"></i> Send Password Reset Link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
