@extends('admin.layout')

@section('title', 'Product variants')

@section('content')
    @include('admin.page-header')
    <form action="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/variant/insert" method="post">
        @include('admin.product.variants.variant-form')

        @component('admin.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/product.create_variant') }}</button>
        @endcomponent
    </form>
@endsection
