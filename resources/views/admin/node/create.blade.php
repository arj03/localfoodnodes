@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    @if (app('request')->input('terms') === 'approved')
        <form action="/account/node/insert" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('admin.node.generic-node-form')
            @component('admin.form-control-bar')
                <button type="submit" class="btn btn-success">{{ trans('admin/node.save_node') }}</button>
            @endcomponent
        </form>
    @else
        @include('admin.node.terms')
    @endif
@endsection
