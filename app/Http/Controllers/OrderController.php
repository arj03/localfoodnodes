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
    public function createOrder()
    {
        $user = Auth::user();

        $errors = $this->validateOrder($user);
        if ($errors->isEmpty()) {
            $orders = $this->saveOrder($user);

            \Mail::to($user->email)->send(new \App\Mail\CustomerOrder($orders['orderDates']));

            // Send producer emails
            $orders['orderItems']->groupBy('producer_id')->each(function($orderItems) {
                $producerEmail = $orderItems[0]->producer['email'];
                \Mail::to($producerEmail)->send(new \App\Mail\ProducerOrder($orderItems));
            });

            $user->cartDateItemLinks()->each->delete();

            \App\Helpers\SlackHelper::message('notification', $user->name . ' placed an order.');

            return redirect('/account/user/orders');
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
        $orderDates = new Collection();
        $orderItems = new Collection();

        $user->cartDateItemLinks()->each(function($cartDateItemLink) use ($user, &$orderDates, &$orderItems) {
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

            // Add to email data collection
            $orderItems->push($orderItem);

            $orderDate = OrderDate::where('date', $cartDate->date('Y-m-d'))->first();
            if (!$orderDate) {
                $orderDate = OrderDate::create([
                    'user_id' => $user->id,
                    'date' => $cartDate->date('Y-m-d'),
                ]);
            }

            // Add to email data collection
            $orderDates->push($orderDate);

            $orderDateItemLink = OrderDateItemLink::create([
                'user_id' => $user->id,
                'producer_id' => $cartItem->producer['id'],
                'order_item_id' => $orderItem->id,
                'order_date_id' => $orderDate->id,
                'quantity' => $cartDateItemLink->quantity,
                'ref' => $cartDateItemLink->ref,
            ]);
        });

        return [
            'orderDates' => $orderDates,
            'orderItems' => $orderItems,
        ];
    }
}
