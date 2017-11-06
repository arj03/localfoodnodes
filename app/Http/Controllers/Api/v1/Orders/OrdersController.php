<?php

namespace App\Http\Controllers\Api\v1\Orders;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order\OrderDateItemLink;

class OrdersController extends \App\Http\Controllers\Controller
{
    public function orders(Request $request)
    {
        return OrderDateItemLink::with(['orderDateRelationship', 'orderItemRelationship'])->get();
    }
}
