<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User\User;
use App\Producer\Producer;
use App\Producer\ProducerNodeLink;
use App\Producer\ProducerAdminLink;
use App\Product\Product;
use App\Product\ProductNodeDeliveryLink;
use App\Node\Node;
use App\Order\Order;
use App\Image\Image;

use App\Helpers\GoogleMapsHelper;

class ProducerController extends Controller
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
            $producerId = $request->route('producerId');

            if (!$producerId) {
                return $next($request);
            }

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

            return $next($request);
        });
    }

    /**
     * Producer index action.
     */
    public function index(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        $orderItems = $producer->orderItems();

        $orderDateItemLinks = $producer->orderItems()->map(function($orderItem) {
            return $orderItem->orderDateItemLink();
        })->flatten();

        $orderDates = $orderDateItemLinks->map(function($orderDateItemLink) {
            return $orderDateItemLink->getDate();
        })->flatten();

        return view('account.producer.index', [
            'producer' => $producer,
            'orderDateItemLinks' => $orderDateItemLinks,
            'orderDates' => $orderDates,
            'orderItems' => $orderItems,
            'breadcrumbs' => [
                $producer->name => '',
                trans('admin/user-nav.dashboard') => ''
            ]
        ]);
    }

    /**
     * Producer create action.
     */
    public function create(Request $request)
    {
        $producer = new Producer();
        $producer->fill($request->old());
        return view('account.producer.create', [
            'producer' => $producer,
            'breadcrumbs' => [
                trans('admin/user-nav.create_producer') => ''
            ]
        ]);
    }

    /**
     * Producer insert action.
     */
    public function insert(Request $request, GoogleMapsHelper $googleMapsHelper)
    {
        $user = Auth::user();
        $data = $request->all();
        $producer = new Producer();

        $errors = $producer->validate($data);
        if ($errors->isEmpty()) {
            $producer->fill($producer->sanitize($data));

            $this->uploadImage($request, $user, $producer);

            $latLng = $googleMapsHelper->getLatLngForDb($producer->getAddressFields());
            $producer->setLocation($latLng);

            $producer->save();

            \App\Helpers\SlackHelper::message('notification', $user->name . ' (' . $user->email . ')' . ' created the producer ' . $producer->name . '.');

            ProducerAdminLink::create(['producer_id' => $producer->id, 'user_id' => $user->id, 'active' => 1]);

            $request->session()->flash('message', [trans('admin/messages.producer_created')]);
            return redirect('/account/producer/' . $producer->id . '/edit');
        }

        return redirect('/account/producer/create?terms=approved')->withInput()->withErrors($errors);
    }

    /**
     * Producer edit action.
     */
    public function edit(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $producer->fill($request->old());

        return view('account.producer.edit', [
            'producer' => $producer,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.edit') => ''
            ]
        ]);
    }

    /**
     * Producer update action.
     */
    public function update(Request $request, GoogleMapsHelper $googleMapsHelper, $producerId)
    {
        $user = Auth::user();
        $data = $request->all();
        $producer = $user->producerAdminLink($data['id'])->getProducer();

        $errors = $producer->validate($data);
        if ($errors->isEmpty()) {
            $producer->fill($producer->sanitize($data));

            $latLng = $googleMapsHelper->getLatLngForDb($producer->getAddressFields());
            $producer->setLocation($latLng);
            $producer->save();

            $this->uploadImage($request, $producer);

            $request->session()->flash('message', [trans('admin/messages.producer_updated')]);
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Confirm delete action.
     */
    public function deleteConfirm(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        return view('account.producer.confirm-delete', [
            'producer' => $producer,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.delete') => ''
            ]
        ]);
    }

    /**
     * Producer delete action.
     */
    public function delete(Request $request, $producerId)
    {
        $user = Auth::user();
        $user->producerAdminLink($producerId)->getProducer()->delete();

        $request->session()->flash('message', [trans('admin/messages.producer_deleted')]);
        return redirect('/account/user');
    }

    /**
     * Leave producer action.
     */
    public function leave(Request $request, $producerId)
    {
        $user = Auth::user();
        $producerAdminLink = $user->producerAdminLink($producerId);
        $producer = $producerAdminLink->getProducer();
        $producerAdminLink->delete();

        $request->session()->flash('message', [
            trans('admin/messages.admin_removed', ['name' => $producer->name])
        ]);
        return redirect('/account/user');
    }

    /**
     * Upload image.
     *
     * @param Request $request
     * @param Producer $producer
     */
    public function uploadImage(Request $request, $producer)
    {
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                Image::create([
                    'entity_id' => $producer->id,
                    'entity_type' => 'producer',
                    'file' => $file,
                    'sort' => 999
                ]);
            }
        }

        if ($request->input('image_sort_order')) {
            foreach ($request->input('image_sort_order') as $imageId => $sortOrder) {
                $image = $producer->image($imageId);
                $image->sort = $sortOrder;
                $image->save();
            }
        }
    }

    /**
     * Select nodes view.
     */
    public function nodes(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        return view('account.producer.nodes', [
            'producer' => $producer,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.nodes') => ''
            ]
        ]);
    }

    /**
     * Products action.
     */
    public function products(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $products = Product::where('producer_id', $producer->id)->get();

        return view('account.producer.products', [
            'producer' => $producer,
            'products' => $products,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.products') => ''
            ]
        ]);
    }

    /**
     * Deliveries action.
     */
    public function deliveries($producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();

        return view('account.producer.deliveries', [
            'producer' => $producer,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.deliveries') => ''
            ]
        ]);
    }

    /**
     * Pick list action.
     */
    public function picklist($producerId, $orderDateId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $orderDate = $producer->orderDate($orderDateId);
        $orderItemsGroupedByUserId = $orderDate->orderDateItemLinks(null, $producer->id)->groupBy('user_id');
        $node = $orderDate->orderDateItemLinks(null, $producer->id)->first()->getItem()->node;

        return view('account.producer.delivery-picklist', [
            'producer' => $producer,
            'node' => $node,
            'orderDate' => $orderDate,
            'orderItemsGroupedByUserId' => $orderItemsGroupedByUserId,
            'breadcrumbs' => [
                $producer->name => 'producer/' . $producer->id,
                trans('admin/user-nav.deliveries') => 'producer/' . $producer->id . '/deliveries',
                $node['name'] . ' ' . $orderDate->date('Y-m-d') => ''
            ]
        ]);
    }

    /**
     * Invite user to become producer admin.
     *
     * @param Request $request
     */
    public function sendAdminInvite(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $invitedUser = User::where(['email' => $request->input('email')])->first();

        if (!$invitedUser) {
            $request->session()->flash('error', [trans('admin/messages.invite_no_user')]);
            return redirect()->back();
        }

        $adminLink = $invitedUser->producerAdminLink($producerId);
        if ($adminLink) {
            if ($adminLink->active === 0) {
                $request->session()->flash('message', [trans('admin/messages.user_invited')]);
            } else {
                $request->session()->flash('message', [trans('admin/messages.user_is_admin')]);
            }

            return redirect()->back();
        }

        $errors = null;
        if ($invitedUser) {
            ProducerAdminLink::create([
                'producer_id' => $producer->id,
                'user_id' => $invitedUser->id,
                'active' => 0
            ]);
            $request->session()->flash('message', [trans('admin/messages.invite_sent')]);
        } else {
            $request->session()->flash('message', [trans('admin/messages.invite_no_user')]);
        }

        return redirect()->back()->withErrors($errors);
    }

    /**
     * Cancel admin invite.
     *
     * @param Request $request
     * @param int $producerId
     * @param int $userId
     */
    public function cancelInvite(Request $request, $producerId, $userId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $producer->adminInvites()->where('user_id', $userId)->first()->delete();

        $request->session()->flash('message', [trans('admin/messages.invite_cancelled')]);
        return redirect()->back();
    }

    /**
     * Accept admin invite.
     *
     * @param Request $request
     * @param int $producerId
     * @param int $userId
     */
    public function acceptInvite(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $producerAdminInvite = $producer->adminInvite($user->id);

        $producerAdminInvite->update(['active' => 1]);

        $request->session()->flash('message', [
            trans('admin/messages.invite_accepted', ['name' => $producer->name])
        ]);
        return redirect()->back();
    }

    /**
     * Get nodes for map action.
     */
    public function mapGetNodes(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $nodes = Node::all();

        $selectedNodes = [];
        $links = ProducerNodeLink::where('producer_id', $producer->id)->get();
        foreach ($links as $link) {
            array_push($selectedNodes, $link->getNode());
        }

        return response()->json([
            'nodes' => $nodes,
            'selectedNodes' => $selectedNodes,
            'location' => $producer->location
        ]);
    }

    /**
     * Add node to map action.
     */
    public function mapAddNode(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $data = $request->all();

        try {
            ProducerNodeLink::create(['producer_id' => $producer->id, 'node_id' => $data['nodeId']]);
        } catch(\Exception $e) {
            return response()->json($e, 400);
        }

        $selectedNodes = [];
        $links = ProducerNodeLink::where('producer_id', $producer->id)->get();
        foreach ($links as $link) {
            array_push($selectedNodes, $link->getNode());
        }

        return response()->json([
            'selectedNodes' => $selectedNodes
        ]);
    }

    /**
     * Remove node from map action.
     */
    public function mapRemoveNode(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = $user->producerAdminLink($producerId)->getProducer();
        $data = $request->all();

        // Remove producer node link
        $producerNodeLink = ProducerNodeLink::where('producer_id', $producer->id)->where('node_id', $data['nodeId'])->first();
        $producerNodeLink->delete();

        // Find all product ids and remove product node delivery link too
        $productIds = $producer->products()->map(function($product) {
            return $product->id;
        });
        ProductNodeDeliveryLink::whereIn('product_id', $productIds)->where('node_id', $data['nodeId'])->delete();

        // Return new selection
        $selectedNodes = [];
        $links = ProducerNodeLink::where('producer_id', $producer->id)->get();
        foreach ($links as $link) {
            array_push($selectedNodes, $link->getNode());
        }

        return response()->json([
            'selectedNodes' => $selectedNodes
        ]);
    }
}
