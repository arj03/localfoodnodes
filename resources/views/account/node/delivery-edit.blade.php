@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <form action="/account/node/{{ $node->id }}/delivery/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include('account.node.delivery-settings-form')
        @component('account.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/node.save_node') }}</button>
        @endcomponent
    </form>
@endsection
