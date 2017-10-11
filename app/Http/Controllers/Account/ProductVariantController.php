<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\MessageBag;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User\User;
use App\Producer\Producer;
use App\Product\Product;
use App\Product\ProductVariant;

class ProductVariantController extends Controller
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
            $errorMessage = trans('admin/messages.request_no_producer');

            if (!$producerAdminLink) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/user');
            }

            $producer = $producerAdminLink->getProducer();

            if (!$producer) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/user');
            }

            // Product
            $productId = $request->route('productId');
            $product = $producer->product($productId);
            $errorMessage = trans('admin/messages.request_no_product');

            if (!$product) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/producer/' . $producer->id);
            }

            // Variant
            $variantId = $request->route('variantId');
            $errorMessage = trans('admin/messages.request_no_variant');

            // No variant id so nothing to check, continue to controller action
            if (!$variantId) {
                return $next($request);
            }

            $variant = $product->variant($variantId);
            if (!$variant) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/producer/' . $producer->id . '/product/' . $product->id . '/variants');
            }

            return $next($request);
        });
    }

    /**
     * Variants view.
     *
     * @param Request $request
     * @param int $productId
     */
    public function index(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        return view('account/product.variants.index', [
            'product' => $product,
            'producer' => $producer,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                $product->name => 'producer/' . $producer->id . '/product/' . $product->id . '/edit',
                trans('admin/user-nav.variants') => ''
            ]
        ]);
    }

    /**
     * Variants view.
     *
     * @param Request $request
     * @param int $productId
     */
    public function create(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);
        $variant = new ProductVariant();
        $variant->fill($request->old());

        return view('account/product.variants.create', [
            'product' => $product,
            'variant' => $variant,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                $product->name => 'producer/' . $producer->id . '/product/' . $product->id . '/edit',
                trans('admin/user-nav.variants') => 'producer/' . $producer->id . '/product/' . $product->id . '/variants',
                trans('admin/user-nav.create_variant') => ''
            ]
        ]);
    }

    /**
     * Create variant.
     *
     * @param Product $product
     * @param array $data
     * @param MessageBag &$allErrors
     * @return MessageBag $allErrors
     */
    public function insert(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        $data = $request->all();

        // Set first created variant automatically to main variant.
        if ($product->variants()->count() === 0) {
            $data['main_variant'] = 1;
        }

        $variant = new ProductVariant();

        $errors = $variant->validate($variant->sanitize($data));
        if ($errors->isEmpty()) {
            $variant->fill($variant->sanitize($data));
            $variant->save();

            $request->session()->flash('message', [trans('admin/messages.variant_created')]);
            return redirect('/account/producer/' . $producer->id . '/product/' . $product->id . '/variants');
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Variants view.
     *
     * @param Request $request
     * @param int $productId
     */
    public function edit(Request $request, $producerId, $productId, $variantId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);
        $variant = $product->variant($variantId);

        $variant->fill($request->old());

        return view('account/product.variants.edit', [
            'producer' => $producer,
            'product' => $product,
            'variant' => $variant,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                $product->name => 'producer/' . $producer->id . '/product/' . $product->id . '/edit',
                trans('admin/user-nav.variants') => 'producer/' . $producer->id . '/product/' . $product->id . '/variants',
                $variant->name => ''
            ]
        ]);
    }

    /**
     * Update variant.
     *
     * @param Product $product
     * @param array $data
     * @param MessageBag &$allErrors
     * @return MessageBag $allErrors
     */
    public function update(Request $request, $producerId, $productId, $variantId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);
        $variant = $product->variant($variantId);

        $errors = $variant->validate($variant->sanitize($request->all()));
        if ($errors->isEmpty()) {
            $variant->fill($variant->sanitize($request->all()));
            $variant->save();

            $request->session()->flash('message', [trans('admin/messages.variant_updated')]);
            return redirect('/account/producer/' . $producer->id . '/product/' . $product->id . '/variants');
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Delete product variant.
     *
     * @param Request $request
     * @param int $productId
     * @param int $variantId
     */
    public function delete(Request $request, $producerId, $productId, $variantId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);
        $productVariant = $product->variant($variantId);
        $productVariant->delete();

        $request->session()->flash('message', [trans('admin/messages.variant_deleted')]);
        return redirect()->back();
    }

    /**
     * Update variant.
     *
     * @param Product $product
     * @param array $data
     * @param MessageBag &$allErrors
     * @return MessageBag $allErrors
     */
    public function setMainVariant(Request $request, $producerId, $productId, $variantId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        foreach ($product->variants() as $variant) {
            $variant->main_variant = ((int) $variant->id === (int) $variantId) ? 1 : 0;
            $variant->save();
        }

        $request->session()->flash('message', [trans('admin/messages.variant_updated')]);

        return redirect()->back();
    }
}
