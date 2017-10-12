@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">{{ trans('admin/node.confirm_delete') }}</div>
                <div class="card-body">
                    {{ trans('admin/node.confirm_delete_info') }}
                </div>
            </div>
        </div>
    </div>

    @component('account.form-control-bar')
        <a href="/account/node/{{ $node->id }}/edit" class="btn btn-success">{{ trans('admin/node.cancel') }}</button>
        <a href="/account/node/{{ $node->id }}/delete" class="btn btn-danger">{{ trans('admin/node.delete') }}</a>
    @endcomponent
@endsection
