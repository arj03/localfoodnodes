@extends('account.layout')

@section('title', 'Edit product')

@section('content')
    @include('account.page-header')

    @include('account.product.shared.quick-links')

    <form action="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="producer_id" value="{{ $producer->id }}">
        <input type="hidden" name="type" value="{{ $product->type }}" />

        @include('account.product.product.product-form')

        @component('account.form-control-bar')
            <button type="submit" name="update" class="btn btn-success">{{ trans('admin/product.save_product') }}</button>
            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/delete/confirm" class="btn btn-danger">{{ trans('admin/product.delete_product') }}</a>
        @endcomponent
    </form>
@endsection
