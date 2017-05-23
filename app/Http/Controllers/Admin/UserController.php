<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use Mail;

use Stripe\Stripe;
use Stripe\Charge;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User\User;
use App\User\UserMembershipPayment;
use App\User\UserNodeLink;
use App\Image\Image;
use App\Node\Node;
use App\Producer\Producer;
use App\Order\Order;
use App\Event\EventUserLink;

use App\Helpers\GoogleMapsHelper;

use \Exception;

class UserController extends Controller
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
            $orderId = $request->route('orderId');

            if (!$orderId) {
                return $next($request);
            }

            $order = $user->order($orderId);
            if (!$order) {
                $request->session()->flash('error', [trans('admin/messages.request_no_order')]);
                return redirect('/account/user');
            }

            $orderItemId = $request->route('orderItemId');
            $orderItem = $order->item($orderItemId);
            if (!$orderItem) {
                $request->session()->flash('error', [trans('admin/messages.request_no_order_item')]);
                return redirect('/account/user');
            }

            return $next($request);
        });
    }

    /**
     * Activation screen.
     *
     * @param Request $request
     * @param string $token
     */
    public function activate(Request $request)
    {
        $user = Auth::user();

        if ($user->active === 1) {
            return redirect('/account/user');
        }

        return view('admin.user.activate', [
            'breadcrumbs' => [
                $user->name => '',
                trans('admin/user-nav.activate') => ''
            ]
        ]);
    }

    /**
     * Resend activation link.
     *
     * @param Request $request
     * @param string $token
     */
    public function activateResend(Request $request)
    {
        $user = Auth::user();
        $this->sendActivationLink($user);

        $request->session()->flash('message', [trans('admin/messages.user_account_email_sent')]);
        return redirect('/');
    }

    /**
     * Activate user from activation email.
     *
     * @param Request $request
     * @param string $token
     */
    public function activateToken(Request $request, $token)
    {
        $userId = DB::table('user_activations')->select('user_id')->where('token', $token)->value('user_id');
        $user = User::find($userId);

        if ($user) {
            $user->fill(['active' => 1]);
            $user->save();
        }

        DB::table('user_activations')->where('token', $token)->delete();

        $request->session()->flash('message', [trans('admin/messages.user_account_activated')]);
        return redirect('/login');
    }

    /**
     * User index action.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('admin.user.index', [
            'breadcrumbs' => [
                $user->name => '',
                trans('admin/user-nav.dashboard') => ''
            ]
        ]);
    }

    /**
     * User create action.
     */
    public function create(Request $request)
    {
        return view('admin.user.create', [
            'breadcrumbs' => [
                trans('admin/user-nav.create_account') => ''
            ]
        ]);
    }

    /**
     * User insert action.
     */
    public function insert(Request $request)
    {
        $data = $request->all();
        $user = new User();

        $errors = $user->validate($data);
        if ($errors->isEmpty()) {
            $userData = $user->sanitize($data);
            $userData['password'] = bcrypt($userData['password']);
            $user->fill($userData);

            // Default location Röstånga
            $user->setLocation('56.002490 13.293257');

            $user->save();

            $this->sendActivationLink($user);

            Auth::login($user);

            $request->session()->flash('message', [trans('admin/messages.user_account_created')]);

            $request->session()->flash('message', ['Your account has been created and an activation link has been sent to your email.']);
            return redirect('/account/user');
        }

        return redirect('/account/user/create')->withInput()->withErrors($errors);
    }

    /**
     * User edit action.
     */
    public function edit(Request $request)
    {
        $user = Auth::user();
        $user->fill($request->old());

        return view('admin.user.edit', [
            'user' => $user,
            'breadcrumbs' => [
                $user->name => 'user',
                trans('admin/user-nav.edit') => ''
            ]
        ]);
    }

    /**
     * User update action.
     */
    public function update(Request $request, GoogleMapsHelper $googleMapsHelper)
    {
        $user = Auth::user();
        $data = $request->all();

        $errors = $user->validateUpdate($data);
        if ($errors->isEmpty()) {
            $user->fill($user->sanitize($data));

            $latLng = $googleMapsHelper->getLatLngForDb($user->getAddressFields());
            $user->setLocation($latLng);

            $user->save();

            $this->uploadImage($request, $user);

            $request->session()->flash('message', [trans('admin/messages.user_account_updated')]);
            return redirect('/account/user');
        }

        return redirect('/account/user/edit')->withInput()->withErrors($errors);
    }

    /**
     * User delete action.
     */
    public function delete(Request $request)
    {
        $user = Auth::user();
        Auth::logout();

        $user->delete();
        $request->session()->flash('message', [trans('admin/messages.user_account_deleted')]);

        return redirect('/');
    }

    /**
     * Upload image.
     *
     * @param Request $request
     * @param Producer $producer
     */
    public function uploadImage(Request $request, $user)
    {
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                Image::create([
                    'entity_id' => $user->id,
                    'entity_type' => 'user',
                    'file' => $file,
                    'sort' => 999
                ]);
            }
        }

        if ($request->input('image_sort_order')) {
            foreach ($request->input('image_sort_order') as $imageId => $sortOrder) {
                $image = $user->image($imageId);
                $image->sort = $sortOrder;
                $image->save();
            }
        }
    }

    /**
     * Edit password action.
     */
    public function editPassword()
    {
        $user = Auth::user();

        return view('admin.user.edit-password', [
            'breadcrumbs' => [
                $user->name => 'user/' . $user->id,
                trans('admin/user-nav.change_password') => ''
            ]
        ]);
    }

    /**
     * Update password action.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $errors = $user->validateUpdatePassword($data);
        if ($errors->isEmpty()) {
            $user->password = bcrypt($data['password']);
            $user->save();

            $request->session()->flash('message', [trans('admin/messages.user_password_changed')]);
            return redirect('/account/user');
        }

        return redirect('/account/user/password/edit')->withErrors($errors);
    }

    /**
     * Pickups action.
     */
    public function pickups()
    {
        $user = Auth::user();

        return view('admin.user.pickups', [
            'user' => $user,
            'breadcrumbs' => [
                $user->name => 'user',
                trans('admin/user-nav.pickups') => ''
            ]
        ]);
    }

    /**
     * Orders action.
     */
    public function orders()
    {
        $user = Auth::user();

        return view('admin.user.orders', [
            'user' => $user,
            'breadcrumbs' => [
                $user->name => 'user',
                trans('admin/user-nav.orders') => ''
            ]
        ]);
    }

    /**
     * Orders action.
     */
    public function order(Request $request, $orderDateItemLinkId)
    {
        $user = Auth::user();
        $orderDateItemLink = $user->orderDateItemLink($orderDateItemLinkId);

        return view('admin.user.order', [
            'user' => $user,
            'orderDateItemLink' => $orderDateItemLink,
            'orderDate' => $orderDateItemLink->getDate(),
            'orderItem' => $orderDateItemLink->getItem(),
            'breadcrumbs' => [
                $user->name => 'user',
                trans('admin/user-nav.orders') => 'user/orders',
                trans('admin/user-nav.order') . ' #' . $orderDateItemLink->ref => ''
            ]
        ]);
    }

    /**
     * Membership action.
     *
     * @param  Request $request
     */
    public function membership(Request $request)
    {
        $user = Auth::user();

        return view('admin.user.membership', [
            'breadcrumbs' => [
                $user->name => 'user',
                trans('admin/user-nav.membership') => ''
            ]
        ]);
    }

    /**
     * Handle membership callback action.
     */
    public function membershipCallback(Request $request)
    {
        $user = Auth::user();
        $errors = new MessageBag();

        Stripe::setApiKey(config('payment.stripe.live.secret_key'));

        try {
            $token = $request->input('stripeToken');
            $amount = $request->input('amount');

            if (!is_numeric($amount)) {
                throw new Exception(trans('admin/messages.user_membership_amount_not_numeric'));
            }

            $amount = ((int) $amount) * 100;

            $charge = Charge::create(array(
                'amount' => $amount,
                'currency' => 'sek',
                'source' => $token,
                'description' => $user->name
            ));

            if ($charge['status'] === 'succeeded') {
                UserMembershipPayment::create([
                    'user_id' => $user->id,
                    'amount' => $amount
                ]);
            }
        } catch(Exception $e) {
            $errors->add('payment', $e->getMessage());
            $request->session()->flash('message', [trans('admin/messages.user_membership_error')]);
            return redirect('/account/user/membership')->withErrors($errors);
        }

        $request->session()->flash('message', [trans('admin/messages.user_membership_success')]);
        return redirect('/account/user');
    }

    /**
     * Add or remove node from user.
     */
    public function toggleNode(Request $request, $nodeId)
    {
        $user = Auth::user();
        $userNodeLink = UserNodeLink::where('user_id', $user->id)->where('node_id', $nodeId)->first();
        if ($userNodeLink) {
            $userNodeLink->delete();
        } else {
            UserNodeLInk::create([
                'user_id' => $user->id,
                'node_id' => $nodeId
            ]);
        }

        return redirect()->back();
    }

    /**
     * User event action.
     */
    public function events()
    {
        $user = Auth::user();

        return view('admin.user.events', [
            'breadcrumbs' => [
                $user->name => 'user',
                trans('admin/user-nav.events') => ''
            ]
        ]);
    }

    /**
     * Add or remove user from event.
     */
    public function toggleEvent(Request $request, $eventId)
    {
        $user = Auth::user();
        $eventUserLink = EventUserLink::where(['user_id' => $user->id, 'event_id' => $eventId])->first();
        if ($eventUserLink) {
            $eventUserLink->delete();
        } else {
            EventUserLInk::create([
                'user_id' => $user->id,
                'event_id' => $eventId
            ]);
        }

        return redirect()->back();
    }

    /**
     * Send activation link.
     *
     * @param User $user
     */
    private function sendActivationLink($user)
    {
        // Clear
        DB::table('user_activations')->where('user_id', $user->id)->delete();

        // Create new token
        $token = hash_hmac('sha256', str_random(40), config('app.key'));
        DB::table('user_activations')->insert(['user_id' => $user->id, 'token' => $token]);

        Mail::send('email.activate-user', ['user' => $user, 'token' => $token], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Activate account');
        });
    }
}
