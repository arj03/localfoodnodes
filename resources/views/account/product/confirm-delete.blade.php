@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">{{ trans('admin/product.confirm_delete') }}</div>
                <div class="card-body">
                    {{ trans('admin/product.confirm_delete_info') }}
                </div>
            </div>
        </div>
    </div>

    @component('account.form-control-bar')
        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/edit" class="btn btn-success">{{ trans('admin/product.cancel') }}</button>
        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/delete" class="btn btn-danger">{{ trans('admin/product.delete') }}</a>
    @endcomponent
@endsection
