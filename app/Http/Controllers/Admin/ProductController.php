<?php

namespace App\Http\Controllers\Admin;

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
use App\Producer\ProducerNodeLink;
use App\Node\NodeDelivery;
use App\Product\Product;
use App\Product\ProductNodeDeliveryLink;
use App\Product\ProductTag;
use App\Product\ProductFilter;
use App\Image\Image;

class ProductController extends Controller
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
     * Product create action.
     */
    public function create(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = new Product();

        $product->fill($request->old());

        $nodes = $producer->nodeLinks()->map(function($nodeLink) {
            return $nodeLink->getNode();
        });

        return view('admin.product.create', [
            'producer' => $producer,
            'product' => $product,
            'nodes' => $nodes,
            'tags' => ProductFilter::tags(),
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                trans('admin/user-nav.create_product') => ''
            ]
        ]);
    }

    /**
     * Product insert action.
     */
    public function insert(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        $errors = $this->validateProduct($request, $producer);

        if ($errors->isEmpty()) {
            $product = $this->saveProduct($request, $producer);
            $this->createTags($request, $product);
            $this->uploadImage($request, $product);

            $request->session()->flash('message', [trans('admin/messages.product_created')]);

            return redirect('/account/producer/' . $producer->id . '/product/' . $product->id . '/production');
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Edit product action.
     */
    public function edit(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        $product = $producer->product($productId);
        $product->fill($request->old());

        $nodes = $producer->nodeLinks()->map(function($nodeLink) {
            return $nodeLink->getNode();
        });

        return view('admin.product.edit', [
            'producer' => $producer,
            'product' => $product,
            'nodes' => $nodes,
            'tags' => ProductFilter::tags(),
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                $product->name => ''
            ]
        ]);
    }

    /**
     * Product update action.
     */
    public function update(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        $errors = $this->validateProduct($request, $producer);
        if ($errors->isEmpty()) {
            $product = $this->saveProduct($request, $producer, $product);
            $this->createTags($request, $product);
            $this->uploadImage($request, $product);

            $request->session()->flash('message', [trans('admin/messages.product_updated')]);
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Confirm delete action.
     */
    public function deleteConfirm(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);

        return view('admin.product.confirm-delete', [
            'producer' => $producer,
            'product' => $product,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                $product->name => 'producer/' . $producer->id . '/product/' . $product->id . '/edit',
                trans('admin/user-nav.delete') => ''
            ]
        ]);
    }

    /**
     * Product delete action.
     *
     * @param Request $request
     * @param int $productId
     */
    public function delete(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $product = $producer->product($productId);
        $product->delete();

        $request->session()->flash('message', [trans('admin/messages.product_deleted')]);

        return redirect('/account/producer/' . $producer->id);
    }

    /**
     * [validateProduct description]
     * @param  [type] $request  [description]
     * @param  [type] $producer [description]
     * @return [type]           [description]
     */
    private function validateProduct($request, $producer)
    {
        $data = $request->all();
        $data['producer_id'] = $producer->id;

        $product = new Product();

        return $product->validate($product->sanitize($data));
    }

    /**
     * [saveProduct description]
     * @param  [type] $request [description]
     * @param  [type] $product [description]
     * @return [type]          [description]
     */
    private function saveProduct($request, $producer, $product = null)
    {
        if (!$product) {
            $product = new Product();
        }

        $data = $request->all();
        $data['producer_id'] = $producer->id;

        $product->fill($product->sanitize($data));
        $product->save();

        return $product;
    }

    /**
     * Upload image.
     *
     * @param Request $request
     * @param Producer $producer
     */
    public function uploadImage(Request $request, $product)
    {
        $errors = new Collection();

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $image = new Image();
                $imageData = [
                    'entity_id' => $product->id,
                    'entity_type' => 'product',
                    'file' => $file,
                    'sort' => 999
                ];

                $errors = $image->validate($imageData);
                if ($errors->isEmpty()) {
                    $image->fill($imageData);
                    $image->save();
                }

                $request->session()->flash('message', $errors);
            }
        }

        if ($request->input('image_sort_order')) {
            foreach ($request->input('image_sort_order') as $imageId => $sortOrder) {
                $image = $product->image($imageId);
                $image->sort = $sortOrder;
                $image->save();
            }
        }

        return $errors;
    }

    /**
     * Toggle product tags
     *
     * @param Request $request
     * @param int $productId
     * @param string $tagId
     */
    public function createTags(Request $request, $product)
    {
        $product->tags()->each->delete();
        if ($request->has('tags')) {
            foreach ($request->input('tags') as $tagId) {
                ProductTag::create(['product_id' => $product->id, 'tag' => $tagId]);
            }
        }
    }

    /**
     * Product deliveries edit action.
     */
    public function editDeliveries(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        $product = Product::where('id', $productId)->with('deliveryLinksRelationship')->first();
        $product->fill($request->old());

        $nodes = $producer->nodeLinks()->map(function($nodeLink) {
            return $nodeLink->getNode();
        });

        return view('admin.product.delivery.index', [
            'producer' => $producer,
            'product' => $product,
            'nodes' => $nodes,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => 'producer/' . $producer->id . '/products',
                $product->name => 'producer/' . $producer->id . '/product/' . $product->id . '/edit',
                trans('admin/user-nav.delivery_dates') => ''
            ]
        ]);
    }

    /**
     * Product deliveries update action.
     */
    public function updateDeliveries(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();;
        $product = $producer->product($productId);

        $this->saveNodeDeliveries($request, $product);
        $request->session()->flash('message', [trans('admin/messages.product_delivery_updated')]);

        return redirect()->back();
    }

    /**
     * Set node deliveries depending on product type and delivery selection.
     *
     * @param array $data
     * @param Product $product
     * @return array $errors
     */
    private function saveNodeDeliveries(Request $request, $product)
    {
        $product->deliveryLinks(null, null, true)->each->delete(); // We re-add everything below

        if ($request->has('delivery_dates')) {
            $deliveryDates = $request->input('delivery_dates');

            foreach ($request->input('delivery_dates') as $nodeId => $dates) {;
                foreach ($dates as $date) {
                    $linkData = [
                        'product_id' => $product->id,
                        'node_id' => $nodeId,
                        'date' => $date,
                    ];

                    ProductNodeDeliveryLink::create($linkData);
                }
            }
        }
    }

    /**
     */
    public function setPackageUnit(Request $request, $producerId, $productId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();;
        $product = $producer->product($productId);
        $product->package_unit = $request->input('package_unit');
        $product->save();

        return redirect()->back();
    }
}
