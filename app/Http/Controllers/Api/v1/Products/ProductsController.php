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

        $products = Product::where('is_hidden', 0)->whereIn('id', $productIds)->get();

        return $products;
    }

    public function product(Request $request, $productId)
    {
        return Product::find($productId);
    }
}
