@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="card">
        <div class="card-header">{{ $node['name'] }} - {{ $orderDate->date('Y-m-d') }}</div>
        <div class="card-block">
            @foreach ($orderItemsGroupedByUserId as $userId => $orderDateItemLinks)
                <div class="card">
                    <div class="card-header">{{ $orderDateItemLinks->first()->getItem()->user['name'] }}</div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Produkt</th>
                                    <th>Antal</th>
                                    <th>Summa</th>
                                </tr>
                                @foreach ($orderDateItemLinks as $orderDateItemLink)
                                    <tr>
                                        <td>{{ $orderDateItemLink->getItem()->product['name'] }}</td>
                                        <td>{{ $orderDateItemLink->quantity }}</td>
                                        <td>{!! $orderDateItemLink->getPriceWithUnit() !!}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <b>
                            Total:
                            {{
                                $orderDateItemLinks->sum(function($orderDateItemLink) {
                                    if ($orderDateItemLink->getItem()->variant) {
                                        $price = $orderDateItemLink->getItem()->variant['price'];
                                    } else {
                                        $price = $orderDateItemLink->getItem()->product['price'];
                                    }

                                    return $price * $orderDateItemLink->quantity;
                                })
                            }} {{ $orderDateItemLinks->first()->getItem()->producer['currency'] }}
                        </b>
                    </div>
                </div>
            @endforeach

            <button class="btn btn-success" onClick="window.print(); return false">{{ trans('admin/producer.print') }}</button>
        </div>
    </div>
@endsection
