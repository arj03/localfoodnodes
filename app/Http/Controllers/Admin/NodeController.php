<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User\User;
use App\Node\Node;
use App\Node\NodeAdminLink;
use App\Node\NodeDeliveryDate;
use App\Node\NodeProducerBlacklist;
use App\Producer\Producer;
use App\Producer\ProducerNodeLink;
use App\User\UserNodeLink;
use App\Image\Image;
use App\Helpers\GoogleMapsHelper;

class NodeController extends Controller
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
            $nodeId = $request->route('nodeId');

            if (!$nodeId) {
                return $next($request);
            }

            $nodeAdminLink = $user->nodeAdminLink($nodeId);
            $errorMessage = trans('admin/messages.request_no_node');

            if (!$nodeAdminLink) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/user');
            }

            $node = $nodeAdminLink->getNode();

            if (!$node) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/user');
            }

            return $next($request);
        });
    }

    /**
     * Index action.
     */
    public function index(Request $request, $nodeId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();

        return view('admin.node.index', [
            'node' => $node,
            'breadcrumbs' => [
                $node->name => '',
                trans('admin/user-nav.dashboard') => ''
            ]
        ]);
    }

    /**
     * Create node action.
     */
    public function create(Request $request)
    {
        $node = new Node();
        $node->fill($request->old());

        return view('admin.node.create', [
            'node' => $node,
            'breadcrumbs' => [
                trans('admin/user-nav.create_node') => ''
            ]
        ]);
    }

    /**
     * Insert node action.
     */
    public function insert(Request $request, GoogleMapsHelper $googleMapsHelper)
    {
        $user = Auth::user();
        $data = $request->all();
        $node = new Node();

        $errors = $node->validate($node->sanitize($data));
        if ($errors->isEmpty()) {
            $node->fill($node->sanitize($data));

            $latLng = $googleMapsHelper->getLatLngForDb($node->getAddressFields());
            $node->setLocation($latLng);
            $node->save();

            NodeAdminLink::create(['node_id' => $node->id, 'user_id' => $user->id, 'active' => 1]);

            $this->uploadImage($request, $node);

            $request->session()->flash('message', [trans('admin/messages.node_created')]);
            return redirect('/account/node/' . $node->id . '/edit');
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Edit node action.
     */
    public function edit(Request $request, $nodeId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();

        return view('admin.node.edit', [
            'node' => $node,
            'breadcrumbs' => [
                $node->name => 'node/' . $node->id,
                trans('admin/user-nav.edit') => ''
            ]
        ]);
    }

    /**
     * Update node action.
     */
    public function update(Request $request, GoogleMapsHelper $googleMapsHelper, $id)
    {
        $user = Auth::user();
        $data = $request->all();
        $node = Node::find($id);

        $errors = $node->validate($data);
        if ($errors->isEmpty()) {

            $node->fill($data);

            $latLng = $googleMapsHelper->getLatLngForDb($node->getAddressFields());
            $node->setLocation($latLng);

            $node->save();

            $this->uploadImage($request, $node);

            $request->session()->flash('message', [trans('admin/messages.node_updated')]);
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Node delete action.
     */
    public function delete(Request $request, $nodeId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();
        $nodeAdminLinks = $node->adminLinks();
        $userLinks = $node->userLinks();
        $producerLinks = $node->producerLinks();

        $node->delete();
        $nodeAdminLinks->each(function($nodeAdminLink) { $nodeAdminLink->delete(); });
        $userLinks->each(function($userLink) { $userLink->delete(); });
        $producerLinks->each(function($producerLink) { $producerLink->delete(); });

        $request->session()->flash('message', [trans('admin/messages.node_deleted')]);
        return redirect('/account/user');
    }

    /**
     * Node leave action.
     */
    public function leave(Request $request, $nodeId)
    {
        $user = Auth::user();
        $nodeAdminLink = $user->nodeAdminLink($nodeId);
        $node = $nodeAdminLink->getNode();
        $nodeAdminLink->delete();

        $request->session()->flash('message', [
            trans('admin/messages.admin_removed', ['node' => $node->name])
        ]);

        return redirect('/account/user');
    }

    /**
     * List users action.
     */
    public function users(Request $request, $nodeId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();

        return view('admin.node.users', [
            'node' => $node,
            'breadcrumbs' => [
                $node->name => 'node/' . $node->id,
                trans('admin/user-nav.users') => ''
            ]
        ]);
    }

    /**
     * List producers action.
     */
    public function producers(Request $request, $nodeId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();

        return view('admin.node.producers', [
            'node' => $node,
            'breadcrumbs' => [
                $node->name => 'node/' . $node->id,
                trans('admin/user-nav.producers') => ''
            ]
        ]);
    }



    /**
     * Upload image.
     *
     * @param Request $request
     * @param Producer $producer
     */
    public function uploadImage(Request $request, $node)
    {
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                Image::create([
                    'entity_id' => $node->id,
                    'entity_type' => 'node',
                    'file' => $file,
                    'sort' => 999
                ]);
            }
        }

        if ($request->input('image_sort_order')) {
            foreach ($request->input('image_sort_order') as $imageId => $sortOrder) {
                $image = $node->image($imageId);
                $image->sort = $sortOrder;
                $image->save();
            }
        }
    }

    /**
     * Add or remove producer from blacklist.
     *
     * @param Request $request
     * @param int $nodeId
     * @param int $producerId
     */
    public function blacklistProducer(Request $request, $nodeId, $producerId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();
        $producer = Producer::find($producerId);

        if ($producer->isBlacklisted($node->id) === null) {
            NodeProducerBlacklist::create([
                'node_id' => $node->id,
                'producer_id' => $producer->id
            ]);
        } else {
            NodeProducerBlacklist::where([
                'node_id' => $node->id,
                'producer_id' => $producer->id
            ])->delete();
        }

        return redirect()->back();
    }

    /**
     * Invite user to become node admin.
     *
     * @param Request $request
     */
    public function sendAdminInvite(Request $request, $nodeId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();
        $invitedUser = User::where(['email' => $request->input('email')])->first();

        // User doesn't exist
        if (!$invitedUser) {
            $request->session()->flash('error', [trans('admin/messages.invite_no_user')]);
            return redirect()->back(); // ->withErrors(['user_not_existing' => 'Invited user does not exist.']);
        }

        $adminLink = $invitedUser->nodeAdminLink($nodeId);

        // User is already invited
        if ($adminLink) {
            if ($adminLink->active === 0) {
                $request->session()->flash('message', [trans('admin/messages.user_invited')]);
            } else {
                $request->session()->flash('message', [trans('admin/messages.user_is_admin')]);
            }

            return redirect()->back();
        }

        NodeAdminLink::create([
            'node_id' => $node->id,
            'user_id' => $invitedUser->id,
            'active' => 0
        ]);

        return redirect()->back();
    }

    /**
     * Cancel admin invite.
     *
     * @param Request $request
     * @param int $producerId
     * @param int $userId
     */
    public function cancelInvite(Request $request, $nodeId, $userId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();
        $node->adminInvites()->where('user_id', $userId)->first()->delete();

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
    public function acceptInvite(Request $request, $nodeId)
    {
        $user = Auth::user();
        $node = $user->nodeAdminLink($nodeId)->getNode();
        $nodeAdminInvite = $node->adminInvites()->where('user_id', $user->id)->first();

        $nodeAdminInvite->update(['active' => 1]);

        $request->session()->flash('message', [
            trans('admin/messages.admin_accepted', ['node' => $node->name])
        ]);
        return redirect()->back();
    }
}
