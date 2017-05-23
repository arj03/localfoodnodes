@extends('public.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.account_activation') }}</div>
                <div class="card-block">
                    <p>{{ trans('admin/user.account_activation_info') }}</p>
                </div>
                <div class="card-footer">
                    <a class="btn btn-success" href="/account/user/activate/resend">{{ trans('admin/user.resend') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
