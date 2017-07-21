<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        if ($user->active) {
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

        if (!$userId) {
            \App\Helpers\SlackHelper::message('error', 'User id ' . $userId . ' does not exist and cannot be activated. Token user: ' . $token);
        }

        $user = User::find($userId);

        if ($user) {
            $user->fill(['active' => 1]);
            $user->save();

            DB::table('user_activations')->where('token', $token)->delete();
        } else {
            \Log::debug('error 1');
            \App\Helpers\SlackHelper::message('error', 'User with id ' . $userId . ' could not be found. Activation failed.');

            $request->session()->flash('error', [trans('admin/messages.user_account_activation_failed')]);
            return redirect('/login?error=activation_failed');
        }

        $request->session()->flash('message', [trans('admin/messages.user_account_activated')]);
        return redirect('/login?message=activation_complete');
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
        if (Auth::check()) {
            return redirect('/account/user');
        }

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
            $userData['password'] = \Hash::make($userData['password']);
            $user->fill($userData);

            // Default location Röstånga
            $user->setLocation('56.002490 13.293257');
            $user->save();

            \App\Helpers\SlackHelper::message('notification', $user->name . ' (' . $user->email . ')' . ' signed up as a user.');

            $this->sendActivationLink($user);

            Auth::login($user);

            $request->session()->flash('message', [trans('admin/messages.user_account_created')]);
            $request->session()->flash('welcome_modal', true);

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
     * Confirm delete action.
     */
    public function deleteConfirm(Request $request)
    {
        $user = Auth::user();

        return view('admin.user.confirm-delete', [
            'breadcrumbs' => [
                $user->name => 'user',
                trans('admin/user-nav.delete') => ''
            ]
        ]);
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
    public function updatePassword(Request $request, GoogleMapsHelper $googleMapsHelper)
    {
        $user = Auth::user();
        $data = $request->all();

        $errors = $user->validateUpdatePassword($data);
        if ($errors->isEmpty()) {
            $user->password = \Hash::make($data['password']);

            // Fix for migrated users. Update position if user have an address.
            if ($user->address && ($user->city || $user->zip)) {
                $latLng = $googleMapsHelper->getLatLngForDb($user->getAddressFields());
                $user->setLocation($latLng);
            }

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
    public function producerOrders(Request $request, $producerId)
    {
        $user = Auth::user();
        $producer = Producer::find($producerId);
        $orderDateItemLinks = $user->orderDateItemLinks($producerId);

        return view('admin.user.producer-orders', [
            'orderDateItemLinks' => $orderDateItemLinks,
            'breadcrumbs' => [
                $user->name => 'user',
                trans('admin/user-nav.pickups') => 'user/pickups',
                $producer->name => ''
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
     * Delete order item action.
     */
    public function deleteOrderItem(Request $request, $orderDateItemLinkId)
    {
        $user = Auth::user();

        $orderDateItemLink = $user->orderDateItemLink($orderDateItemLinkId);
        $orderDateItemLink->delete();
        $request->session()->flash('message', [trans('admin/messages.order_deleted')]);

        return redirect('/account/user/pickups');
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

            // If user pays less than 3SEK (stripe limit)
            if ($amount < 3) {
                UserMembershipPayment::create([
                    'user_id' => $user->id,
                    'amount' => $amount * 100
                ]);

                \App\Helpers\SlackHelper::message('notification', $user->name . ' (' . $user->email . ')' . ' payed ' . $request->input('amount') . 'SEK to become a member.');

                $request->session()->flash('membership_modal_no_charge', true);
                return redirect('/account/user/membership');
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

                $request->session()->flash('membership_modal_thanks', true);
                $request->session()->flash('message', [trans('admin/messages.user_membership_success')]);

                \App\Helpers\SlackHelper::message('notification', $user->name . ' (' . $user->email . ')' . ' payed ' . $request->input('amount') . 'SEK to become a member.');
            }
        } catch(Exception $e) {
            $errors->add('payment', $e->getMessage());

            $request->session()->flash('message', [
                trans('admin/messages.user_membership_error', [
                    'errors' => implode($errors->all(), '')
                ])
            ]);
        }

        return redirect('/account/user/membership');
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
        \Log::debug(var_export($user, true));
        $token = DB::table('user_activations')->select('token')->where('user_id', '=', $user->id)->value('token');

        if (!$token) {
            // Clear.
            DB::table('user_activations')->where('user_id', $user->id)->delete();
            // Create new token and insert to database.
            DB::table('user_activations')->insert(['user_id' => $user->id, 'token' => hash_hmac('sha256', str_random(64), config('app.key'))]);
            // Get token from database to ensure that we send the correct one in the email.
            $token = DB::table('user_activations')->select('token')->where('user_id', '=', $user->id)->value('token');
        }

        Mail::send('email.activate-user', ['user' => $user, 'token' => $token], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject(trans('public/email.activate_your_account'));
        });
    }

    public function migrateEditAccount(Request $request)
    {
        return view('admin.user.migrate-edit', [
            'breadcrumbs' => [
                trans('admin/user.recreate_account') => ''
            ]
        ]);
    }

    public function migrateUpdateAccount(Request $request)
    {
        $oldUsers = collect(array_map('strtolower', [
            'davidajnered@gmail.com',
            '4114achansson@telia.com',
            'ada.wraneus@telia.com',
            'agatkabielska@gmail.com',
            'Albin.Sunesson@gmail.com',
            'albin@ponnert.se',
            'albrechtdennis@gmail.com',
            'Alexandra.ronnangsgard@hotmail.com',
            'Alexandra1korhonen@gmail.com',
            'amelie_andersson@hotmail.com',
            'andrea_flycht@hotmail.com',
            'anette@boang.se',
            'anna.haraldson@gmail.com',
            'anna.strangby@gmail.com',
            'anna@hulander.com',
            'Annette.linander@centerpartiet.se',
            'annika_jons@hotmail.com',
            'annsarner@hotmail.com',
            'asa.waldenstrom@hotmail.com',
            'aylamm@gmail.com',
            'braiten@hotmail.com',
            'calle72cl@gmail.com',
            'carlsson.kristina@gmail.com',
            'carolina@pojmarklamm.se',
            'christel.soderholm@gmail.com',
            'christina.johnsson@gmail.com',
            'cynthiareynolds@mac.com',
            'danieldoppsko@hotmail.com',
            'david@roxendal.com',
            'elvir.olsson@gmail.com',
            'Elvira.edenlund@hotmail.com',
            'Erik.starck@gmail.com',
            'evalottaoberg64@gmail.com',
            'evasmeds@gmail.com',
            'ewa@ortagarden.nu',
            'filip.roos@gmail.com',
            'flanders303@gmail.com',
            'folkeg@gmail.com',
            'frank@remmarlov.se',
            'frebro@gmail.com',
            'fredrik_a_nilsson@hotmail.com',
            'fridahansson__@hotmail.com',
            'frulaurin@gmail.com',
            'Frustrand@gmail.com',
            'grahnjohan@hotmail.com',
            'hakan@ekdahls.com',
            'Hanna-Mia.hellberg@eslov.se',
            'hanna.norrby@gmail.com',
            'hellalinda@hotmail.com',
            'info@kokstradgarden.nu',
            'info@lovbergsostar.se',
            'info@tranaslund.se',
            'Info@vilhelminagladkyckling.se',
            'ingerochbosse@gmail.com',
            'ingrid@billingehill.se',
            'jenny.bergh@gmail.com',
            'jennyjosefin@gmail.com',
            'jens@narjord.se',
            'jesper.brantefors@gmail.com',
            'Jocke.bson@gmail.com',
            'johan.navelso@gmail.com',
            'johan.nyman@hotmail.com',
            'Johan@enbom.se',
            'johanna@bosarpsgard.se',
            'johnguslen@hotmail.com',
            'johroger@gmail.com',
            'jonas.widriksson@outlook.com',
            'jonaseco@hotmail.com',
            'jonny.wikstrom@siljansnasresursen.se',
            'k_wigrup@hotmail.com',
            'karinjohansson91@live.se',
            'karinwelinkjellman@gmail.com',
            'kasebergaodlingen@gmail.com',
            'katariinalundqvist@hotmail.com',
            'kate.haeggstrom@farmartjanst.se',
            'katti1503@gmail.com',
            'kick@viltogardsprodukter.se',
            'kingemansson254@gmail.com',
            'Klarakarnerud@yahoo.se',
            'kristina_surf@yahoo.com',
            'kristoffer.ristinmaa@gmail.com',
            'lammagarden@hotmail.se',
            'le.mller@telia.com',
            'lena.liljemark@ljustorp.se',
            'lena.norrstrom@gmail.com',
            'lenadera@gmail.com',
            'lenakarinlundin@gmail.com',
            'lennart.olsson15@bredband.net',
            'lethorning@gmail.com',
            'Lindawicks.th@gmail.com',
            'lindenkisse1@gmail.com',
            'lisatidmanfuchs@gmail.com',
            'liselott.lantz@telia.com',
            'Liselotthagg@gmail.com',
            'Liza.lang82@gmail.com',
            'lotta@poppelskogen.se',
            'louiselagergren@gmail.com',
            'lyckerodlantgard@gmail.com',
            'm_sjosten@hotmail.com',
            'malena.ekholm@gmail.com',
            'malinengdahl@spray.se',
            'margareta.zaunders@telia.com',
            'maria.persson.martinsson@telia.com',
            'maria@rikkenstorp.se',
            'Marie-vestin@live.se',
            'marika@paxbrygghus.se',
            'marta.herrlin@gmail.com',
            'mimmi.lowejko@gmail.com',
            'moa.nyman@spray.se',
            'mona@frugran.se',
            'mullaert@me.com',
            'Nc@fornkorn.se',
            'nils@hasselconsulting.se',
            'ninnabjorne@gmail.com',
            'nordahlbirgitta@hotmail.com',
            'norrfrid@hotmail.com',
            'norrlidenlamm@gmail.com',
            'ola.ohlson@gmail.com',
            'olssonslovisa@gmail.com',
            'olsvenne@Gotland.at',
            'oscar.hjerpe@gmail.com',
            'oskar@ponnert.en',
            'patrik.ivarssons@gmail.com',
            'patrik.mansson@gmail.com',
            'petra@petrasundberg.se',
            'philemonarthur@hotmail.com',
            'polcirkelngardsprodukter@gmail.com',
            'post@karinlilja.se',
            'Rasmus@sumsar.se',
            'roger@langbergetsgard.se',
            'sarafrostberg@hotmail.com',
            'sarasockerbit@gmail.com',
            'sarner2@gmail.com',
            'simone@undertallarna.se',
            'sissela.ekdahl@hotmail.com',
            'stefan.persson@rhefab.se',
            'stina@gamlasalteriet.se',
            'susannascollie@hotmail.com',
            'tearjerker@bredband.net',
            'Tina@sverige.nu',
            'tony.heine@hotmail.com',
            'Tove.bergman@hotmail.se',
            'ullae.strandberg@gmail.com',
            'vadensjo1@hotmail.com',
            'victorwassman@gmail.com',
            'zaunders@gmail.com',
        ]));

        // Not and old user, redirect to create account page
        if (!$oldUsers->contains(strtolower($request->input('email')))) {
            $request->session()->flash('message', [trans('admin/messages.user_migrate_not_valid')]);
            return redirect('/account/user/create');
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user->password !== null) {
            $request->session()->flash('message', [trans('admin/messages.user_migrate_already_exists')]);
            return redirect('/login');
        }

        $validator = \Validator::make($request->all(), [
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $user->password = Hash::make($request->input('password'));
            $user->save();

            $this->sendActivationLink($user);

            Auth::login($user);

            $request->session()->flash('message', [trans('admin/messages.user_migrate_done')]);
            $request->session()->flash('welcome_modal', true);

            return redirect('/account/user');
        }
    }
}
