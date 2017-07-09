<?php

namespace App\Producer;

use Illuminate\Support\Facades\DB;

use App\BaseModel;
use App\Event\EventOwnerInterface;
use App\Node\Node;
use App\Node\NodeProducerBlacklist;
use App\Order\OrderDate;
use App\Helpers\DistanceHelper;

class Producer extends BaseModel implements EventOwnerInterface
{
    protected $appends = ['location'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'name' => 'required',
        'address' => 'required',
        'zip' => 'required',
        'city' => 'required',
        'info' => 'required',
        'email' => 'required',
        'currency' => '',
        'payment_info' => '',
        'link_homepage' =>'',
        'link_facebook' => '',
        'link_instagram' => '',
        'link_twitter' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'zip',
        'city',
        'info',
        'email',
        'currency',
        'payment_info',
        'link_homepage',
        'link_facebook',
        'link_instagram',
        'link_twitter',
    ];

    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($producer) {
            $producer->adminLinks()->each->delete();
            $producer->nodeLinks()->each->delete();
            $producer->products()->each->delete();
            $producer->events()->each->delete();
            $producer->blacklists()->each->delete();
            $producer->cartDateItemLinks()->each->delete();
            $producer->orderItems()->each->delete();
            $producer->permalink()->delete();
        });

        static::created(function($producer) {
            \App\Permalink::create(['entity_type' => 'producer', 'entity_id' => $producer->id, 'slug' => $producer->name]);
        });

        static::updated(function($producer) {
            $permalink = $producer->permalink();
            $permalink->slug = $producer->name;
            $permalink->save();
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
        if ($this->attributes['location']) {
            $location = substr($this->attributes['location'], 6);
            $location = substr($location, 0, strpos($location, ')'));
            list ($lat, $lng) = explode(' ', $location);

            return ['lat' => $lat, 'lng' => $lng];
        } else {
            return null;
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
     * Get cart items.
     *
     * @return Collection
     */
    public function cartDateItemLinks()
    {
        $cartItems = $this->hasMany('App\Cart\CartItem', 'producer_id', 'id')->get();

        $cartDateItemLinks = $cartItems->map(function($cartItem) {
            return $cartItem->cartDateItemLinks();
        })->flatten();

        return $cartDateItemLinks;
    }

    /**
     * Get order date item links.
     *
     * @return Collection
     */
    public function orderDateItemLinks()
    {
        return $this->hasMany('App\Order\OrderDateItemLink', 'producer_id', 'id')->get();
    }

    /**
     * Get specific order date item link.
     *
     * @return Collection
     */
    public function orderDateItemLink($orderDateItemLinkId)
    {
        return $this->orderDateItemLinks()->where('id', $orderDateItemLinkId)->first();
    }

    /**
     * Get order dates.
     *
     * @return Collection
     */
    public function orderDates()
    {
        $orderDates = $this->orderDateItemLinks()->map(function($orderDateItemLink) {
            return $orderDateItemLink->getDate() ?: null;
        })->filter()->unique('id');


        $sortedOrderDates = $orderDates->sortByDesc(function($orderDate, $key) {
            return $orderDate->date('Y-m-d');
        });

        return $sortedOrderDates;
    }

    /**
     * Get order date.
     *
     * @return OrderDate
     */
    public function orderDate($orderDateId)
    {
        $orderDateItemLink = $this->orderDateItemLinks()->where('order_date_id', $orderDateId)->first();

        return $orderDateItemLink->getDate();
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

        return $orderDates->last();
    }

    /**
     * Get order items.
     *
     * @return Collection
     */
    public function orderItems()
    {
        return $this->hasMany('App\Order\OrderItem', 'producer_id', 'id')->get();
    }

    /**
     * Get order items grouped by user.
     *
     * @return Collection
     */
    public function orderItemsGroupedByUser()
    {
        return $this->hasMany('App\Order\OrderItem')->get()->groupBy('user_id');
    }

    /**
     * Get permalink.
     */
    public function permalink()
    {
        return $this->hasOne('App\Permalink', 'entity_id')->where('entity_type', 'producer')->first();
    }

    /**
     * Get distance to node.
     *
     * @param Node $node
     * @return int
     */
    public function getDistance(Node $node)
    {
        $distanceHelper = new DistanceHelper();
        return $distanceHelper->getDistance($this->location, $node->location);
    }

    /**
     * Get admin links where active is 1.
     */
    public function adminLinks()
    {
        return $this->hasMany('App\Producer\ProducerAdminLink')->where('active', 1)->get();
    }

    /**
     * Get admin links where active is 0.
     */
    public function adminInvites()
    {
        return $this->hasMany('App\Producer\ProducerAdminLink')->where('active', 0)->get();
    }

    /**
     * Get specific admin invite.
     *
     * @param int $userId
     */
    public function adminInvite($userId)
    {
        return $this->adminInvites()->where('user_id', $userId)->first();
    }

    /**
     * Get nodes.
     */
    public function nodeLinks()
    {
        return $this->hasMany('App\Producer\ProducerNodeLink')->get();
    }

    public function getNodeLinksWhereProductIsSold($productId)
    {
        $nodeLinks = $this->nodeLinks()->filter(function($nodeLink) use ($productId) {
            if ($this->product($productId)) {
                return $this->product($productId)->deliveryLinks()->filter(function($deliveryLink) use ($productId) {
                    return $deliveryLink->getProduct()->id === $productId;
                });
            }
        });

        return $nodeLinks;
    }

    /**
     * Get products.
     */
    public function products()
    {
        return $this->hasMany('App\Product\Product')->get();
    }

    /**
     * Get specific product.
     *
     * @param int $productId
     * @return Product
     */
    public function product($productId)
    {
        return $this->products()->where('id', $productId)->first();
    }

    /**
     * Get node producer blacklist.
     */
    public function blacklists()
    {
        return $this->hasMany('App\Node\NodeProducerBlacklist')->get();
    }

    /**
     * Check if producer is blacklisted at a specific node.
     */
    public function isBlacklisted($nodeId)
    {
        return $this->hasMany('App\Node\NodeProducerBlacklist')->where('node_id', $nodeId)->first();
    }

    /**
     * Get events.
     */
    public function events(\DateTime $date = null)
    {
        $events = $this->hasMany('App\Event\Event', 'owner_id')->where('owner_type', 'producer')->get();

        if ($date) {
            $events = $events->where('start_datetime', '<=', $date)->where('end_datetime', '>=', $date);
        }

        return $events;
    }

    /**
     * Get specific event.
     *
     * @param int $eventId
     * @return Event
     */
    public function event($eventId)
    {
        return $this->events()->where('id', $eventId)->first();
    }

    /**
     * Get images.
     */
    public function images()
    {
        $images = $this->hasMany('App\Image\Image', 'entity_id')->where('entity_type', 'producer')->get();

        return collect($images->sortBy('sort')->all());
    }

    /**
     * Get specific image.
     *
     * @param int $imageId
     * @return Image
     */
    public function image($imageId)
    {
        return $this->images()->where('id', $imageId)->first();
    }

    /**
     * Get distance to provided coordinates.
     *
     * @param float $lat
     * @param float $lng
     * @return float
     */
    public function distance($lat, $lng)
    {
        return 6371 * acos(
            cos(deg2rad($this->location['lat']))
            * cos(deg2rad($lat))
            * cos(deg2rad($lng) - deg2rad($this->location['lng']))
            + sin(deg2rad($this->location['lat']))
            * sin(deg2rad($lat))
        );
    }

    /**
     * @return string
     */
    public function eventOwnerType()
    {
        return 'producer';
    }

    /**
     * @return string
     */
    public function eventOwnerUrl()
    {
        return $this->eventOwnerType() . '/' . $this->id;
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
            'currency' => $this->currency,
            'payment_bank' => $this->payment_bank,
            'payment_account' => $this->payment_account,
            'payment_swish' => $this->payment_swish,
            'payment_info' => $this->payment_info,
        ];
    }
}
