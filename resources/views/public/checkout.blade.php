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
                            <div class="card-header">{{ trans('public/checkout.delivery') }} {{ $cartDate->date('Y-m-d') }}</div>
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
                        <div class="card-block">
                            <b>{{ trans('public/checkout.products') }}</b>
                            <ul>
                                @foreach ($user->cartItems()->unique('product_id') as $cartItem)
                                    <li class="d-flex justify-content-between">
                                        <span>{{ $cartItem->product['name'] }}</span>
                                        <span>{{ $cartItem->cartDateItemLinks()->sum->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <b>{{ trans('public/checkout.producers') }}</b>
                            <ul>
                                @foreach ($user->cartItems()->unique('producer_id') as $cartItem)
                                    <li class="d-flex justify-content-between">
                                        <span>{{ $cartItem->producer['name'] }}</span>
                                        <span>{{ $cartItem->cartDateItemLinks()->count() }} {{ trans('public/checkout.deliveries') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            @if ($user->isMember())
                                <a href="/checkout/order/create" class="btn w-100">{{ trans('public/checkout.send_order') }}</a>
                            @else
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
            $('.quantity-input').on('change', function() {
                $(this).closest('form').submit();
            })
        });
    </script>
@endsection
