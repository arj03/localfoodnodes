@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="row">
        <div class="col-12">
            @if ($producer->orderItemsGroupedByUser()->count() > 0)
                @foreach ($producer->orderItemsGroupedByUser() as $userId => $orderItems)
                    <div class="card">
                        <div class="card-header">{{ $orderItems->first()->getUser()['name'] }}</div>
                        <div class="card-block order-header">{{ $orderItems->first()->getUser()['email'] }}</div>
                        <div class="card-block">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/producer.order') }}</th>
                                        <th>{{ trans('admin/producer.product') }}</th>
                                        <th>{{ trans('admin/producer.customer') }}</th>
                                        <th class="text-right">{{ trans('admin/producer.quantity') }}</th>
                                        <th>{{ trans('admin/producer.node') }}</th>
                                        <th>{{ trans('admin/producer.delivery') }}</th>
                                        <th class="text-right">{{ trans('admin/producer.total_price') }}</th>
                                        <th class="text-right">{{ trans('admin/producer.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $orderItem)
                                        <tr>
                                            <td>
                                                <a href="/account/producer/{{ $producer->id }}/order/{{ $orderItem->orderDateItemLink()->id }}">{{ $orderItem->orderDateItemLink()->ref }}</a>
                                            </td>
                                            <td>
                                                {{ $orderItem->product['name'] }}
                                                @if ($orderItem->variant)
                                                    - {{ $orderItem->variant['name'] }}
                                                @endif
                                            </td>
                                            <td>{{ $orderItem->user['name'] }}</td>
                                            <td class="text-right">{{ $orderItem->orderDateItemLink()->quantity }}</td>
                                            <td>{{ $orderItem->node['name'] }}</td>
                                            <td>{{ $orderItem->orderDateItemLink()->getDate()->date('Y-m-d') }}</td>
                                            <td class="text-right">{!! $orderItem->orderDateItemLink()->getPriceWithUnit() !!}</td>
                                            <td class="text-right"><span class="{{ $orderItem->getCurrentStatus()->getHtmlClass() }}">{{ $orderItem->getCurrentStatus() }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <div class="card-header">{{ trans('admin/producer.orders') }}</div>
                    <div class="card-block">
                        {{ trans('admin/producer.no_orders') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
