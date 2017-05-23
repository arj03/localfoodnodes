<?php

namespace App\Cart;

use \DateTime;

class CartDate extends \App\BaseModel
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
    public function cartDateItemLinks()
    {
        return $this->hasOne('App\Cart\CartDateItemLink')->get();
    }
}
