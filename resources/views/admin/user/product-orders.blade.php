@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $orderDateItemLinks->first()->getItem()->product['name'] }}</div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/producer.order') }}</th>
                                    <th>{{ trans('admin/producer.product') }}</th>
                                    <th class="text-right">{{ trans('admin/producer.quantity') }}</th>
                                    <th>{{ trans('admin/producer.node') }}</th>
                                    <th>{{ trans('admin/producer.delivery') }}</th>
                                    <th class="text-right">{{ trans('admin/producer.total_price') }}</th>
                                    <th class="text-right">{{ trans('admin/producer.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderDateItemLinks as $orderDateItemLink)
                                    <tr>
                                        <td>
                                            <a href="/account/producer/{{ $orderDateItemLink->producer_id }}/order/{{ $orderDateItemLink->getItem()->product_id }}">{{ $orderDateItemLink->ref }}</a>
                                        </td>
                                        <td>{{ $orderDateItemLink->getItem()->getName() }}</td>
                                        <td class="text-right">{{ $orderDateItemLink->quantity }}</td>
                                        <td>{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                        <td>
                                            @if ($orderDateItemLink->getDate())
                                                {{ $orderDateItemLink->getDate()->date('Y-m-d') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">{!! $orderDateItemLink->getPriceWithUnit() !!}</td>
                                        <td class="text-right"><span class="{{ $orderDateItemLink->getItem()->getCurrentStatus()->getHtmlClass() }}">{{ $orderDateItemLink->getItem()->getCurrentStatus() }}</span></td>
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
