<?php

namespace App\Event;

use Illuminate\Support\Facades\DB;

use App\BaseModel;
use App\Node\Node;
use App\Producer\Producer;

use \DateTime;

class Event extends BaseModel
{
    protected $appends = ['location'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'owner_id' => 'required',
        'owner_type' => 'required|in:producer,node',
        'name' => 'required',
        'info' => 'required',
        'address' => 'required',
        'zip' => 'required',
        'city' => 'required',
        'start_datetime' => 'required',
        'end_datetime' => 'required',
        'fee' => 'integer',
        'is_hidden' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'owner_type',
        'name',
        'info',
        'address',
        'zip',
        'city',
        'start_datetime',
        'end_datetime',
        'fee',
        'is_hidden'
    ];

    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($event) {
            $event->userLinks()->each->delete();
            $event->permalink()->delete();
        });

        static::created(function($event) {
            \App\Permalink::create(['entity_type' => 'event', 'entity_id' => $event->id, 'slug' => $event->name]);
        });

        static::updated(function($event) {
            $permalink = $event->permalink();
            $permalink->slug = $event->name;
            $permalink->save();
        });
    }

    /**
     * Get event owner.
     */
    public function owner()
    {
        $className = null;
        if ($this->owner_type === 'node') {
            $className = Node::class;
        } else if ($this->owner_type === 'producer') {
            $className = Producer::class;
        }

        return $this->hasOne($className, 'id', 'owner_id')->first();
    }

    /**
     * Get all admin users for this event.
     *
     * @return Collection
     */
    public function admins()
    {
        return $this->owner()->adminLinks()->map(function($adminLink) {
            return $adminLink->getUser();
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
     * Return start date object.
     *
     * @param string $value
     * @return Date
     */
    public function getStartDatetimeAttribute($value)
    {
        return $value ? new DateTime($value) : null;
    }

    /**
     * Return end date object.
     *
     * @param string $value
     * @return Date
     */
    public function getEndDatetimeAttribute($value)
    {
        return $value ? new DateTime($value) : null;
    }

    /**
     * Get DatePeriod object for the event period.
     *
     * @return DatePeriod
     */
    public function getDatePeriod()
    {
        $interval = new \DateInterval('P1D');
        return new \DatePeriod($this->start_datetime, $interval, $this->end_datetime->modify('+1 day'));
    }

    /**
     *Get permalink.
     */
    public function permalink()
    {
        return $this->hasOne('App\Permalink', 'entity_id')->where('entity_type', 'event')->first();
    }

    /**
     * Get user links.
     */
    public function userLinks()
    {
        return $this->hasMany('App\Event\EventUserLink')->get();
    }

    /**
     * Get images.
     */
    public function images()
    {
        $images = $this->hasMany('App\Image\Image', 'entity_id')->where('entity_type', 'event')->get();

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
     * Load relationsship and other data form map ajax responses.
     */
    public function loadMapData()
    {
        $this->permalink = $this->permalink();

        return $this;
    }

    public function uniqueKey()
    {
        return $this->owner_type . '_' . $this->owner_id;
    }
}
