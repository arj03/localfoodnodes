@extends('admin.layout')

@section('title', 'Product variants')

@section('content')
    @include('admin.page-header')

    @include('admin.product.shared.quick-links')

    <form action="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/variant/{{ $variant->id }}/update" method="post">
        @include('admin.product.variants.variant-form')

        @component('admin.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/product.save_variant') }}</button>
            <a class="btn btn-danger" href="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/variant/{{ $variant->id }}/delete">{{ trans('admin/product.delete_variant') }}</a>
        @endcomponent
    </form>
@endsection
