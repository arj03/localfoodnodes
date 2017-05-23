<?php

namespace App\Order;

use \DateTime;

class OrderDate extends \App\BaseModel
{
    public $timestamps = false;

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
     * Get all links related to this date.
     *
     * @return Collection
     */
    public function orderDateItemLinks($producerId = null)
    {
        if ($producerId) {
            return $this->hasMany('App\Order\OrderDateItemLink')->get()->filter(function($orderItemDateLink) use ($producerId) {
                return $orderItemDateLink->getItem()->producer_id == $producerId;
            });
        } else {
            return $this->hasMany('App\Order\OrderDateItemLink')->get();
        }
    }
}
