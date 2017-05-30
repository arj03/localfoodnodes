<?php

namespace App\Node;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

use App\BaseModel;
use App\Event\EventOwnerInterface;
use App\Node\NodeDeliveryDate;

use DateTime;

class Node extends BaseModel implements EventOwnerInterface
{
    protected $appends = ['location'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'name' => 'required',
        'info' => 'required',
        'address' => 'required',
        'zip' => 'required',
        'city' => 'required',
        'email' => 'required',
        'delivery_time' => 'required',
        'delivery_weekday' => 'required',
        'link_facebook' => '',
        'link_facebook_producers' => '',
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'info',
        'address',
        'zip',
        'city',
        'email',
        'delivery_time',
        'delivery_weekday',
        'link_facebook',
        'link_facebook_producers',
    ];

    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($node) {
            $node->adminLinks()->each->delete();
            $node->userLinks()->each->delete();
            $node->producerLinks()->each->delete();
            $node->events()->each->delete();
            $node->images()->each->delete();
            $node->permalink()->delete();
        });

        static::created(function($node) {
            \App\Permalink::create(['entity_type' => 'node', 'entity_id' => $node->id, 'slug' => $node->name]);
        });

        static::updated(function($node) {
            $permalink = $node->permalink();
            $permalink->slug = $node->name;
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
     * Add location select to query.
     */
    public function newQuery($excludeDeleted = true)
    {
        $raw = 'astext(location) as location';
        return parent::newQuery($excludeDeleted)->addSelect('*', DB::raw($raw));
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
        }
    }

    /**
     * Get node admin links.
     *
     * @return Collection
     */
    public function adminLinks()
    {
        return $this->hasMany('App\Node\NodeAdminLink')->where('active', 1)->get();
    }

    /**
     * Get node admin invites.
     *
     * @return Collection
     */
    public function adminInvites()
    {
        return $this->hasMany('App\Node\NodeAdminLink')->where('active', 0)->get();
    }

    /**
     * Define producer link relationship.
     *
     * @return Collection
     */
    public function producerLinksRelationship()
    {
        return $this->hasMany('App\Producer\ProducerNodeLink');
    }

    /**
     * Get producer links.
     */
    public function producerLinks()
    {
        return $this->producerLinksRelationship;
    }

    /**
     * Return number of producers connected to this node.
     *
     * @return int
     */
    public function producerCount()
    {
        return $this->producerLinks()->count();
    }

    /**
     * Get user links.
     */
    public function userLinks()
    {
        return $this->hasMany('App\User\UserNodeLink')->get();
    }

    /**
     * Get permalink.
     */
    public function permalink()
    {
        return $this->hasOne('App\Permalink', 'entity_id')->where('entity_type', 'node')->first();
    }

    /**
     * Get events.
     */
    public function events(\DateTime $date = null)
    {
        $events = $this->hasMany('App\Event\Event', 'owner_id')->where('owner_type', 'node')->get();

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
     * [getAllEvents description]
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    public function getAllEvents(\DateTime $date = null)
    {
        $allEvents = $this->events($date);
        $allEvents = $allEvents->merge($this->getProducerEvents($date));

        return $allEvents->sortBy('start_datetime');
    }

    /**
     * [getProducerEvents description]
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    private function getProducerEvents(\DateTime $date = null)
    {
        $producerEvents = new Collection();

        if ($this->producerLinks()->count() > 0) {
            $this->producerLinks()->each(function($producerLink) use (&$producerEvents, $date) {
                if ($producerLink->getProducer()->events($date)->count() > 0) {
                    $events = $producerLink->getProducer()->events($date);
                    $producerEvents = $producerEvents->merge($events);
                }
            });
        }

        return $producerEvents;
    }

    /**
     * Get images.
     */
    public function images()
    {
        return $this->hasMany('App\Image\Image', 'entity_id')->where('entity_type', 'node')->get()->sortBy('sort');
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
     * Define node relationship with product delivery dates.
     */
    public function productsRelationship()
    {
        return $this->hasMany('App\Product\ProductNodeDeliveryLink');
    }

    /**
     * Get products.
     */
    public function products()
    {
        $products = $this->productsRelationship->map(function($productNodeDeliveryLink) {
            return $productNodeDeliveryLink->getProduct();
        });

        if (!$products->isEmpty()) {
            return $products->unique('id')->filter();
        }

        return new Collection();
    }

    /**
     * Load relationsship and other data form map ajax responses.
     */
    public function loadMapData()
    {
        $this->producerLinks = $this->producerLinks();
        $this->userLinks = $this->userLinks();
        $this->permalink = $this->permalink();
        $this->averageProducerDistance = $this->getAverageProducerDistance();

        return $this;
    }

    /**
     * Get delivery dates for the next 52 weeks.
     *
     * @return Collection
     */
    public function getDeliveryDates()
    {
        $deliveryDates = new Collection();

        $nextDelivery = $this->getNextDelivery();

        for ($week = 0; $week < 52; $week++) {
            $date = date('Y-m-d', strtotime('+' . $week . ' week', $nextDelivery->getTimestamp()));
            $deliveryDates->push($date);
        }

        return $deliveryDates->sort();
    }

    /**
     * Get delivery dates by months.
     *
     * @return Collection
     */
    public function getDeliveryDatesByMonths()
    {
        return collect($this->getDeliveryDates()->groupBy(function($date) {
            return (int) date('Ym01', strtotime($date));
        }));
    }

    /**
     * Get next delivery date.
     *
     * @return Date
     */
    private function getNextDelivery()
    {
        $nextDeliveryTimestamp = strtotime('next ' . $this->delivery_weekday, time());
        return new DateTime(date('Y-m-d H:i', $nextDeliveryTimestamp));
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
     * Get average producer distance.
     *
     * @return int
     */
    public function getAverageProducerDistance()
    {
        if ($this->producerLinks()->count() <= 0) {
            return 0;
        }

        $averageProducerDistance = $this->producerLinks()->map(function($producerLink)  {
            $distance = $this->distance($producerLink->getProducer()->location['lat'], $producerLink->getProducer()->location['lng']);
            return $distance;
        })->sum();

        return round($averageProducerDistance / $this->producerLinks()->count(), 1);
    }

    /**
     * @return string
     */
    public function eventOwnerType()
    {
        return 'node';
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
            'delivery_time' => $this->delivery_time,
            'delivery_weekday' => $this->delivery_weekday,
        ];
    }
}
