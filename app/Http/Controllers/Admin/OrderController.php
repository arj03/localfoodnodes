<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User\User;
use App\Producer\Producer;
use App\Order\Order;
use App\Order\OrderItem;
use App\Order\OrderItemDate;
use App\Order\OrderStatus;

class OrderController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * Check if requested producer account exist, or if user has permission.
         */
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            // Producer
            $producerId = $request->route('producerId');
            $producerAdminLink = $user->producerAdminLink($producerId);
            $errorMessage = 'Could not find the producer account you requested';

            if (!$producerAdminLink) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/user');
            }

            $producer = $producerAdminLink->getProducer();

            if (!$producer) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/user');
            }

            $orderId = $request->route('orderId');

            if (!$orderId) {
                return $next($request);
            }

            $orderDateItemLink = $producer->orderDateItemLink($orderId);
            $errorMessage = 'Could not find the order you requested';

            if (!$orderDateItemLink) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/producer/' . $producer->id . '/orders');
            }

            return $next($request);
        });
    }

    /**
     * Orders action.
     */
    public function orders($producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        return view('admin.producer.orders', [
            'producer' => $producer,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.orders') => ''
            ]
        ]);
    }

    /**
     * User orders action.
     */
    public function userOrders($producerId, $userId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $orderItems = $producer->orderItems($userId);

        return view('admin.producer.user-orders', [
            'producer' => $producer,
            'orderItems' => $orderItems,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.orders') => ''
            ]
        ]);
    }


    /**
     * Product orders action.
     */
    public function productOrders($producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $orderItems = $producer->orderItems()->filter(function($orderItem) use ($productId) { // null, $producerId
            return $orderItem->product['id'] == $productId;
        })->sortByDesc(function($orderItem) {
            $orderDate = $orderItem->orderDateItemLink()->getDate();
            return $orderDate ? $orderDate->date('Y-m-d') : 0;
        });

        return view('admin.producer.product-orders', [
            'producer' => $producer,
            'orderItems' => $orderItems,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.orders') => ''
            ]
        ]);
    }

    /**
     * Order action.
     */
    public function order(Request $request, $producerId, $orderDateItemLinkId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $orderDateItemLink = $producer->orderDateItemLink($orderDateItemLinkId);

        return view('admin.producer.order', [
            'producer' => $producer,
            'orderDateItemLink' => $orderDateItemLink,
            'orderDate' => $orderDateItemLink->getDate(),
            'orderItem' => $orderDateItemLink->getItem(),
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.orders') => 'producer/' . $producer->id . '/orders',
                trans('admin/user-nav.order') . ' #' . $orderDateItemLink->ref => ''
            ]
        ]);
    }

    /**
     * Change order item status.
     */
    public function changeOrderStatus(Request $request, $producerId, $orderItemId, $status)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        // Load all
        $orderItem = OrderItem::where([
            'id' => $orderItemId,
            'producer_id' => $producer->id
        ])->first();

        if ($orderItem) {
            OrderStatus::create([
                'order_item_id' => $orderItem->id,
                'status' => $status
            ]);
        }

        return redirect()->back();
    }
}
