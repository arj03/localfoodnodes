<?php

namespace App\Order;

class OrderDateItemLink extends \App\BaseModel
{
    protected $with = ['orderItemRelationship', 'orderDateRelationship'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'user_id' => 'required',
        'producer_id' => 'required',
        'order_item_id' => 'required',
        'order_date_id' => 'required',
        'quantity' => 'required',
        'ref' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'producer_id',
        'order_item_id',
        'order_date_id',
        'quantity',
        'ref',
    ];

    /**
     * Define relationship with order items.
     *
     * @return Collection
     */
    public function orderItemRelationship()
    {
        return $this->hasMany('App\Order\OrderItem', 'id', 'order_item_id');
    }

    /**
     * Get order item.
     *
     * @return OrderItem
     */
    public function getItem()
    {
        return $this->orderItemRelationship->first();
    }

    /**
     * Define relationship with order dates.
     *
     * @return OrderDate
     */
    public function orderDateRelationship()
    {
        return $this->hasMany('App\Order\OrderDate', 'id', 'order_date_id');
    }

    /**
     * Get order date.
     *
     * @return OrderDate
     */
    public function getDate()
    {
        return $this->orderDateRelationship->first();
    }

    /**
    * Get order item price.
    *
    * @return int
    */
    public function getPrice()
    {
        if ($this->getItem()->variant) {
            return $this->getItem()->variant['price'] * $this->quantity;
        } else {
            return $this->getItem()->product['price'] * $this->quantity;
        }
    }
}
