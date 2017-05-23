@extends('email.layout')

@section('content')
    <h1 style="margin: 0 0 20px; color: #333;">Incoming order</h1>
    <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <td bgcolor="#fff">
                <div style="background: #d6c69b;">
                    <div style="text-transform: uppercase; color: #fff; padding: 15px; font-weight: bold;">
                        {{ $orderItems->first()->user['name'] }}
                        - <a style="color:#fff" href="mailto: {{ $orderItems->first()->user['email'] }}">{{ $orderItems->first()->user['email'] }}</a>
                    </div>
                </div>
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td colspan="2" style="padding: 20px;">
                            <table style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th style="text-align:left; padding:10px;">Date</th>
                                        <th style="text-align:left; padding:10px;">Product</th>
                                        <th style="text-align:right; padding:10px;">Quantity</th>
                                        <th style="text-align:left; padding:10px;">Customer</th>
                                        <th style="text-align:left; padding:10px;">Node</th>
                                        <th style="text-align:right; padding:10px;">Price</th>
                                    </tr>
                                <thead>
                                <tbody>
                                    @foreach ($orderItems as $orderItem)
                                        <tr style="vertical-align: top; border-top: 1px solid #eee;">
                                            <td style="text-align:left; padding:10px;">{{ $orderItem->orderDateItemLink()->getDate()->date('Y-m-d') }}</td>
                                            <td style="text-align:left; padding:10px;">{{ $orderItem->getName() }}</td>
                                            <td style="text-align:right; padding:10px;">{{ $orderItem->orderDateItemLink()->quantity }}</td>
                                            <td style="text-align:left; padding:10px;">{{ $orderItem->user['name'] }}</td>
                                            <td style="text-align:left; padding:10px;">{{ $orderItem->node['name'] }}</td>
                                            <td style="text-align:right; padding:10px;">{{ $orderItem->orderDateItemLink()->getPrice() }} {{ $orderItem->producer['currency'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
