@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.order') }} #{{ $orderDateItemLink->ref }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <ul>
                                <li><b>{{ $orderItem->producer['name'] }}</b></li>
                                <li><a href="mailto:{{ $orderItem->producer['email'] }}">{{ $orderItem->getProducer()['email'] }}</a></li>
                                <li>{{ $orderItem->getProducer()['address'] }}</li>
                                <li>{{ $orderItem->getProducer()['zip'] }} {{ $orderItem->getProducer()['city'] }}</li>
                            </ul>

                            <div class="mt-3 mb-3">
                                {{ $orderItem->getProducer()['payment_info'] }}
                                {{ $orderItem->product['payment_info'] }}
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <!-- Right align -->
                            <ul class="text-right d-none d-lg-block">
                                <li><b>{{ $orderItem->node['name'] }}</b></li>
                                <li><a href="mailto:{{ $orderItem->node['email'] }}">{{ $orderItem->node['email'] }}</a></li>
                                <li>{{ $orderItem->node['address'] }}</li>
                                <li>{{ $orderItem->node['zip'] }} {{ $orderItem->node['city'] }}</li>
                            </ul>
                            <!-- Left align -->
                            <ul class="d-block d-lg-none">
                                <li><b>{{ $orderItem->node['name'] }}</b></li>
                                <li><a href="mailto:{{ $orderItem->node['email'] }}">{{ $orderItem->node['email'] }}</a></li>
                                <li>{{ $orderItem->node['address'] }}</li>
                                <li>{{ $orderItem->node['zip'] }} {{ $orderItem->node['city'] }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/user.delivery') }}</th>
                                    <th>{{ trans('admin/user.product') }}</th>
                                    <th>{{ trans('admin/user.quantity') }}</th>
                                    <th>{{ trans('admin/user.price') }}</th>
                                    <th>{{ trans('admin/user.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if ($orderDateItemLink->getDate())
                                            {{ $orderDateItemLink->getDate()->date('Y-m-d') }}
                                        @endif
                                    </td>
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
                        <span class="{{ $orderStatus->getHtmlClass() }}">{{ $orderStatus }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @component('account.form-control-bar')
        @if ($orderDateItemLink->isDeletable())
            <a href="/account/user/order/{{ $orderDateItemLink->id }}/delete" class="btn btn-danger">{{ trans('admin/user.delete_order') }}</a>
        @else
            <button class="btn btn-danger" disabled="disabled">{{ trans('admin/user.delete_order') }}</button>
        @endif
    @endcomponent
@endsection
