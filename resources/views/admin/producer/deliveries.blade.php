@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="row">
        <div class="col-12">
            @if ($producer->orderDates()->count() > 0)
                @foreach ($producer->orderDates() as $orderDate)
                    <div class="card">
                        <div class="card-header">{{ trans('admin/producer.delivery') }} {{ $orderDate->date('Y-m-d') }}</div>
                        <div class="card-block">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/producer.order') }}</th>
                                        <th>{{ trans('admin/producer.product') }}</th>
                                        <th>{{ trans('admin/producer.customer') }}</th>
                                        <th class="text-right">{{ trans('admin/producer.quantity') }}</th>
                                        <th>{{ trans('admin/producer.node') }}</th>
                                        <th>{{ trans('admin/producer.total_price') }}</th>
                                        <th class="text-right">{{ trans('admin/producer.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderDate->orderDateItemLinks(null, $producer->id) as $orderDateItemLink)
                                        <tr>
                                            <td>
                                                <a href="/account/producer/{{ $producer->id }}/order/{{ $orderDateItemLink->id }}">{{ strtoupper($orderDateItemLink->ref) }}</a>
                                            </td>
                                            <td>
                                                <a href="/account/producer/{{ $producer->id }}/product/{{ $orderDateItemLink->getItem()->product['id'] }}/edit">
                                                    {{ $orderDateItemLink->getItem()->getName() }}
                                                </a>
                                            </td>
                                            <td>{{ $orderDateItemLink->getItem()->user['name'] }}</td>
                                            <td class="text-right">{{ $orderDateItemLink->quantity }}</td>
                                            <td>{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                            <td>{!! $orderDateItemLink->getPriceWithUnit() !!}</td>
                                            <td class="text-right"><span class="{{ $orderDateItemLink->getItem()->getCurrentStatus()->getHtmlClass() }}">{{ $orderDateItemLink->getItem()->getCurrentStatus() }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <div class="card-header">{{ trans('admin/producer.deliveries') }}</div>
                    <div class="card-block">
                        {{ trans('admin/producer.no_upcoming_deliveries') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
