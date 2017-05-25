@extends('public.layout')

@section('title', $product->name . ' - ' . $producer->name)

@section('content')
    <div class="header">
        <div class="top">
            <div class="d-flex justify-content-between">
                <div>
                    <h1>{{ $product->name }}</h1>
                    <a href="{{ $producer->permalink()->url }}">{{ $producer->name }}</a>
                </div>
            </div>
        </div>

        <div class="image" style="background-image: url(/images/product-header.jpg)"></div>
    </div>

    <div class="container info">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">{{ trans('public/product.product_info') }}</div>
                    <div class="card-block">
                        <div class="row">
                            @if ($product->images()->count() > 0)
                                <div class="col-12 col-lg-6">
                                    <div class="images slick-slider">
                                        @foreach ($product->images() as $image)
                                            <a data-fancybox="gallery" href="{{ $image->url('medium') }}">
                                                <img class="card-image-bottom" src="{{ $image->url('small') }}">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="col-12 col-lg-6">
                                <div class="body-text">
                                    <p>{!! $product->info !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">{{ trans('public/product.producer') }}</div>
                    <div class="card-block">
                        <div class="mb-3">
                            <a href="{{ $producer->permalink()->url }}">{{ $producer->name }}</a><br>
                            {{ $producer->address }}, {{ $producer->zip }} {{ $producer->city }}
                        </div>
                        <div class="body-text">
                            {!! $producer->info !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                @if (isset($node))
                    @include('public.node.product-order-form')
                @else
                    @include('public.producer.product-order-form')
                @endif
            </div>
        </div> <!-- end right column-->
    </div>

    <script>
        jQuery(document).ready(function() {
            var variantQuantity = function(variantId) {
                $('.variant-quantity').hide();
                $('.variant-quantity-' + variantId).show();
            };

            $('.variant').on('change', function(event) {
                variantQuantity($(this).val());
            });

            variantQuantity($('#variant').val());
        });
    </script>

    <script>
        jQuery(document).ready(function() {
            $('.select-all-dates-action').click(function() {
                var month = $(this).closest('.month');
                var checkAll = month.find('input:checked').length < month.find('input').length;
                month.find('input').prop('checked', checkAll);
            });
        });
    </script>
@endsection
