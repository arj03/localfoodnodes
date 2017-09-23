@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <div class="row">
        <div class="col-12">
            @if ($user->orderDates()->count() > 0)
                @foreach ($user->orderDates() as $orderDate)
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <span>{{ trans('admin/user.pickup') }} {{ $orderDate->date('Y-m-d') }}</span>
                        </div>
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('admin/user.order') }}</th>
                                            <th>{{ trans('admin/user.product') }}</th>
                                            <th class="text-right">{{ trans('admin/user.quantity') }}</th>
                                            <th>{{ trans('admin/user.producer') }}</th>
                                            <th>{{ trans('admin/user.node') }}</th>
                                            <th class="text-right">{{ trans('admin/user.price') }}</th>
                                            <th class="text-right">{{ trans('admin/user.status') }}</th>
                                        </tr>
                                    </thead>
                                    @foreach ($orderDate->orderDateItemLinks($user->id) as $orderDateItemLink)
                                        <tr>
                                            <td>
                                                <a href="/account/user/order/{{ $orderDateItemLink->id }}">
                                                    {{ $orderDateItemLink->ref }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="/account/user/orders/product/{{ $orderDateItemLink->getItem()->product_id }}">
                                                    {{ $orderDateItemLink->getItem()->getName() }}
                                                </a>
                                            </td>
                                            <td class="text-right">{{ $orderDateItemLink->quantity }}</td>
                                            <td>
                                                <a href="/account/user/orders/producer/{{ $orderDateItemLink->producer_id }}">
                                                    {{ $orderDateItemLink->getItem()->producer['name'] }}
                                                </a>
                                            </td>
                                            <td>{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                            <td class="text-right">{!! $orderDateItemLink->getPriceWithUnit() !!}</td>
                                            <td class="text-right"><span class="{{ $orderDateItemLink->getItem()->getCurrentStatus()->getHtmlClass() }}">{{ $orderDateItemLink->getItem()->getCurrentStatus()}}</span></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <div class="card-header">{{ trans('admin/user.orders') }}</div>
                    <div class="card-block">
                        {{ trans('admin/user.no_orders') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
