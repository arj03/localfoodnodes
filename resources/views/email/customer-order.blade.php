@extends('email.layout')

@section('content')
    <h1 style="margin: 0 0 20px; color: #333;">Order confirmation</h1>
    @foreach ($orderDates as $orderDate)
        <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
            <tr>
                <td bgcolor="#fff">
                    <div style="background: #d6c69b;">
                        <div style="text-transform: uppercase; color: #fff; padding: 15px; font-weight: bold;">{{ $orderDate->date('Y-m-d') }}</div>
                    </div>
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td colspan="2" style="padding: 20px;">
                                <table style="width: 100%; margin-bottom: 20px;">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left; padding:10px;">Product</th>
                                            <th style="text-align:right; padding:10px;">Quantity</th>
                                            <th style="text-align:left; padding:10px;">Producer</th>
                                            <th style="text-align:left; padding:10px;">Node</th>
                                            <th style="text-align:right; padding:10px;">Price</th>
                                        </tr>
                                    <thead>
                                    <tbody>
                                        @foreach ($orderDate->orderDateItemLinks() as $orderDateItemLink)
                                            <tr style="vertical-align: top; border-top: 1px solid #eee;">
                                                <td style="text-align:left; padding:10px;">{{ $orderDateItemLink->getItem()->getName() }}</td>
                                                <td style="text-align:right; padding:10px;">{{ $orderDateItemLink->quantity }}</td>
                                                <td style="text-align:left; padding:10px;">
                                                    {{ $orderDateItemLink->getItem()->producer['name'] }}<br>
                                                    <a href="mailto:{{ $orderDateItemLink->getItem()->producer['email'] }}">{{ $orderDateItemLink->getItem()->producer['email'] }}</a>
                                                </td>
                                                <td style="text-align:left; padding:10px;">{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                                <td style="text-align:right; padding:10px;">{{ $orderDateItemLink->getPrice() }} {{ $orderDateItemLink->getItem()->producer['currency'] }}</td>
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
    @endforeach
@endsection
