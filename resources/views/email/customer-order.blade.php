@extends('email.layout')

@section('content')
    <h1 style="margin: 0 0 20px; color: #333;">{{ trans('public/email.order_confirmation') }}</h1>
    @foreach ($orderDates as $orderDate)
        <div class="order-date" style="margin-bottom: 40px; background: #fff; box-shadow: #ccc 0 1px 8px -2px;">
            <div style="background: #d6c69b; overflow: hidden; text-transform: uppercase; color: #fff; padding: 15px; font-weight: bold;">
                <div style="float: left;">
                    {{ trans('public/email.pickup') }} {{ $orderDate->date('Y-m-d') }}
                </div>
            </div>
            @foreach ($orderDate->orderDateItemLinks($user->id) as $index => $orderDateItemLink)
                @if ($index === 0)
                    <div style="overflow: hidden; padding: 20px;">
                @else
                    <div style="overflow: hidden; padding: 20px; border-top: 1px dashed #eee;">
                @endif
                    <div style="width: 70%; float: left;">
                        <h2 style="margin: 0px;">{{ $orderDateItemLink->getItem()->getName() }}</h2>
                        {{ $orderDateItemLink->getItem()->node['name'] }}<br>
                        {{ trans('public/email.quantity') }}: {{ $orderDateItemLink->quantity }}<br>
                        <p style="color: #999;">{{ $orderDateItemLink->getItem()->producer['payment_info'] }} {{ $orderDateItemLink->getItem()->product['payment_info'] }}</p>
                    </div>
                    <div style="width: 30%; float: left;">
                        <div style="float: right; text-align: right;">
                            <b>{!! $orderDateItemLink->getPriceWithUnit() !!}</b>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <div style="text-align: center; margin-bottom: 20px;">
        <a style="background: #8fb773; color: #fff; padding: 10px; border-radius: 4px;" href="{{ app('url')->to('/account/user/pickups') }}">{{ trans('public/email.view_deliveries') }}</a>
    </div>
@endsection
