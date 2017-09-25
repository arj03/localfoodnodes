@extends('account.layout')

@section('title', 'Product production adjustment')

@section('content')
    @include('account.page-header')

    @include('account.product.shared.quick-links')

    @if ($product->productionType !== 'weekly')
        <div class="card">
            <div class="card-header">{{ trans('admin/product.adjust_production_quantity_per_week') }}</div>
            <div class="card-body">
                This feature is only available for weekly produced products.
            </div>
        </div>
    @else
        <form action="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production/adjustment/update" method="post">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 col-xl-8">
                    <div class="row">
                        @foreach ($dates as $firstDateOfMonth => $dates)
                            <div class="col-12 col-xl-6 card-deck">
                                <div class="card">
                                    <div class="card-header">{{ trans('months.' . date('F', strtotime($firstDateOfMonth))) }} {{ date('Y', strtotime($firstDateOfMonth)) }}</div>
                                    <div class="card-body">
                                        @foreach ($dates as $date)
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon col-4">{{ date('d', strtotime('monday this week', $date->getTimestamp())) }} - {{ date('d', strtotime('sunday this week', $date->getTimestamp())) }} {{ substr(trans('months.' . date('F', $date->getTimestamp())), 0, 3) }}</span>

                                                    <input type="number" class="form-control" name="quantity[{{ $date->format('Y') }}][{{ $date->format('W') }}]" id="{{ $date->format('W') }}" placeholder="{{ $product->getProductionQuantity() }}" value="{{ $product->productionAdjustmentQuantity($date->format('Y'), $date->format('W')) }}">
                                                    <span class="input-group-addon col-3">{{ trans('admin/product.products') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @component('account.form-control-bar')
                <button type="submit" name="update" class="btn btn-success">{{ trans('admin/product.update_production') }}</button>
            @endcomponent
        </form>
    @endif
@endsection
