<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;
use App\Cart\Cart;
use App\Cart\CartDate;
use App\Cart\CartItem;
use App\Cart\CartDateItemLink;
use App\Order\Order;
use App\Order\OrderItem;
use App\Order\OrderDate;
use App\Order\OrderDateItemLink;
use App\Order\OrderStatus;

use App\Product\Product;
use App\Product\ProductVariant;
use App\Producer\Producer;
use App\Node\Node;

use App\Jobs\SendOrderEmails;

use \DateTime;

class OrderController extends Controller
{
    /**
     * Create order action.
     */
    public function createOrder(Request $request)
    {
        $user = Auth::user();
        $errors = $this->validateOrder($user);

        if ($errors->isEmpty()) {
            $orderDateItemLinks = $this->saveOrder($user);
            $orderRefs = $orderDateItemLinks->map->ref;

            $customerOrderDates = $orderDateItemLinks->map(function($orderDateItemLink) {
                return $orderDateItemLink->getDate();
            })->unique(function($orderDate) {
                return $orderDate->date('Y-m-d');
            })->filter();

            $customerOrderDates->each->setOrderFilter($orderRefs);

            \Mail::to($user->email)->send(new \App\Mail\CustomerOrder($customerOrderDates));

            $producerOrderDateItemLinksByProducerId = $orderDateItemLinks->groupBy(function($orderDateItemLink) {
                return $orderDateItemLink->getItem()->producer_id;
            });

            $producerOrderDatesByProducerId = $producerOrderDateItemLinksByProducerId->map(function($orderDateItemLinks) {
                return $orderDateItemLinks->map(function($orderDateItemLink) {
                    return $orderDateItemLink->getDate();
                })->unique(function($orderDate) {
                    return $orderDate->date('Y-m-d');
                })->filter();
            });

            $producerOrderDatesByProducerId->each(function($orderDates, $producerId) use ($orderRefs) {
                $producer = Producer::find($producerId);

                $orderDates->each->setOrderFilter($orderRefs);

                \Mail::to($producer->email)->send(new \App\Mail\ProducerOrder($producer, $orderDates));
            });

            $user->cartDateItemLinks()->each->delete();

            \App\Helpers\SlackHelper::message('notification', $user->name . ' placed an order.');

            return redirect('/account/user/pickups');
        }

        if ($errors) {
            $request->session()->flash('error', $errors->all());

        }

        return redirect()->back()->withErrors($errors);
    }

    /**
     * Validate order quantity.
     *
     * @param Cart $cart
     * @return Collection errors
     */
    private function validateOrder($user)
    {
        $errors = new MessageBag();
        $user->cartDateItemLinks()->each(function($cartDateItemLink) use (&$errors) {
            $cartItem = $cartDateItemLink->getItem();
            $cartDate = $cartDateItemLink->getDate();

            $product = Product::find($cartItem->product['id']);
            $variant = $product->variants()->where('id', $cartItem->variant['id'])->first();
            $deliveryLinks = $product->deliveryLinks($cartDateItemLink->node['id'], collect([$cartDate->date('Y-m-d')]));

            $deliveryLinks->each(function($deliveryLink) use ($cartDateItemLink, $product, $variant, &$errors) {
                $quantity = $deliveryLink->getAvailableQuantity($variant);

                if ($quantity < $cartDateItemLink->quantity) {
                    $errors->add('date_quantity_' . $deliveryLink->date('Y-m-d'), 'Your ordered quantity for ' . $cartDateItemLink->getItem()->getName() . ' exceeds the product quantity for ' . $deliveryLink->date('Y-m-d') . '. Only ' . $quantity . ' available.');
                }
            });
        });

        return $errors;
    }

    /**
     * Save order
     *
     * @param Cart $cart
     */
    private function saveOrder($user)
    {
        $orderDateItemLinks = new Collection();

        $user->cartDateItemLinks()->each(function($cartDateItemLink) use ($user, &$orderDateItemLinks) {
            $cartItem = $cartDateItemLink->getItem();
            $cartDate = $cartDateItemLink->getDate();

            $orderItem = OrderItem::create([
                'user_id' => $user->id,
                'user' => $user->getInfoForOrder(),
                'node_id' => $cartItem->node['id'],
                'node' => $cartItem->node,
                'producer_id' => $cartItem->producer['id'],
                'producer' => $cartItem->producer,
                'product_id' => $cartItem->product['id'],
                'product' => $cartItem->product,
                'variant_id' => $cartItem->variant['id'],
                'variant' => $cartItem->variant,
                'message' => $cartItem->message,
            ]);

            $orderDate = OrderDate::where('date', $cartDate->date('Y-m-d'))->where('user_id', $user->id)->first();
            if (!$orderDate) {
                $orderDate = OrderDate::create([
                    'user_id' => $user->id,
                    'date' => $cartDate->date('Y-m-d'),
                ]);
            }

            $orderDateItemLink = OrderDateItemLink::create([
                'user_id' => $user->id,
                'producer_id' => $cartItem->producer['id'],
                'order_item_id' => $orderItem->id,
                'order_date_id' => $orderDate->id,
                'quantity' => $cartDateItemLink->quantity,
                'ref' => $cartDateItemLink->ref,
            ]);

            $orderDateItemLinks->push($orderDateItemLink);
        });

        return $orderDateItemLinks;
    }
}
