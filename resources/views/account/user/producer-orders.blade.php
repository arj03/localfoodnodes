@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    <div class="row">
        <div class="col-12">
            @if ($orderDateItemLinks->count() > 0)
                <div class="card">
                    <div class="card-header">{{ $orderDateItemLinks->first()->getItem()->producer['name'] }}</div>
                    <div class="card-body order-header">{{ $orderDateItemLinks->first()->getItem()->producer['email'] }}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ trans('admin/user.product') }}</th>
                                        <th class="text-right">{{ trans('admin/user.quantity') }}</th>
                                        <th>{{ trans('admin/user.node') }}</th>
                                        <th>{{ trans('admin/user.pickup') }}</th>
                                        <th class="text-right">{{ trans('admin/user.total') }}</th>
                                        <th class="text-right">{{ trans('admin/user.status') }}</th>
                                    </tr>
                                </thead>
                                @foreach ($orderDateItemLinks as $orderDateItemLink)
                                    <tr>
                                        <td><a href="/account/user/order/{{ $orderDateItemLink->id }}">{{ $orderDateItemLink->ref }}</a></td>
                                        <td>{{ $orderDateItemLink->getItem()->getName() }}</td>
                                        <td class="text-right">{{ $orderDateItemLink->quantity }}</td>
                                        <td>{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                        <td>
                                            @if ($orderDateItemLink->getDate())
                                                {{ $orderDateItemLink->getDate()->date('Y-m-d') }}
                                            @endif
                                        </td>
                                        <td class="text-right">{!! $orderDateItemLink->getPriceWithUnit() !!}</td>
                                        <td class="text-right"><span class="{{ $orderDateItemLink->getItem()->getCurrentStatus()->getHtmlClass() }}">{{ $orderDateItemLink->getItem()->getCurrentStatus() }}</span></td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">{{ trans('admin/user.orders') }}</div>
                    <div class="card-body">
                        {{ trans('admin/user.no_orders') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
