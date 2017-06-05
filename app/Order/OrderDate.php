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
    public function orderDateItemLinks($userId = null, $producerId = null)
    {
        if ($userId) {
            return $this->hasMany('App\Order\OrderDateItemLink')->where('user_id', $userId)->get();
        } else if ($producerId) {
            return $this->hasMany('App\Order\OrderDateItemLink')->where('producer_id', $producerId)->get();
        } else {
            return collect([]);
        }
    }
}
