<?php

namespace App\Cart;

use \DateTime;

class CartDateItemLink extends \App\BaseModel
{
    public $timestamps = false;

    protected $with = ['cartItemRelationship', 'cartDateRelationship'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'user_id' => 'required',
        'cart_id' => 'required',
        'cart_item_id' => 'required',
        'cart_date_id' => 'required',
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
        'cart_id',
        'cart_item_id',
        'cart_date_id',
        'quantity',
        'ref',
    ];

    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($cartDateItemLink) {
            // If CartItem's link count is 1, this is the last CartItemDateLink connected and is safe to remove.
            if ($cartDateItemLink->getItem()->cartDateItemLinks()->count() <= 1) {
                $cartDateItemLink->getItem()->delete();
            }

            // If CartDate count is 1, this is the last CartItemDateLink connected and is save to remove.
            if ($cartDateItemLink->getDate()->cartDateItemLinks()->count() <= 1) {
                $cartDateItemLink->getDate()->delete();
            }
        });
    }

    /**
     * Define relationship with cart items.
     *
     * @return Collection
     */
    public function cartItemRelationship()
    {
       return $this->hasMany('App\Cart\CartItem', 'id', 'cart_item_id');
    }

    /**
     * Get cart item.
     *
     * @return CartItem
     */
    public function getItem()
    {
       return $this->cartItemRelationship->first();
    }

    /**
     * Define relationship with cart dates.
     *
     * @return Collection
     */
    public function cartDateRelationship()
    {
       return $this->hasMany('App\Cart\CartDate', 'id', 'cart_date_id');
    }

    /**
     * Get cart date.
     *
     * @return CartDate
     */
    public function getDate()
    {
       return $this->cartDateRelationship->first();
    }

    /**
    * Get cart item price.
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
