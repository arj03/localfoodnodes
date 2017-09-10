@extends('public.layout')

@section('title', trans('public/checkout.title'))

@section('content')
    <div class="header">
        <div class="top">
            <div class="d-flex justify-content-between">
                <div>
                    <h1>{{ trans('public/checkout.title') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-xl-8">
                @if ($user->cartDates()->count() > 0)
                    @foreach ($user->cartDates() as $cartDate)
                        <div class="card">
                            <div class="card-header">{{ trans_choice('public/checkout.delivery', 1) }} {{ $cartDate->date('Y-m-d') }}</div>
                            <div class="card-block cart-items-block hidden-md-down">
                                <div class="cart-items-block-header row">
                                    <div class="col-8">{{ trans('public/checkout.product') }}</div>
                                    <div class="col-2 text-right">{{ trans('public/checkout.quantity') }}</div>
                                    <div class="col-2 text-right">{{ trans('public/checkout.total') }}</div>
                                </div>
                            </div>
                            <div class="card-block">
                                @foreach ($cartDate->cartDateItemLinks() as $cartDateItemLink)
                                    @include('public.checkout.checkout-item')
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <div class="card-header">{{ trans('public/checkout.cart') }}</div>
                        <div class="card-block">
                            {{ trans('public/checkout.cart_empty') }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-12 col-xl-4">
                @if ($user->cartDates()->count() > 0)
                    <div class="card summary">
                        <div class="card-header">{{ trans('public/checkout.summary') }}</div>
                        <div class="card-block red">
                            <b class="d-flex justify-content-between">
                                <span>{{ trans('public/checkout.products') }}</span>
                                <span>{{ trans('public/checkout.quantity') }}</span>
                            </b>
                            <ul>
                                @foreach ($user->cartItems()->unique('product_id') as $cartItem)
                                    <li class="d-flex justify-content-between">
                                        <span>{{ $cartItem->product['name'] }}</span>
                                        <span>{{ $cartItem->getQuantity() }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <b class="d-flex justify-content-between">
                                <span>{{ trans('public/checkout.producers') }}</span>
                                <span>{{ trans_choice('public/checkout.delivery', 2) }}</span>
                            </b>
                            <ul>
                                @foreach ($user->cartItems()->unique('producer_id') as $cartItem)
                                    <li class="d-flex justify-content-between">
                                        <span>{{ $cartItem->producer['name'] }}</span>
                                        <span>{{ $cartItem->cartDateItemLinks()->count() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-block">
                            @if ($user->isMember())
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                <button id="send-order-action" class="btn btn-success w-100">{{ trans('public/checkout.send_order') }}</button>
                                <script>
                                    $('#send-order-action').on('click', function() {
                                        $(this).attr('disabled', true);
                                        $.ajax({
                                            type: 'get',
                                            url: '/checkout/order/create',
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                window.location = '/checkout';
                                            },
                                            success: function(data, textStatus, jqXHR) {
                                                window.location = '/account/user/pickups';
                                            }
                                        });
                                    });
                                </script>
                            @else
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                <a href="/membership" class="btn w-100">{{ trans('public/checkout.become_member') }}</a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">{{ trans('public/checkout.how_it_works') }}</div>
                    <div class="card-block">
                        {{ trans('public/checkout.after_placing_order') }}
                        <ul class="info-list">
                            <li><span class="info-count">1</span><p>{!! trans('public/checkout.recieve_instruction') !!}</p></li>
                            <li><span class="info-count">2</span><p>{{ trans('public/checkout.pick_it_up') }}</p></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('.quantity-input').on('keyup', _.debounce(function() {
                var $form = $(this).closest('form');
                var value = $(this).val();

                if ($.isNumeric(value) && parseInt(value) > 0) {
                    $form.submit();
                }
            }, 500));
        });
    </script>
@endsection
