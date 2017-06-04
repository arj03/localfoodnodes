@extends('admin.layout')

@section('title', 'Product variants')

@section('content')
    @include('admin.page-header')

    @include('admin.product.shared.quick-links')

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">{{ trans('admin/product.variants') }}</div>
                <div class="card-block">
                    @if ($product->variants()->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/product.main_variant') }}</th>
                                    <th>{{ trans('admin/product.name') }}</th>
                                    <th class="text-right">{{ trans('admin/product.amount_per_package') }}</th>
                                    <th class="text-right">{{ trans('admin/product.production') }}</th>
                                    <th class="text-right">{{ trans('admin/product.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->variants() as $variant)
                                    <tr>
                                        <td>
                                            @if ($variant->main_variant)
                                                <i class="fa fa-check"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/variant/{{ $variant->id }}/edit">{{ $variant->name }}</a>
                                        </td>
                                        <td class="text-right">{{ $variant->package_amount }} {{ trans_choice('units.' . $product->package_unit, $variant->package_amount) }}</td>
                                        <td class="text-right">{{ $variant->getProductionQuantity() }}</td>
                                        <td class="text-right">{{ $variant->price }}</td>
                                        <td>
                                            <div class="dropdown dropdown-action-component">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-gear"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/variant/{{ $variant->id }}/set-main-variant">
                                                        {{ trans('admin/product.set_main_variant') }}
                                                    </a>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        {{ trans('admin/product.no_variants') }}
                    @endif
                </div>
                <div class="card-footer">
                    <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/variant/create">Create variant</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card">
                <div class="card-header">{{ trans('admin/product.product_content') }}</div>
                <div class="card-block">
                    <form action="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/set-package-unit" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="form-control-label" for="package_unit">
                                {{ trans('admin/product.product_content_specified_in') }}
                                @include('admin.field-error', ['field' => 'package_unit'])
                            </label>
                            <select class="form-control" name="package_unit" id="package-unit">
                                <option>{{ trans('admin/product.select_unit_product_content') }}</option>
                                @foreach(UnitsHelper::getVariantUnits() as $key => $label)
                                    <option value="{{ $key }}" {{ $product->package_unit === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">{{ trans('admin/product.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
