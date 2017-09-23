@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    @if (app('request')->input('terms') === "approved")
        <form action="/account/producer/insert" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('account.producer.generic-producer-form')
            @component('account.form-control-bar')
                <button type="submit" class="btn btn-success">{{ trans('admin/producer.save_producer') }}</button>
            @endcomponent
        </form>
    @else
        @include('account.producer.terms')
    @endif
@endsection
