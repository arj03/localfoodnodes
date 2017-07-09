<?php

namespace App\Order;

use \DateTime;

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
        'user_id' => 'required',
        'date' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'date',
    ];

    /**
     * Return date object.
     *
     * @param string $value
     * @return Date
     */
    public function getDateAttribute($value)
    {
        return new DateTime($value);
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
     * Get all links related to this date.
     *
     * @return Collection
     */
    public function orderDateItemLinks($userId = null, $producerId = null)
    {
        if ($userId) {
            return $this->orderDateItemLinksRelationship->where('user_id', $userId);
        } else if ($producerId) {
            return $this->orderDateItemLinksRelationship->where('producer_id', $producerId);
        } else {
            \Log::error('Error in function orderDateItemLinks: both userId and producerId cannot be null');
            return collect([]);
        }
    }
}
