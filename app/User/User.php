<?php

namespace App\User;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

use App\User\BaseUser;
use App\Producer\Producer;
use App\Node\NodeAdminLink;
use App\Order\OrderItemDateLink;
use App\Mail\ResetPassword as ResetPasswordNotification;

use Mail;

class User extends BaseUser
{
    use Notifiable;

    protected $appends = ['location'];

    protected $validationRules = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:8',
        'address' => '',
        'zip' => '',
        'city' => '',
        'language' => '',
        'active' => ''
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'zip',
        'city',
        'language',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($user) {
            if ($user->cartDateItemLinks()->count()) {
                $user->cartDateItemLinks()->each->delete();
            }

            $user->orderDateItemLinks()->each->delete();
            $user->membershipPayments()->each->delete();
            $user->nodeLinks()->each->delete();
            $user->nodeAdminLinks()->each->delete();
            $user->nodeAdminInvites()->each->delete();
            $user->producerAdminLinks()->each->delete();
            $user->eventLinks()->each->delete();
            $user->images()->each->delete();
        });
    }

    /**
     * Get all address fields.
     *
     * @return array
     */
    public function getAddressFields()
    {
        return array_filter($this->attributes, function($value, $key) {
            $addressFields = ['address', 'city', 'zip', 'country'];
            return in_array($key, $addressFields);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Set location.
     *
     * @param string $value
     */
    public function setLocation($value)
    {
        $insertValue = '"POINT(' . $value . ')"';
        $this->attributes['location'] = DB::raw('GeomFromText(' . $insertValue . ')');
    }

    /**
     * Get location.
     */
    public function getLocationAttribute()
    {
        if (isset($this->attributes['location'])) {
            $location = substr($this->attributes['location'], 6);
            $location = substr($location, 0, strpos($location, ')'));
            list ($lat, $lng) = explode(' ', $location);

            return ['lat' => $lat, 'lng' => $lng];
        } else {
            return null; // Needs to be null for location by IP address to work
        }
    }

    /**
     * Add location select to query.
     */
    public function newQuery($excludeDeleted = true)
    {
        $raw = 'astext(location) as location';
        return parent::newQuery($excludeDeleted)->addSelect('*', DB::raw($raw));
    }

    /**
     * Get language name.
     *
     * @return string
     */
    public function getLanguageName()
    {
        if ($this->language) {
            return trans('lang.' . $this->language);
        } else {
            return 'English';
        }
    }

    /**
     * Custom validation rules for update.
     *
     * @param array $data
     * @return array
     */
    public function validateUpdate($data)
    {
        $rules = $this->validationRules;
        $rules['email'] .= ',id,' . $this->id;
        $rules['password'] = '';

        return parent::validate($data, $rules);
    }

    /**
     * Custom validation rules for update password.
     *
     * @param array $data
     * @return array
     */
    public function validateUpdatePassword($data)
    {
        $rules = $this->validationRules;
        $rules['name'] = '';
        $rules['email'] = '';

        return parent::validate($data, $rules);
    }

    /**
     * Get active boolean.
     *
     * @return bool
     */
    public function getActiveAttribute()
    {
        return (bool) $this->attributes['active'];
    }

    /**
     * Cart dates.
     *
     * @param array $dates Filter on dates.
     * @return Collection
     */
    public function cartDates($dates = [])
    {
        $cartDates = $this->hasMany('App\Cart\CartDate');

        if (!empty($dates)) {
            $cartDates->whereIn('date', $dates);
        }

        return $cartDates->get();
    }

    /**
     * Cart items.
     *
     * @param int $productId Filter on product id.
     * @return Collection
     */
    public function cartItems($productId = null, $wheres = [])
    {
        $cartItems = $this->hasMany('App\Cart\CartItem', 'user_id', 'id');

        if ($productId) {
            $cartItems = $cartItems->where('product_id', $productId);
        }

        return $cartItems->get();
    }

    /**
     * Cart items.
     *
     * @param int $productId Filter on product id.
     * @return Collection
     */
    public function cartItem($productId, $nodeId, $variantId = null)
    {
        $cartItems = $this->cartItems($productId)->where('node_id', $nodeId);

        if ($variantId) {
            $cartItems = $cartItems->where('variant_id', $variantId);
        }

        return $cartItems->first();
    }

    /**
     * Define relationship with cart date item link.
     *
     * @return Relationship
     */
    public function cartDateItemLinksRelationship()
    {
        return $this->hasMany('App\Cart\CartDateItemLink');
    }

    /**
     * Get cart date item links.
     *
     * @return Collection
     */
    public function cartDateItemLinks()
    {
        return $this->cartDateItemLinksRelationship;
    }

    /**
     * Get specific cart date item link.
     *
     * @param int $cartDateItemLinkId
     * @return CartDateItemLink
     */
    public function cartDateItemLink($cartDateItemLinkId)
    {
        return $this->cartDateItemLinksRelationship->where('id', $cartDateItemLinkId)->first();
    }

    /**
     * Get order dates.
     *
     * @param array $dates Filter on dates.
     * @return Collection
     */
    public function orderDates($dates = [])
    {
        $orderDates = $this->orderDateItemLinks()->map(function($orderDateItemLink) {
            return $orderDateItemLink->getDate() ?: null;
        })->filter()->unique();

        if (!empty($dates)) {
            $orderDates = $orderDates->whereIn('date', $dates);
        }

        $sortedOrderDates = $orderDates->sortByDesc(function($orderDate, $key) {
            return $orderDate->date('Y-m-d');
        });

        return $sortedOrderDates;
    }

    /**
     * Get next order date.
     *
     * @return OrderDate
     */
    public function getNextOrderDate()
    {
        $orderDates = $this->orderDates();

        $orderDates = $orderDates->filter(function($orderDate) {
            return $orderDate->date >= new \DateTime(date('Y-m-d'));
        });

        return $orderDates->count() > 0 ? $orderDates->last() : null;
    }

    /**
     * Define relationship with order date item links.
     *
     * @return Collection
     */
    public function orderDateItemLinksRelationship()
    {
        return $this->hasMany('App\Order\OrderDateItemLink');
    }

    /**
     * Get order date item links.
     *
     * @return Collection
     */
    public function orderDateItemLinks($producerId = null)
    {
        $orderDateItemLinks = $this->orderDateItemLinksRelationship;

        if ($producerId) {
            $orderDateItemLinks = $orderDateItemLinks->where('producer_id', $producerId);
        }

        return $orderDateItemLinks;
    }

    /**
     * Get specific order date item link.
     *
     * @param int $orderDateItemLinkId
     * @return OrderDateItemLink
     */
    public function orderDateItemLink($orderDateItemLinkId)
    {
        return $this->orderDateItemLinksRelationship->where('id', $orderDateItemLinkId)->first();
    }

    /**
     * Define relationship with membership payments.
     */
    public function membershipPaymentsRelationship()
    {
        return $this->hasMany('App\User\UserMembershipPayment');
    }

    /**
     * Get membership payments.
     */
    public function membershipPayments()
    {
        return $this->membershipPaymentsRelationship;
    }

    /**
     * Get latest membership payment.
     *
     * @return UserMembershipPayment
     */
    public function getLatestMembershipPayment($forceCheck = false)
    {
        if ($forceCheck === false && env('APP_DISABLE_MEMBERSHIP', false) === true) {
            return new UserMembershipPayment([
                'amount' => 0,
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ]);
        }

        return $this->membershipPaymentsRelationship->sortBy('created_at')->last();
    }

    /**
     * Check if user is a member.
     *
     * Todo: implement
     *
     * @return boolean
     */
    public function isMember($forceCheck = false)
    {
        if ($forceCheck === false && env('APP_DISABLE_MEMBERSHIP', false) === true) {
            return true;
        }

        $lastMembershipPayment = $this->getLatestMembershipPayment($forceCheck);

        if ($lastMembershipPayment) {
            return $lastMembershipPayment->expiresInDays() >= 0 ? true : false;
        }

        return false;
    }

    /**
     * Get node links.
     */
    public function nodeLinks()
    {
        return $this->hasMany('App\User\UserNodeLink')->get();
    }

    /**
     * Check if user is added to node.
     *
     * @param int $nodeId
     * @return boolean
     */
    public function isAddedToNode($nodeId)
    {
        $userNodeLink = $this->nodeLinks()->where('node_id', $nodeId);
        return $userNodeLink->count() === 1 ? true : false;
    }

    /**
     * Override laravel default reset password email.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::send('email.reset-password', ['token' => $token], function($message) {
            $message->to($this->email)->subject('Reset password');
        });
    }

    /**
     * Define user relationship with nodes admin links.
     */
    public function nodeAdminLinksRelationship()
    {
        return $this->hasMany('App\Node\NodeAdminLink');
    }

    /**
     * Get nodes admin links.
     */
    public function nodeAdminLinks()
    {
        return $this->nodeAdminLinksRelationship->where('active', 1);
    }

    /**
     * Get a specific node link.
     *
     * @param int $nodeId
     */
    public function nodeAdminLink($nodeId)
    {
        return $this->nodeAdminLinks()->where('node_id', $nodeId)->first();
    }

    /**
     * Node admin invites.
     *
     * @return Collection
     */
    public function nodeAdminInvites()
    {
        return $this->nodeAdminLinksRelationship->where('active', 0);
    }

    /**
     * Node admin invites.
     *
     * @return Collection
     */
    public function nodeAdminInvite($nodeId)
    {
        return $this->nodeAdminInvites()->where('node_id', $nodeId)->first();
    }

    /**
     * Get producer admin links.
     */
    public function producerAdminLinks()
    {
        return $this->hasMany('App\Producer\ProducerAdminLink')->get();
    }

    /**
     * Get a specific producer link.
     *
     * @param int $producerId
     */
    public function producerAdminLink($producerId)
    {
        return $this->producerAdminLinks()->where('producer_id', $producerId)->first();
    }

    /**
     * Producer admin invites.
     *
     * @return Collection
     */
    public function producerAdminInvites()
    {
        $producerAdminInvites = $this->producerAdminLinks()->filter(function($producerAdminLink) {
            return $producerAdminLink->active === 0 ? true : false;
        })->all();

        return collect($producerAdminInvites);
    }

    /**
     *Get event links.
     */
    public function eventLinks()
    {
        return $this->hasMany('App\Event\EventUserLink')->get();
    }

    /**
     * Get a specific event link.
     *
     * @param int $eventId
     */
    public function eventLink($eventId)
    {
        return $this->eventLinks()->where('event_id', $eventId)->first();
    }

    /**
     * Get images.
     */
    public function images()
    {
        $images = $this->hasMany('App\Image\Image', 'entity_id')->where('entity_type', 'user')->get();

        return collect($images->sortBy('sort')->all());
    }

    /**
     * Get specific image.
     * @param int $imageId
     * @return Image
     */
    public function image($imageId)
    {
        return $this->images()->where('id', $imageId)->first();
    }

    /**
     * [isEventAdmin description]
     * @param  [type]  $ownerId   [description]
     * @param  [type]  $ownerType [description]
     * @return boolean            [description]
     */
    public function isEventAdmin($ownerId, $ownerType)
    {
        $isAdmin = false;

        if ($ownerType === 'node' && $this->nodeAdminLink($ownerId)) {
            $isAdmin = true;
        } else if ($ownerType === 'producers' && $this->producerAdminLink($ownerId)) {
            $isAdmin = true;
        }

        return $isAdmin;
    }

    /**
     * [eventOwner description]
     * @return [type] [description]
     */
    public function eventOwner()
    {
        $eventOwner = new Collection();

        if ($this->producerAdminLinks()->count() > 0) {
            $eventOwner->add($this->producerAdminLinks()->map(function($producerAdminLink) {
                $producer = $producerAdminLink->getProducer();

                return [
                    'id' => $producer->id,
                    'unique_key' => 'producer_' . $producer->id,
                    'type' => 'producer',
                    'name' => $producer->name,
                    'object' => $producer
                ];
            }));
        }

        if ($this->nodeAdminLinks()->count() > 0) {
            $eventOwner->add($this->nodeAdminLinks()->map(function($nodeAdminLink) {
                $node = $nodeAdminLink->getNode();

                return [
                    'id' => $node->id,
                    'unique_key' => 'node_' . $node->id,
                    'type' => 'node',
                    'name' => $node->name,
                    'object' => $node
                ];
            }));
        }

        return $eventOwner->flatten(1);
    }

    /**
     * Get info to be stored with order.
     *
     * @return array
     */
    public function getInfoForOrder()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'zip' => $this->zip,
            'city' => $this->city,
            'email' => $this->email,
        ];
    }
}
