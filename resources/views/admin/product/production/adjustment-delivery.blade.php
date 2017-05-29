@extends('admin.layout')

@section('title', 'Product production adjustment')

@section('content')
    @include('admin.page-header')

    @include('admin.product.shared.quick-links')

    @if ($product->productionType !== 'weekly')
        <div class="card">
            <div class="card-header">{{ trans('admin/product.adjust_production_quantity_per_week') }}</div>
            <div class="card-block">
                This feature is only available for weekly produced products.
            </div>
        </div>
    @else
        <form action="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production/adjustment/delivery/update" method="post">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 col-xl-8">
                    @foreach ($producer->nodeLinks() as $nodeLink)
                        <div class="card">
                            <div class="card-header">{{ $nodeLink->getNode()->name }}</div>
                            <div class="card-block">
                                @foreach ($product->deliveryLinks($nodeLink->getNode()->id) as $deliveryLink)
                                    <div class="form-group">
                                        <label>{{ trans('admin/product.delivery') }} {{ $deliveryLink->date('Y-m-d') }}</label>
                                        <input type="number" class="form-control" name="quantity[{{ $nodeLink->getNode()->id }}][{{ $deliveryLink->date('Y-m-d') }}]" placeholder="{{ $product->getProductionQuantity() }} (default production)" value="{{ $product->productionDeliveryAdjustmentQuantity($deliveryLink->date) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @component('admin.form-control-bar')
                <button type="submit" name="update" class="btn btn-success">{{ trans('admin/product.update_production') }}</button>
            @endcomponent
        </form>
    @endif
@endsection
