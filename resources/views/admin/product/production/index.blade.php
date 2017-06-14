@extends('admin.layout')

@section('title', trans('admin/product.title'))

@section('content')
    @include('admin.page-header')

    @include('admin.product.shared.quick-links')

    <form action="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card">
                    <div class="card-header">{{ trans('admin/product.production_type_header') }}</div>
                    <div class="card-block">
                        <ul class="list">
                            <li>
                                <b class="d-block">{{ trans('admin/product.recurring_products_weekly') }}</b>
                                {!! trans('admin/product.recurring_products_info') !!}
                            </li>
                            <li class="mt-3">
                                <b class="d-block">{{ trans('admin/product.occasional_products') }}</b>
                                {!! trans('admin/product.occasional_products_info') !!}
                            </li>
                            <li class="mt-3">
                                <b class="d-block">{{ trans('admin/product.csa_products') }}</b>
                                {!! trans('admin/product.csa_products_info') !!}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header toggle">
                        {{ trans('admin/product.production') }}
                        <i class="fa fa-chevron-up toggle"></i>
                    </div>
                    <div class="card-block">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>
                                        {{ trans('admin/product.select_production_type') }}
                                        @include('admin.field-error', ['field' => 'production_type'])
                                    </label>
                                    <select name="production_type" id="product-type" class="form-control">
                                        <option value=''>{{ trans('admin/product.select_product_type') }}</option>
                                        @if (Request::old('production_type'))
                                            <option value="weekly" {{ Request::old('production_type') === 'weekly' ? 'selected' : '' }}>
                                                {{ trans('admin/product.recurring_products_weekly') }}
                                            </option>
                                            <option value="occasional" {{ Request::old('production_type') === 'occasional' ? 'selected' : '' }}>
                                                {{ trans('admin/product.occasional_products') }}
                                            </option>
                                            <option value="csa" {{ Request::old('production_type') === 'csa' ? 'selected' : '' }}>
                                                {{ trans('admin/product.csa_products') }}
                                            </option>
                                        @else
                                            <option value="weekly" {{ $product->productionType === 'weekly' ? 'selected' : '' }}>
                                                {{ trans('admin/product.recurring_products_weekly') }}
                                            </option>
                                            <option value="occasional" {{ $product->productionType === 'occasional' ? 'selected' : '' }}>
                                                {{ trans('admin/product.occasional_products') }}
                                            </option>
                                            <option value="csa" {{ $product->productionType === 'csa' ? 'selected' : '' }}>
                                                {{ trans('admin/product.csa_products') }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <!-- CSA -->
                        <div class="card type-card" id="type-card-csa">
                            <div class="card-header">{{ trans('admin/product.csa_products') }}</div>
                            <div class="card-block">
                                <div class="form-group col-6">
                                    <label class="form-control-label" for="csa_quantity">
                                        {{ trans('admin/product.enter_total_csa_subscribers') }}
                                        @include('admin.field-error', ['field' => 'csa_quantity'])
                                    </label>
                                    <input type="number" min="0" name="csa_quantity" class="form-control" id="csa_quantity" placeholder="{{ trans('admin/product.number_of_csa_subscribers') }}" value="{{ $product->productions()->first()->quantity or '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Weekly -->
                        <div class="card type-card" id="type-card-weekly">
                            <div class="card-header">{{ trans('admin/product.recurring_products_weekly') }}</div>
                            <div class="card-block">
                                <div class="form-group col-6">
                                    <label class="form-control-label" for="weekly_quantity">
                                        {{ trans('admin/product.specify_number_products') }}
                                        @include('admin.field-error', ['field' => 'weekly_quantity'])
                                    </label>
                                    <input type="number" min="0" name="weekly_quantity" class="form-control" id="weekly_quantity" placeholder="{{ trans('admin/product.number_of_products') }}" value="{{ $product->productions()->first()->quantity or '' }}">
                                </div>
                                <div class="card-block">
                                    <p>{{ trans('admin/product.weekly_adjustment') }}</p>
                                    <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production/adjustment">{{ trans('admin/product.adjust_production_quantity_per_week') }}</a>
                                </div>
                            </div>
                        </div>

                        <!-- Occasional -->
                        <div class="card type-card" id="type-card-occasional">
                            <div class="card-header">{{ trans('admin/product.occasional_products') }}</div>
                            <div class="card-block">
                                <div class="text-muted mb-3"><i class="fa fa-info-circle"></i> {{ trans('admin/product.occasional_specify_date_quantity') }}</div>
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="form-part-container">
                                            @if (Request::old('occasional_date'))
                                                @foreach (Request::old('occasional_date') as $index => $occasional_date)
                                                    <div class="{{ $index === 1 ? 'form-part-template' : '' }}">
                                                        <div class="form-group">
                                                            <label>
                                                                {{ trans('admin/product.production_date') }}
                                                                @include('admin.field-error', ['field' => 'occasional_date[' . $index . ']'])
                                                            </label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                                <input type="text" name="occasional_date[]" class="form-control picker date" placeholder="Date" value="{{ $occasional_date }}" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>
                                                                {{ trans('admin/product.quantity_available') }}
                                                                @include('admin.field-error', ['field' => 'occasional_quantity[' . $index . ']'])
                                                            </label>
                                                            <input type="number" min="0" name="occasional_quantity[]" class="form-control" placeholder="Quantity" value="{{ Request::old('occasional_quantity')[$index] }}"/>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="form-part-template">
                                                    <div class="form-group">
                                                        <label>{{ trans('admin/product.production_date') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                            <input type="text" name="occasional_date[]" class="form-control picker date" placeholder="{{ trans('admin/product.production_date') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ trans('admin/product.quantity_available') }}</label>
                                                        <input type="number" min="0" name="occasional_quantity[]" class="form-control" placeholder="{{ trans('admin/product.quantity') }}" />
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group add-date-action hidden">
                                            <a href="#">{{ trans('admin/product.add_date') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    jQuery(document).ready(function($) {
                                        $('.add-date-action').show();
                                        $('.add-date-action a').on('click', function(event) {
                                            event.preventDefault();

                                            var clone = $('.form-part-template').clone(true);
                                            clone.removeClass('form-part-template').appendTo('.form-part-container');

                                            // Reset clone and re-bind datepickers
                                            clone.find('.picker.date').removeClass('bound');
                                            clone.find('input').val('');
                                            $(document).trigger('bindDatepicker');
                                        });
                                    });
                                </script>

                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        @if ($product->productionType === 'occasional')
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('admin/product.production_date') }}</th>
                                                        <th class="text-right">{{ trans('admin/product.quantity') }}</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->productions()->sortBy('date')->all() as $production)
                                                        <tr>
                                                            <td>{{ $production->date->format('Y-m-d') }}</td>
                                                            <td class="text-right">{{ $production->quantity }}</td>
                                                            <td>
                                                                <div class="dropdown dropdown-action-component">
                                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                                        <i class="fa fa-gear"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item" href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production/{{ $production->id }}/delete">{{ trans('admin/product.delete') }}</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div> <!-- Saved production end -->
                            </div>
                        </div>
                    </div> <!-- Card block end-->
                </div>
            </div>

            <div class="col-12 col-xl-4">
                @include('admin.product.product.how-does-it-work')
            </div>
        </div>

        @component('admin.form-control-bar')
            <button type="submit" name="update" class="btn btn-success">{{ trans('admin/product.save') }}</button>
        @endcomponent
    </form>

    <!-- Toggle product type -->
    <script>
        jQuery(document).ready(function() {
            var toogleType = function(type) {
                // Reset
                $('.product-type-alert').remove();
                $('.type-card').hide();

                if (type) {
                    $('#type-card-' + type).show();
                }
            };

            $('#product-type').on('change', function(event) {
                toogleType($(this).val());
            });

            toogleType($('#product-type').val());
        });
    </script>
@endsection
