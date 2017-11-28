@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    @if (app('request')->input('terms') === 'approved')
        <form action="/account/node/insert" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('account.node.generic-node-form', ['showDeliverySettings' => true])
            @component('account.form-control-bar')
                <button type="submit" class="btn btn-success">{{ trans('admin/node.save_node') }}</button>
            @endcomponent
        </form>
    @else
        @include('account.node.terms')
    @endif
@endsection
