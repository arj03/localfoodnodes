<?php

namespace App\Order;

use Illuminate\Database\Eloquent\Collection;

class OrderDate extends \App\BaseModel
{
    public $timestamps = false;

    protected $with = ['orderDateItemLinksRelationship'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'date' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
    ];

    private $orderFilter;

    /**
     * Return date object.
     *
     * @param string $value
     * @return Date
     */
    public function getDateAttribute($value)
    {
        return new \DateTime($value);
    }

    /**
     * Return date.
     *
     * @param string $format
     * @return string
     */
    public function date($format)
    {
        return $this->date->format($format);
    }

    /**
     * Define relationship with orderDateItemLinks.
     *
     * @return [type] [description]
     */
    public function orderDateItemLinksRelationship()
    {
        return $this->hasMany('App\Order\OrderDateItemLink');
    }

    /**
     * Limit OrderDateItemLinks to specifics orders refs. Useful for order emails where not all order for a date can be loaded.
     *
     * @param Collection $orderRefs
     */
    function setOrderFilter($orderRefs)
    {
        $this->orderFilter = $orderRefs;
    }

    /**
     * Get all links related to this date.
     *
     * @return Collection
     */
    public function orderDateItemLinks($userId = null, $producerId = null)
    {
        $orderDateItemLinks = new Collection();

        if ($userId) {
            $orderDateItemLinks = $this->orderDateItemLinksRelationship->where('user_id', $userId);
        } else if ($producerId) {
            $orderDateItemLinks = $this->orderDateItemLinksRelationship->where('producer_id', $producerId);
        } else {
            \Log::error('Error in function orderDateItemLinks: both userId and producerId cannot be null');
        }

        // Limit query to filter
        if ($this->orderFilter) {
            $orderDateItemLinks = $orderDateItemLinks->whereIn('ref', $this->orderFilter);
        }

        return $orderDateItemLinks;
    }
}
