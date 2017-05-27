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
                                    <th class="text-right">{{ trans('admin/product.production') }}</th>
                                    <th class="text-right">{{ trans('admin/product.amount_per_package') }}</th>
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
                                        <td class="text-right">
                                            @if ($product->variants_individual_quantity)
                                                {{ $variant->quantity }}
                                            @else
                                                {{ $variant->getProductionQuantity() }}
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $variant->package_amount }} {{ $product->package_unit }}</td>
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
                <div class="card-header">{{ trans('admin/product.variant_settings') }}</div>
                <div class="card-block">
                    <form action="/account/producer/{{ $product->producer()->id }}/product/{{ $product->id }}/variants/settings" method="post">
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

                        <fieldset class="form-group">
                            <legend>Quantity</legend>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="variants_individual_quantity" value="0" {{ $product->variants_individual_quantity === 0 ? 'checked' : '' }}>
                                    Shared
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="variants_individual_quantity" value="1" {{ $product->variants_individual_quantity === 1 ? 'checked' : '' }}>
                                    Individual
                                </label>
                            </div>
                          </fieldset>

                        <button type="submit" class="btn btn-success">{{ trans('admin/product.save') }}</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">{{ trans('admin/product.variant_settings') }}</div>
                <div class="card-block">
                    Info om shared quanttiy
                </div>
            </div>
        </div>
    </div>
@endsection
