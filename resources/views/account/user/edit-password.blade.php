@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <form action="/account/user/password/update" method="post">
        <div class="row">
            <div class="col-12 col-xl-8">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header">{{ trans('admin/user.change_password') }}</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label" for="password">{{ trans('admin/user.new_password') }}</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="{{ trans('admin/user.password') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @component('account.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/user.change_password') }}</button>
        @endcomponent
    </form>
@endsection
