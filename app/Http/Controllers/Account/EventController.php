<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\Controller;
use App\Event\Event;
use App\Event\EventOwnerFactory;
use App\Image\Image;
use App\Helpers\GoogleMapsHelper;

class EventController extends Controller
{
    private $eventOwner;

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
            $eventId = $request->route('eventId');

            $this->eventOwner = EventOwnerFactory::create($request->route('ownerType'), $request->route('ownerId'));

            // No event id so nothing to check, continue to controller action
            if (!$eventId) {
                return $next($request);
            }

            $errorMessage = trans('admin/messages.request_no_event');

            $event = Event::find($eventId);
            if (!$event) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/user');
            }

            $admins = $event->admins()->pluck('id');
            if (!$admins->contains($user->id)) {
                $request->session()->flash('error', [$errorMessage]);
                return redirect('/account/user');
            }

            return $next($request);
        });
    }

    /**
     * List events.
     *
     * @param Request $request
     * @param int $prducerId
     */
    public function index(Request $request)
    {
        return view('account.event.index', [
            'eventOwner' => $this->eventOwner,
            'breadcrumbs' => [
                $this->eventOwner->name => $this->eventOwner->eventOwnerUrl(),
                trans('admin/user-nav.events') => ''
            ]
        ]);
    }

    /**
     * Create event description.
     *
     * @param Request $request
     * @param string $ownerType
     * @param int $ownerId
     */
    public function create(Request $request)
    {
        $event = new Event();
        $event->fill($request->old());

        return view('account.event.create', [
            'event' => $event,
            'eventOwner' => $this->eventOwner,
            'breadcrumbs' => [
                $this->eventOwner->name => $this->eventOwner->eventOwnerUrl(),
                trans('admin/user-nav.events') => $this->eventOwner->eventOwnerUrl() . '/events',
                trans('admin/user-nav.create_event') => ''
            ]
        ]);
    }

    /**
     * Insert event action.
     *
     * @param Request $request
     * @param GoogleMapsHelper $googleMapsHelper
     */
    public function insert(Request $request, GoogleMapsHelper $googleMapsHelper)
    {
        $user = Auth::user();
        $data = $request->all();
        $event = new Event();

        $errors = $event->validate($data);
        if ($errors->isEmpty()) {
            $event->fill($event->sanitize($data));

            $latLng = $googleMapsHelper->getLatLngForDb($event->getAddressFields());
            $event->setLocation($latLng);
            $event->save();

            $this->uploadImage($request, $event);

            $request->session()->flash('message', [trans('admin/messages.event_created')]);

            $eventOwner = EventOwnerFactory::create($data['owner_type'], $data['owner_id']);
            $url = '/account/' . $eventOwner->eventOwnerUrl() . '/event/' . $event->id . '/edit';
            return redirect($url);
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Edit event action.
     *
     * @param Request $request
     * @param int $eventId
     */
    public function edit(Request $request, $ownerType, $ownerId, $eventId)
    {
        $user = Auth::user();
        $event = Event::find($eventId);

        return view('account.event.edit', [
            'event' => $event,
            'eventOwner' => $this->eventOwner,
            'breadcrumbs' => [
                $this->eventOwner->name => $this->eventOwner->eventOwnerUrl(),
                trans('admin/user-nav.events') => $this->eventOwner->eventOwnerUrl() . '/events',
                $event->name => '',
            ]
        ]);
    }

    /**
     * Update event action.
     *
     * @param Request $request
     * @param GoogleMapsHelper $googleMapsHelper
     * @param int $eventId
     */
    public function update(Request $request, GoogleMapsHelper $googleMapsHelper, $ownerType, $ownerId, $eventId)
    {
        $user = Auth::user();
        $data = $request->all();
        $event = Event::find($eventId);

        $errors = $event->validate($data);
        if ($errors->isEmpty()) {
            $event->fill($event->sanitize($data));

            $latLng = $googleMapsHelper->getLatLngForDb($event->getAddressFields());
            $event->setLocation($latLng);
            $event->save();

            $this->uploadImage($request, $event);

            $request->session()->flash('message', [trans('admin/messages.event_updated')]);
        }

        return redirect()->back()->withInput()->withErrors($errors);
    }

    /**
     * Delete event action.
     *
     * @param Request $request
     * @param int $eventId
     */
    public function delete(Request $request, $ownerType, $ownerId, $eventId)
    {
        $user = Auth::user();
        $event = Event::find($eventId);
        $admins = $event->admins()->pluck('id');

        if ($admins->contains($user->id)) {
            $event->delete();
            $request->session()->flash('message', [trans('admin/messages.event_deleted')]);
        }

        return redirect('/account/' . $this->eventOwner->eventOwnerUrl() . '/events');
    }

    /**
     * Upload image.
     *
     * @param Request $request
     * @param Producer $producer
     */
    public function uploadImage(Request $request, $event)
    {
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                Image::create([
                    'entity_id' => $event->id,
                    'entity_type' => 'event',
                    'file' => $file,
                    'sort' => 999
                ]);
            }
        }

        if ($request->input('image_sort_order')) {
            foreach ($request->input('image_sort_order') as $imageId => $sortOrder) {
                $image = $event->image($imageId);
                $image->sort = $sortOrder;
                $image->save();
            }
        }
    }

    /**
     * List all attending users for an event.
     */
    public function guests(Request $request, $eventOwnerType, $eventOwnerId, $eventId)
    {
        $user = Auth::user();
        $event = Event::find($eventId);

        return view('account.event.guests', [
            'event' => $event,
            'breadcrumbs' => [
                $this->eventOwner->name => $this->eventOwner->eventOwnerUrl(),
                trans('admin/user-nav.events') => $this->eventOwner->eventOwnerUrl() . '/events',
                $event->name => '',
                trans('admin/user-nav.guests') => ''
            ]
        ]);
    }
}
