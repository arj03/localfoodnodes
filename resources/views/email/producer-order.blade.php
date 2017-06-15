@extends('email.layout')

@section('content')
    <div style="margin-bottom: 20px;">
        <h1 style="margin: 0px; color: #333;">{{ trans('public/email.order_from') }} {{ $orderItems->first()->user['name'] }}</h1>
        <a style="color: #333" href="mailto: {{ $orderItems->first()->user['email'] }}">{{ $orderItems->first()->user['email'] }}</a>
    </div>
    @foreach ($orderItems as $orderItem)
        <div class="order-item" style="margin-bottom: 40px; background: #fff; box-shadow: #ccc 0 1px 8px -2px;">
            <div style="background: #d6c69b; overflow: hidden; text-transform: uppercase; color: #fff; padding: 15px; font-weight: bold;">
                <div style="float: left;">
                    {{ trans('public/email.delivery') }} {{ $orderItem->orderDateItemLink()->getDate()->date('Y-m-d') }}
                </div>
            </div>
            <div style="overflow: hidden; padding: 20px 20px 40px;">
                <div style="width: 70%; float: left;">
                    <h2 style="margin: 0px;">{{ $orderItem->getName() }}</h2>
                    {{ $orderItem->node['name'] }}<br>
                    {{ trans('public/email.quantity') }}: {{ $orderItem->orderDateItemLink()->quantity }}<br>
                    <a href="{{ app('url')->to('/account/producer/' . $orderItem->producer['id'] . '/order/' . $orderItem->orderDateItemLink()->id) }}">
                        {{ trans('public/email.view_order') }} #{{ $orderItem->orderDateItemLink()->ref }}
                    </a>
                </div>
                <div style="width: 30%; float: left;">
                    <div style="float: right; text-align: right;">
                        <b>{!! $orderItem->orderDateItemLink()->getPriceWithUnit() !!}</b>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
