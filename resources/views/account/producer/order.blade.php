@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.order') }} #{{ $orderDateItemLink->ref }}</div>
                <div class="card-block d-flex justify-content-between">
                    <ul>
                        <li><b>{{ $orderItem->user['name'] }}</b></li>
                        <li><a href="mailto:{{ $orderItem->user['email'] }}">{{ $orderItem->user['email'] }}</a></li>
                        <li>{{ $orderItem->user['address'] }}</li>
                        <li>{{ $orderItem->user['zip'] }} {{ $orderItem->user['city'] }}</li>
                    </ul>

                    <ul class="text-right">
                        <li><b>{{ $orderItem->node['name'] }}</b></li>
                        <li><a href="mailto:{{ $orderItem->node['email'] }}">{{ $orderItem->node['email'] }}</a></li>
                        <li>{{ $orderItem->node['address'] }}</li>
                        <li>{{ $orderItem->node['zip'] }} {{ $orderItem->node['city'] }}</li>
                    </ul>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/user.product') }}</th>
                                    <th>{{ trans('admin/user.quantity') }}</th>
                                    <th>{{ trans('admin/user.price') }}</th>
                                    <th>{{ trans('admin/user.item_total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $orderItem->getName() }}
                                    </td>
                                    <td>{{ $orderDateItemLink->quantity }}</td>
                                    <td>{{ $orderItem->getPriceWithUnit() }}</td>
                                    <td>{!! $orderDateItemLink->getPriceWithUnit() !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p>{{ $orderItem->message }}</p>
                </div>
                <div class="card-footer">
                    @foreach ($orderItem->allAvailableOrderStatuses() as $orderStatus)
                        @if ($orderStatus->active)
                            <span class="{{ $orderStatus->getHtmlClass() }}">{{ $orderStatus }}</span>
                        @else
                            <a href="/account/producer/{{ $producer->id }}/order/{{ $orderItem->id }}/status/{{ $orderStatus->key }}" class="{{ $orderStatus->getHtmlClass() }}">{{ $orderStatus }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
