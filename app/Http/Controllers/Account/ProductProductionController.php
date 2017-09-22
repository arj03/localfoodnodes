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
use App\Product\ProductProduction;
use App\Product\ProductProductionAdjustment;

class ProductProductionController extends Controller
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
            if (!$productId) {
                return $next($request);
            }

            $product = $producer->product($productId);
            $errorMessage = trans('admin/messages.request_no_product');

            if (!$product) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/producer/' . $producer->id);
            }

            return $next($request);
        });
    }

    /**
     * Index action.
     *
     * @param Request $request
     * @param int $producerId
     * @param int $productId
     */
    public function index(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        if ($request->old('prodution_type')) {
            $product->production_type = $request->old('production_type');
        }

        return view('account.product.production.index', [
            'producer' => $producer,
            'product' => $product,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                $product->name => 'producer/' . $producer->id . '/product/' . $product->id . '/edit',
                trans('admin/user-nav.create_production') => ''
            ]
        ]);
    }

    /**
     * Update production.
     *
     * @param Request $request
     * @param int $producerId
     * @param int $productId
     */
    public function update(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        $previousProductionCount = $product->productions()->count();

        $errors = $this->validateProduction($request);
        if ($errors->isEmpty()) {
            $this->saveProduction($request, $producer, $product, $errors);

            $request->session()->flash('message', [trans('admin/messages.production_updated')]);
            if ($previousProductionCount === 0) {
                // Redirect to next page if no previous productions
                return redirect('/account/producer/' . $producer->id . '/product/' . $product->id . '/deliveries');
            } else {
                return redirect('/account/producer/' . $producer->id . '/product/' . $product->id . '/production');
            }
        }

        $request->session()->flash('message', [trans('admin/messages.required_fields_missing')]);
        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Adjustment view action.
     *
     * @param Request $request
     * @param int $producerId
     * @param int $productId
     */
    public function adjustment(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        $startDate = new \DateTime(date('Y-m-d'));
        $interval = new \DateInterval('P1W');
        $recurrences = 52;
        $datePeriod = new \DatePeriod($startDate, $interval, $recurrences);

        $dates = [];
        foreach ($datePeriod as $date) {
            $dates[$date->format('Y-m-01')][] = $date;
        }

        return view('account.product.production.adjustment', [
            'producer' => $producer,
            'product' => $product,
            'dates' => $dates,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                $product->name => 'producer/' . $producer->id . '/product/' . $product->id . '/edit',
                trans('admin/user-nav.adjust_production') => ''
            ]
        ]);
    }

    /**
     * Update production adjustments.
     *
     * @param Request $request
     * @param int $producerId
     * @param int $productId
     */
    public function updateAdjustment(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        // Delete all first
        ProductProductionAdjustment::where('product_id', $product->id)->delete();

        foreach ($request->input('quantity') as $year => $weeks) {
            foreach ($weeks as $week => $quantity) {
                if (is_numeric($quantity) && (int) $quantity >= 0) {
                    ProductProductionAdjustment::create([
                        'product_id' => $product->id,
                        'year' => $year,
                        'week' => $week,
                        'quantity' => $quantity
                    ]);
                }
            }
        }

        $request->session()->flash('message', [trans('admin/messages.production_updated')]);
        return redirect()->back();
    }

    /**
     * Validate production.
     *
     * @param Request $request
     * @param MessageBag $errors
     * @return boolean
     */
    private function validateProduction(Request $request)
    {
        $errors = new MessageBag();
        $data = $request->all();

        if (!isset($data['production_type']) || empty($data['production_type'])) {
            $errors->add('production_type', trans('admin/messages.required'));
            return $errors;
        }

        $type = $data['production_type'];

        // Check for occasional type.
        if ($type === 'occasional') {
            foreach ($data['occasional_date'] as $index => $date) {
                if (empty($date) && empty($data['occasional_quantity'][$index]) && $index > 0) {
                    continue;
                }

                if (empty($date)) {
                    $errors->add('occasional_date[' . $index . ']', trans('admin/messages.required'));
                }

                if (empty($data['occasional_quantity'][$index])) {
                    $errors->add('occasional_quantity[' . $index . ']', trans('admin/messages.required'));
                }
            }
        } else {
            if (!isset($data[$type . '_quantity']) || empty($data[$type . '_quantity'])) {
                $errors->add($type . '_quantity', trans('admin/messages.required'));
                return $errors;
            }
        }

        return $errors;
    }

    /**
     * Helper function to create productions.
     *
     * @param  array $data
     * @param  Producer $producer
     * @param  Product $product
     * @return $errors
     */
    private function saveProduction($request, $producer, $product, &$allErrors)
    {
        $data = $request->all();

        if (!isset($data['production_type']) || empty($data['production_type'])) {
            $request->session()->flash('error', [trans('admin/messages.product_no_production')]);
            return false;
        }

        $productionType = $data['production_type'];

        // If type has changed, clear all production info for the old type.
        // Only occasional productions can have multiple rows, so clear everything for the other types.
        if ($productionType !== $product->productionType || $productionType !== 'occasional') {
            ProductProduction::where('product_id', $product->id)->delete();
        }

        // Generic data
        $productionData = [
            'producer_id' => $producer->id,
            'product_id' => $product->id,
            'type' => $productionType
        ];

        // Check for occasional type.
        if ($productionType === 'occasional') {
            // If no value is set return early.
            if (empty($data['occasional_date']) && empty($data['occasional_quantity'])) {
                $request->session()->flash('error', [trans('admin/messages.product_date_quantity_required')]);
            }

            $occasions = array_combine($data['occasional_date'], $data['occasional_quantity']);

            foreach ($occasions as $date => $quantity) {
                // Set values to data array.
                $productionData['date'] = $date;
                $productionData['quantity'] = $quantity;

                // Validate and create production.
                $productProduction = new ProductProduction();
                $errors = $productProduction->validate($productionData);
                if ($errors->isEmpty()) {
                    $productProduction->fill($productProduction->sanitize($productionData));
                    $productProduction->save();
                }
            }
        } else {
            // Set value to data array.
            $quantity = $data[$productionType . '_quantity'];
            $productionData['quantity'] = $quantity;

            // Validate and create production.
            $productProduction = new ProductProduction();
            $errors = $productProduction->validate($productionData);
            if ($errors->isEmpty()) {
                $productProduction->fill($productProduction->sanitize($productionData));
                $productProduction->save();
            }
        }

        return $allErrors->merge($errors->getMessages());
    }

    /**
     * Delete product variant.
     *
     * @param Request $request
     * @param int $productId
     * @param int $variantId
     */
    public function deleteProduction(Request $request, $producerId, $productId, $productionId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        ProductProduction::where([
            'product_id' => $product->id,
            'id' => $productionId
        ])->delete();

        return redirect()->back();
    }
}
