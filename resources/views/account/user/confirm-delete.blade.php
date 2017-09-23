@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.confirm_delete') }}</div>
                <div class="card-block">
                    {{ trans('admin/user.confirm_delete_info') }}
                </div>
            </div>
        </div>
    </div>

    @component('account.form-control-bar')
        <a href="/account/user/edit" class="btn btn-success">{{ trans('admin/user.cancel') }}</button>
        <a href="/account/user/delete" class="btn btn-danger">{{ trans('admin/user.delete') }}</a>
    @endcomponent
@endsection
