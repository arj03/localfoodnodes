@extends('account.layout')

@section('title', 'Product variants')

@section('content')
    @include('account.page-header')
    <form action="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/variant/insert" method="post">
        @include('account.product.variants.variant-form')

        @component('account.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/product.create_variant') }}</button>
        @endcomponent
    </form>
@endsection
