<?php

namespace App\Http\Controllers\Api\v1\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends \App\Http\Controllers\Controller
{
    public function orders(Request $request)
    {
        $user = Auth::guard('api')->user();
        $orderDateItemLinks = $user->orderDateItemLinks();

        $orderDateItemLinks = $orderDateItemLinks->map(function($orderDateItemLink) {
            $orderDate = $orderDateItemLink->getDate();
            $orderItem = $orderDateItemLink->getItem();

            $orderDateItemLinkArray = $orderDateItemLink->toArray();
            $orderDateItemLinkArray['date'] = $orderDate->toArray();
            $orderDateItemLinkArray['item'] = $orderItem->toArray();

            return $orderDateItemLinkArray;
        });

        return $orderDateItemLinks;
    }
}
