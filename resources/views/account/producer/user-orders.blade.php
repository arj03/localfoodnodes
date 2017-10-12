@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $orderItems->first()->getUser()['name'] }}</div>
                <div class="card-body order-header">{{ $orderItems->first()->getUser()['email'] }}</div>
                <div class="card-body">
                    <div class="table-responsive">
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
                                            <a href="/account/producer/{{ $producer->id }}/orders/product/{{ $orderItem->product['id'] }}">
                                                {{ $orderItem->getName() }}
                                            </a>
                                        </td>
                                        <td>{{ $orderItem->user['name'] }}</td>
                                        <td class="text-right">{{ $orderItem->orderDateItemLink()->quantity }}</td>
                                        <td>{{ $orderItem->node['name'] }}</td>
                                        <td>
                                            @if ($orderItem->orderDateItemLink()->getDate())
                                                {{ $orderItem->orderDateItemLink()->getDate()->date('Y-m-d') }}
                                            @endif
                                        </td>
                                        <td class="text-right">{!! $orderItem->orderDateItemLink()->getPriceWithUnit() !!}</td>
                                        <td class="text-right"><span class="{{ $orderItem->getCurrentStatus()->getHtmlClass() }}">{{ $orderItem->getCurrentStatus() }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
