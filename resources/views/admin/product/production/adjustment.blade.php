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
        <form action="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production/adjustment/update" method="post">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 col-xl-8">
                    <div class="row">
                        @foreach ($dates as $firstDateOfMonth => $dates)
                            <div class="col-12 col-xl-6 card-deck">
                                <div class="card">
                                    <div class="card-header">{{ date('F Y', strtotime($firstDateOfMonth)) }}</div>
                                    <div class="card-block">
                                        @foreach($dates as $date)
                                            <div class="form-group">
                                                <label for="{{ $date->format('W') }}">{{ trans('admin/product.week') }} {{ $date->format('W') }}</label>
                                                <input type="number" class="form-control" name="quantity[{{ $date->format('Y') }}][{{ $date->format('W') }}]" id="{{ $date->format('W') }}" placeholder="{{ trans('admin/product.week') }} {{ $date->format('W') }}" value="{{ $product->productionAdjustmentQuantity($date->format('Y'), $date->format('W')) }}">
                                                <div class="text-muted">
                                                    {{ date('Y-m-d', strtotime('last monday', $date->getTimestamp())) }} -
                                                    {{ date('Y-m-d', strtotime('next sunday', $date->getTimestamp())) }}
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

            @component('admin.form-control-bar')
                <button type="submit" name="update" class="btn btn-success">{{ trans('admin/product.update_production') }}</button>
            @endcomponent
        </form>
    @endif
@endsection
