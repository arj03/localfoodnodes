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
                                    <div class="row cart-item">
                                        <div class="col-sm-2 hidden-xs-down">
                                            @if ($cartDateItemLink->getItem()->getProduct() && $cartDateItemLink->getItem()->getProduct()->images()->count() > 0)
                                                <img src="{{ $cartDateItemLink->getItem()->getProduct()->images()->first()->url('medium') }}">
                                            @else
                                                <img src="/images/product-image-placeholder.jpg">
                                            @endif
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <h3>{{ $cartDateItemLink->getItem()->getName() }}</h3>
                                            <div>
                                                <a href="{{ $cartDateItemLink->getItem()->getProducer()->permalink()->url }}">
                                                    {{ $cartDateItemLink->getItem()->producer['name'] }}
                                                </a>
                                            </div>
                                            <div>
                                                <a href="{{ $cartDateItemLink->getItem()->getNode()->permalink()->url }}">
                                                    {{ $cartDateItemLink->getItem()->node['name'] }}
                                                </a>
                                            </div>
                                            @if ($cartDateItemLink->getItem()->message)
                                                <p class="text-muted">{{ trans('public/checkout.message') }}: {{ $cartDateItemLink->getItem()->message }}</p>
                                            @endif
                                        </div>

                                        <div class="col-3 col-sm-2 quantity-column">
                                            <form action="/checkout/item/{{ $cartDateItemLink->id }}/update" method="post">
                                                {{ csrf_field() }}
                                                <div class="input-group">
                                                   <input type="number" min="0" class="form-control quantity-input" name="quantity" value="{{ $cartDateItemLink->quantity }}" placeholder="Qty">
                                                </div>
                                                <a href="/checkout/item/{{ $cartDateItemLink->id }}/remove">Remove</a>
                                            </form>
                                        </div>

                                        <div class="col-4 col-sm-2 mt-1">
                                            <b>{{ $cartDateItemLink->getPrice() }} {{ $cartDateItemLink->getItem()->producer['currency'] }}</b>
                                        </div>
                                    </div>
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
                                    <li>
                                        {{ $cartItem->product['name'] }} - {{ $cartItem->cartDateItemLinks()->count() }} {{ trans('public/checkout.deliveries') }}
                                    </li>
                                @endforeach
                            </ul>

                            <b>{{ trans('public/checkout.producers') }}</b>
                            <ul>
                                @foreach ($user->cartItems()->unique('producer_id') as $cartItem)
                                    <lI>
                                        <a href="{{ $cartItem->getProducer()->permalink()->url }}">
                                            {{ $cartItem->producer['name'] }}
                                        </a>
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
