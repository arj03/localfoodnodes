@extends('admin.layout')

@section('title', 'Create product')

@section('content')
    @include('admin.page-header', ['title' => $producer->name . ' - Create product'])

    <form action="/account/producer/{{ $producer->id }}/product/insert" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <input type="hidden" name="type" value="basic" />
        @include('admin.product.product.product-form')

        @component('admin.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/product.save_product') }}</button>
        @endcomponent
    </form>

@endsection
