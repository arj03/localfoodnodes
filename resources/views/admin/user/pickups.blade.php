@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="row">
        <div class="col-12">
            @if ($user->orderDates()->count() > 0)
                @foreach ($user->orderDates() as $orderDate)
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <span>{{ $orderDate->date('Y-m-d') }}</span>
                        </div>
                        <div class="card-block">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/user.product') }}</th>
                                        <th class="text-right">{{ trans('admin/user.quantity') }}</th>
                                        <th>{{ trans('admin/user.producer') }}</th>
                                        <th>{{ trans('admin/user.node') }}</th>
                                        <th class="text-right">{{ trans('admin/user.price') }}</th>
                                    </tr>
                                </thead>
                                @foreach ($orderDate->orderDateItemLinks() as $orderDateItemLink)
                                    <tr>
                                        <td>
                                            <a href="/account/user/order/{{ $orderDateItemLink->id }}">
                                                {{ $orderDateItemLink->getItem()->getName() }}
                                            </a>
                                        </td>
                                        <td class="text-right">{{ $orderDateItemLink->quantity }}</td>
                                        <td>{{ $orderDateItemLink->getItem()->producer['name'] }}</td>
                                        <td>{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                        <td class="text-right">{{ $orderDateItemLink->getPrice() }} {{ $orderDateItemLink->getItem()->producer['currency'] }}</td>

                                    </tr>
                                @endforeach
                            </table>
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
