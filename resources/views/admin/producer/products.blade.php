@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="card">
        <div class="card-header">Products</div>
        <div class="card-block">
            @if ($products->count() > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('admin/producer.product') }}</th>
                            <th>{{ trans('admin/producer.production') }}</th>
                            <th>{{ trans('admin/producer.deliveries') }}</th>
                            <th>{{ trans('admin/producer.variants') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products->sortBy('name') as $product)
                            <tr>
                                <td>
                                    <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/edit">{{ $product->name }}</a>
                                    @if ($product->isVisible() !== true)
                                        <i class="fa fa-warning" title="{{ $product->isVisible() }}"></i>
                                    @endif
                                </td>
                                <!-- Production -->
                                <td>
                                    @if ($product->productions()->count() > 0)
                                        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production">{{ trans('admin/producer.edit_production') }}</a>
                                    @else
                                        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production">{{ trans('admin/producer.create_production') }}</a>
                                    @endif
                                </td>
                                <!-- Deliveries -->
                                <td>
                                    @if ($product->deliveryLinks()->count() > 0)
                                        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/deliveries">{{ trans('admin/producer.edit_delivery_dates') }}</a>
                                    @else
                                        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/deliveries">{{ trans('admin/producer.select_delivery_dates') }}</a>
                                    @endif
                                </td>
                                <!-- Variants -->
                                <td>
                                    @if ($product->variants()->count() > 0)
                                        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/variants">{{ $product->variantsAsString() }}</a>
                                    @else
                                        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/variants">{{ trans('admin/producer.create_variants') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                {{ trans('admin/producer.no_products') }}
            @endif
        </div>
        <div class="card-footer">
            <a href="/account/producer/{{ $producer->id }}/product/create">{{ trans('admin/producer.create_product') }}</a>
        </div>
    </div>
@endsection
