@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.order') }} #{{ $orderDateItemLink->ref }}</div>
                <div class="card-block">
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
                            <ul class="text-right hidden-md-down">
                                <li><b>{{ $orderItem->node['name'] }}</b></li>
                                <li><a href="mailto:{{ $orderItem->node['email'] }}">{{ $orderItem->node['email'] }}</a></li>
                                <li>{{ $orderItem->node['address'] }}</li>
                                <li>{{ $orderItem->node['zip'] }} {{ $orderItem->node['city'] }}</li>
                            </ul>
                            <!-- Left align -->
                            <ul class="hidden-lg-up">
                                <li><b>{{ $orderItem->node['name'] }}</b></li>
                                <li><a href="mailto:{{ $orderItem->node['email'] }}">{{ $orderItem->node['email'] }}</a></li>
                                <li>{{ $orderItem->node['address'] }}</li>
                                <li>{{ $orderItem->node['zip'] }} {{ $orderItem->node['city'] }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-block">
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
                                <td>{{ $orderDateItemLink->getDate()->date('Y-m-d') }}</td>
                                <td>
                                    {{ $orderItem->product['name'] }}
                                    @if ($orderItem->variant)
                                        - {{ $orderItem->variant['name'] }}
                                    @endif
                                </td>
                                <td>{{ $orderDateItemLink->quantity }}</td>
                                <td>{{ $orderItem->getPriceWithUnit() }}</td>
                                <td>{!! $orderDateItemLink->getPriceWithUnit() !!}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>{{ $orderItem->message }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
