@extends('admin.layout')

@section('title', 'Product deliveries')

@section('content')
    @include('admin.page-header')

    @include('admin.product.shared.quick-links')

    <form action="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/deliveries/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="producer_id" value="{{ $producer->id }}">

        <div class="row">
            <div class="col-12 col-xl-8">
                @if ($nodes->count() > 0)
                    @include('admin.product.delivery.calendar', ['nodes' => $nodes])
                @else
                    <div class="card">
                        <div class="card-header">{{ trans('admin/product.delivery_dates') }}</div>
                        <div class="card-block">
                            <p>{{ trans('admin/product.delivery_dates_no_nodes') }}</p>
                            <a class="btn btn-success" href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/deliveries">{{ trans('admin/product.reload_page') }}</a>
                        </div>
                    </div>

                    <!-- Nodes -->
                    @include('admin.producer.nodes')
                @endif
            </div>

            <div class="col-12 col-xl-4">
                @include('admin.product.product.how-does-it-work')
            </div>
        </div>

        @component('admin.form-control-bar')
            <button type="submit" name="update" class="btn btn-success">{{ trans('admin/product.update_deliveries') }}</button>
        @endcomponent
    </form>

    <!-- Select and deselect all delivery dates -->
    <script>
        jQuery(document).ready(function() {
            $('.select-all-dates-action').on('click', function(event) {
                var month = $(this).closest('.month');
                var checkAll = month.find('input:checked').length < month.find('input[type=checkbox]').length;
                month.find('input').prop('checked', checkAll);
            });
        });
    </script>
@endsection
