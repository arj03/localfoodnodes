@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    @if (app('request')->input('terms') === "approved")
        <form action="/account/producer/insert" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('admin.producer.generic-producer-form')
            @component('admin.form-control-bar')
                <button type="submit" class="btn btn-success">{{ trans('admin/producer.save_producer') }}</button>
            @endcomponent
        </form>
    @else
        @include('admin.producer.terms')
    @endif
@endsection
