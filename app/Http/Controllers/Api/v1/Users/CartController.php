<?php

namespace App\Http\Controllers\Api\v1\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\MessageBag;

use App\Product\Product;
use App\Producer\Producer;
use App\Node\Node;
use \App\Traits\CartLogic;

class CartController extends \App\Http\Controllers\Controller
{
    use CartLogic;
    
    public function getCart(Request $request)
    {
        return 'cart!';
    }

    /**
     * @param int product_id
     * @param int variant_id
     * @param array delivery_dates
     * @param int quantity
     */
    public function addToCart(Request $request)
    {
        $user = Auth::guard('api')->user();

        $messages = new MessageBag();
        $product = Product::find($request->input('product_id'));

        \Log::debug(var_export($request->all(), true));

        // Validate
        $errors = $this->validateAddItemRequest($request, $product);
        
        \Log::debug(var_export($errors, true));

        // Abort and display errors 
        if (!$errors->isEmpty()) {
            return $errors; // 410?!?!
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

        return $messages;
    }

    public function updateCartItem(Request $request)
    {
        $user = Auth::guard('api')->user();
        return $user->nodes();
    }

    public function removeFromCart(Request $request)
    {
        $user = Auth::guard('api')->user();
        return $user->nodes();
    }
}
