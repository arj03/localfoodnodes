@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    <div class="card">
        <div class="card-header">{{ $node['name'] }} - {{ $orderDate->date('Y-m-d') }}</div>
        <div class="card-body">
            @foreach ($orderItemsGroupedByUserId as $userId => $orderDateItemLinks)
                <div class="card">
                    <div class="card-header">{{ $orderDateItemLinks->first()->getItem()->user['name'] }}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Produkt</th>
                                    <th></th>
                                    <th>Antal</th>
                                    <th>Summa</th>
                                </tr>
                                @foreach ($orderDateItemLinks as $orderDateItemLink)
                                    <tr>
                                        <td>
                                            {{ $orderDateItemLink->getItem()->product['name'] }}
                                            @if ($orderDateItemLink->getItem()->variant_id)
                                                - {{ $orderDateItemLink->getItem()->variant['name'] }}
                                            @endif
                                        </td>
                                        <td>{{ $orderDateItemLink->getItem()->message }}</td>
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
