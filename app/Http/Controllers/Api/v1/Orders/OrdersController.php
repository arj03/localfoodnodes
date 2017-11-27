<?php

namespace App\Http\Controllers\Api\v1\Orders;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use App\Order\OrderDateItemLink;
use App\Order\OrderItem;
use App\Order\OrderDate;

class OrdersController extends \App\Http\Controllers\Controller
{
    public function count()
    {
        return OrderDateItemLink::count();
    }

    public function orders(Request $request)
    {
        if (!Cache::has('orders')) {
            $orders = OrderDateItemLink::all()->map(function($orderDateItemLink) {
                // Exclude user details here
                $orderDateItemLink->item = $orderDateItemLink->getItem();
                $orderDateItemLink->date = $orderDateItemLink->getDate();

                return $orderDateItemLink;
            });

            Cache::put('orders', $orders, 60);
        }

        return Cache::get('orders');
    }

    public function orderItem(Request $request, $orderItemId)
    {
        return OrderItem::find($orderItemId);
    }

    public function orderDate(Request $request, $orderDateId)
    {
        return OrderDate::find($orderDateId);
    }

    public function circulation()
    {
        return OrderDateItemLink::all()->map(function($orderDateItemLink) {
            $price = $orderDateItemLink->getItem()->product['price'];

            return $price * $orderDateItemLink->quantity;
        })->sum();
    }


}
