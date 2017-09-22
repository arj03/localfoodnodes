@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <form action="/account/node/{{ $node->id }}/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include('account.node.generic-node-form')
        @component('account.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/node.save_node') }}</button>
            @if ($node->adminLinks()->count() > 1)
                <a href="/account/node/{{ $node->id }}/leave" class="btn btn-warning">{{ trans('admin/node.leave_node') }}</a>
            @else
                <a href="/account/node/{{ $node->id }}/delete/confirm" class="btn btn-danger">{{ trans('admin/node.delete_node') }}</a>
            @endif
        @endcomponent
    </form>
@endsection
