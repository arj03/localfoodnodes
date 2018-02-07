<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;

use App\Product\Product;
use App\Producer\Producer;
use App\Node\Node;
use \App\Traits\CartLogic;

use \DateTime;

class CartController extends Controller
{
    use CartLogic;

    /**
     * Render cart view.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $cartDates = $user->cartDates();
        $today = new \DateTime(date('Y-m-d'));

        // Remove passed dates
        // Todo: remove cart completely if there are no dates
        $cartDates->each(function($cartDate) use ($today) {
            if ($cartDate->date < $today) {
                $cartDateItemLinks = $cartDate->cartDateItemLinks();
                $cartDateItemLinks->each(function($cartDateItemLink) {
                    $cartDateItemLink->delete();
                });
                $cartDate->delete();
            }
        });

        return view('public/checkout', [
            'user' => $user
        ]);
    }

    /**
     * Add single item to cart.
     *
     * @param Request $request
     */
    public function addItem(Request $request)
    {
        $messages = new MessageBag();
        $product = Product::find($request->input('product_id'));

        // Validate
        $errors = $this->validateAddItemRequest($request, $product);

        // Abort and display errors
        if (!$errors->isEmpty()) {
            $request->session()->flash('error', $errors->all());
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // Load additional data
        $user = Auth::user();
        $variant = $product->variants()->where('id', $request->input('variant_id'))->first();
        $producer = Producer::where('id', $product->producer_id)->first();
        $node = Node::find($request->input('node_id'));
        $data = [
            'delivery_dates' => $request->input('delivery_dates'),
            'quantity' => $request->input('quantity'),
            'message' => $request->input('message'),
        ];

        // Add item to cart
        $messages = $this->add($data, $user, $producer, $product, $variant, $node);
        $messages->add('added_to_cart', trans('public/product.added_to_cart'));

        $request->session()->flash('message', $messages->all());
        $request->session()->flash('added_to_cart_modal', true);

        return redirect($node->permalink()->url);
    }

    /**
     * Add multiple items to cart.
     *
     * @param Request $request
     */
    public function addItems(Request $request) {
        $itemsMessages = new MessageBag();

        $user = Auth::user();
        $node = Node::find($request->input('node_id'));

        // Loop products.
        // Validate and and add to cart
        $items = collect($request->input('product'));
        foreach ($items as $item) {
            $data = [
                'message' => null,
            ];

            // Quantity
            if (isset($item['quantity']) && $item['quantity'] > 0) {
                $data['quantity'] = $item['quantity'];
            } else {
                continue; // Ignore this product if quantity is zero
            }

            $errors = new MessageBag();
            $product = Product::find($item['product_id']);

            // Variant
            $variant = null;
            if ($product->variants()->count() > 0 && isset($item['variant_id'])) {
                $variant = $product->variants()->where('id', $item['variant_id'])->first();
            }

            // Delivery date
            if ($request->has('delivery_date')) {
                $deliveryDates = [$request->input('delivery_date')];

                // CSA is all or nothing, programmatically adding all dates
                if ($product->productionType === 'csa') {
                    $deliveryDates = array_values($product->deliveryLinks()->map(function($deliveryLink) {
                        return $deliveryLink->date('Y-m-d');
                    })->toArray());
                }

                $data['delivery_dates'] = $deliveryDates;
            } else {
                $errors->add('no_delivery_dates', trans('public/product.no_delivery_dates'));
            }

            // Abort on errors
            if (!$errors->isEmpty()) {
                continue;
            }

            $producer = Producer::where('id', $product->producer_id)->first();

            $itemMessages = $this->add($data, $user, $producer, $product, $variant, $node);
            $itemsMessages = $itemsMessages->merge($itemMessages);
        }

        $itemsMessages->add('added_to_cart', trans('public/product.added_to_cart'));

        $request->session()->flash('message', $itemsMessages->all());
        $request->session()->flash('added_to_cart_modal', true);

        return redirect(\URL::previous());
    }

    /**
     * Update cart item quantity action.
     *
     * @param Request $request
     * @param int $cartItemId
     */
    public function updateItem(Request $request, $cartDateItemLinkId)
    {
        $quantity = $request->input('quantity');
        if (!$quantity) {
            return $this->removeItem($request, $cartDateItemLinkId);
        }

        $user = Auth::user();
        $cartDateItemLink = $user->cartDateItemLink($cartDateItemLinkId);

        if (!$cartDateItemLink) {
            $request->session()->flash('message', [trans('public/checkout.cart_item_update_failed')]);
            return redirect()->back();
        }

        $cartItem = $cartDateItemLink->getItem();

        $product = Product::find($cartItem->product['id']);
        $variant = $product->variants()->where('id', $cartItem->variant['id'])->first();
        $node = Node::find($cartItem->node['id']);

        if ($cartDateItemLink->getItem()->product['production_type'] === 'csa') {
            // CSA is all or nothing
            $return = $user->cartItems($cartDateItemLink->getItem()->product['id'])->map(function($cartItem) use ($product, $variant, $node, $quantity) {
                return $cartItem->cartDateItemLinks()->map(function($cartDateItemLink) use ($product, $variant, $node, $quantity) {
                    return $this->validateAndUpdateCartDateItemLink($cartDateItemLink, $product, $variant, $node, $quantity);
                });
            })->flatten()->first();
        } else {
            $return = $this->validateAndUpdateCartDateItemLink($cartDateItemLink, $product, $variant, $node, $quantity);
        }

        if ($return->errors) {
            $request->session()->flash('error', $return->errors->all());
        }

        return redirect('/checkout');
    }

    /**
     * Cart remove cart item action.
     */
    public function removeItem(Request $request, $cartDateItemLinkId)
    {
        $user = Auth::user();

        $cartDateItemLink = $user->cartDateItemLink($cartDateItemLinkId);

        if (!$cartDateItemLink) {
            return redirect()->back();
        }

        if (isset($cartDateItemLink->getItem()->product['production_type']) && $cartDateItemLink->getItem()->product['production_type'] === 'csa') {
            // CSA is all or nothing
            $user->cartItems($cartDateItemLink->getItem()->product['id'])->map(function($cartItem) {
                $cartItem->cartDateItemLinks()->each(function($cartDateItemLink) {
                    $cartDateItemLink->delete();
                });
            });
        } else {
            $cartDateItemLink->delete();
        }

        return redirect()->back();
    }
}
