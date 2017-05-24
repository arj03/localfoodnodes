@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="master-alerts">
        <div class="alert alert-danger payment-errors" style="display: none;"></div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8">

            <div class="card">
                <div class="card-block body-text">
                    {!! trans('public/pages/membership.body') !!}
                </div>
            </div>

            @include('admin.user.membership-form')

            <div class="card">
                <div class="card-block">
                    <input type="submit" class="submit btn btn-success" value="{{ trans('admin/user.submit_payment') }}">
                </div>
            </div>
        </div>
    </div>
@endsection
