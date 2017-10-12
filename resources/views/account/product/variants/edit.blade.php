@extends('account.layout')

@section('title', 'Product variants')

@section('content')
    @include('account.product.shared.quick-links')

    <form action="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/variant/{{ $variant->id }}/update" method="post">
        @include('account.product.variants.variant-form')

        @component('account.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/product.save_variant') }}</button>
            <a class="btn btn-danger" href="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/variant/{{ $variant->id }}/delete">{{ trans('admin/product.delete_variant') }}</a>
        @endcomponent
    </form>
@endsection
