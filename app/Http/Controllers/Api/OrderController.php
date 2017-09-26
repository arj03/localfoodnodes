<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Order\OrderItem;
use App\Order\OrderDate;
use App\Order\OrderDateItemLink;
use DateTime;

class OrderController extends \App\Http\Controllers\Controller
{
    /**
     * Get all orders
     */
    public function index(Request $request)
    {
        return OrderDateItemLink::all();
    }
}
