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
            <div class="col-12 col-md-8">
                @if ($user->cartDates()->count() > 0)
                    @foreach ($user->cartDates() as $cartDate)
                        <div class="card">
                            <div class="card-header">{{ $cartDate->date('Y-m-d') }}</div>
                            <div class="card-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('public/checkout.product') }}</th>
                                            <th>{{ trans('public/checkout.quantity') }}</th>
                                            <th>{{ trans('public/checkout.producer') }}</th>
                                            <th>{{ trans('public/checkout.node') }}</th>
                                            <th class="text-right">{{ trans('public/checkout.price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartDate->cartDateItemLinks() as $cartDateItemLink)
                                            <tr>
                                                <td>
                                                    {{ $cartDateItemLink->getItem()->getName() }}
                                                </td>
                                                <td class="quantity-column">
                                                    <form action="/checkout/item/{{ $cartDateItemLink->id }}/update" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="input-group">
                                                           <input type="number" min="0" class="form-control" name="quantity" value="{{ $cartDateItemLink->quantity }}" placeholder="Quantity">

                                                           <button type="submit" class="input-group-addon">
                                                               <i class="fa fa-refresh"></i>
                                                           </button>
                                                           <a class="input-group-addon" href="/checkout/item/{{ $cartDateItemLink->id }}/remove">
                                                               <i class="fa fa-trash"></i>
                                                           </a>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td>{{ $cartDateItemLink->getItem()->producer['name'] }}</td>
                                                <td>{{ $cartDateItemLink->getItem()->node['name'] }}</td>
                                                <td class="text-right">{{ $cartDateItemLink->getPrice() }} {{ $cartDateItemLink->getItem()->producer['currency'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

            <div class="col-12 col-md-4">
                @if ($user->cartDates()->count() > 0)
                    <div class="card">
                        <div class="card-header">{{ trans('public/checkout.summary') }}</div>
                        <div class="card-block">
                            <table class="table">
                                <tr><td>Items: {{ $user->cartItems()->count() }}</td></tr>
                                <tr><td>Pickups: {{ $user->cartDates()->count() }}</td></tr>
                            </table>
                        </div>
                        <div class="card-footer">
                            @if ($user->isMember())
                                <a href="/checkout/order/create" class="btn btn-success w-100">{{ trans('public/checkout.send_order') }}</a>
                            @else
                                <a href="/account/user/membership" class="btn btn-success w-100">{{ trans('public/checkout.become_member') }}</a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">{{ trans('public/checkout.how_it_works') }}</div>
                    <div class="card-block">
                        {{ trans('public/checkout.after_placing_order') }}
                        <ul class="info-list">
                            <li><span class="info-count">1</span><p>{{ trans('public/checkout.recieve_instruction') }}</p></li>
                            <li><span class="info-count">2</span><p>{{ trans('public/checkout.notified') }}</p></li>
                            <li><span class="info-count">3</span><p>{{ trans('public/checkout.pick_it_up') }}</p></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
