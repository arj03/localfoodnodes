@extends('account.layout')

@section('title', 'Create product')

@section('content')
    @include('account.page-header')

    <div class="row">
        <ul class="product-quick-links col-12 col-xl-8">
            <li class="active"><span><i class="fa fa-cube"></i> {{ trans('admin/producer.product') }}</span></li>
            <li><span><i class="fa fa-sun-o"></i> {{ trans('admin/producer.production') }}</span></li>
            <li><span><i class="fa fa-calendar"></i> {{ trans('admin/producer.delivery_dates') }}</span></li>
            <li><span><i class="fa fa-cubes"></i> {{ trans('admin/producer.variants') }}</span></li>
        </ul>
    </div>


    <form action="/account/producer/{{ $producer->id }}/product/insert" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <input type="hidden" name="type" value="basic" />
        @include('account.product.product.product-form')

        @component('account.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/product.save_product') }}</button>
        @endcomponent
    </form>

@endsection
