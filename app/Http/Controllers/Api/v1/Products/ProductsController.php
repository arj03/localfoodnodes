<?php

namespace App\Http\Controllers\Api\v1\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product\Product;
use App\Product\ProductNodeDeliveryLink;

class ProductsController extends \App\Http\Controllers\Controller
{
    public function products(Request $request)
    {
        $date = $request->input('date');
        $nodeId = $request->input('node');

        $productIds = null;

        if ($nodeId) {
            $linkQuery = ProductNodeDeliveryLink::where('node_id', $nodeId);

            if ($date) {
                $linkQuery->where('date', $date);
            }

            $productIds = $linkQuery->get()->pluck('product_id')->unique();
        }

        return Product::with(['productVariantsRelationship', 'imageRelationship'])->where('is_hidden', 0)->whereIn('id', $productIds)->get();
    }

    public function product(Request $request, $productId)
    {
        return Product::find($productId);
    }

    public function dates(Request $request, $productId)
    {
        $product = Product::where('is_hidden', 0)->where('id', $productId)->first();
        $nodeId = $request->input('node_id');
        $variant = null;

        if ($request->has('variant_id')) {
            $variant = $product->variant($request->input('variant_id'));
        }

        $cartQuantity = null;

        $dates = [];
        $product->deliveryLinks($nodeId)->each(function($deliveryLink) use (&$dates, $variant, $cartQuantity) {
            $date = $deliveryLink->date('Y-m-d');
            $quantity = $deliveryLink->getAvailableQuantity($variant, $cartQuantity);

            $dates[$date] = $quantity;
        });

        return $dates;
    }
}
