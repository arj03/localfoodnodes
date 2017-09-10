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

            // If CartDate count is 1, this is the last CartItemDateLink connected and is safe to remove.
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
    * Get item price.
    *
    * @return int
    */
    public function getPrice()
    {
        $price = $this->getItem()->variant ?  $this->getItem()->variant['price'] : $this->getItem()->product['price'];

        if ($this->getItem()->product['production_type'] === 'csa') {
            $csaPrice = $price / $this->getItem()->getProduct()->deliveryLinks()->count();
            return round($csaPrice);
        } else if (\UnitsHelper::isStandardUnit($this->getItem()->product['price_unit'])) {
            // Sold by weight
            if ($this->getItem()->variant) {
                return $price * $this->quantity * $this->getItem()->variant['package_amount'];
            } else {
                return $price * $this->quantity * $this->getItem()->product['package_amount'];
            }
        } else {
            // Sold by product
            return $price * $this->quantity;
        }
    }

    /**
     * Get item unit.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->getItem()->producer['currency'];
    }

    /**
     * Get price and unit.
     *
     * @return string
     */
    public function getPriceWithUnit()
    {
        $prefix = '';
        if (\UnitsHelper::isStandardUnit($this->getItem()->product['price_unit'])) {
            $prefix = '<span class="approx">&asymp;</span>';
        }

        return $prefix . $this->getPrice() . ' ' . $this->getUnit();
    }

    /**
     * Get price and unit html.
     *
     * @return string
     */
    public function getPriceWithUnitHtml()
    {
        $prefix = '';
        if (\UnitsHelper::isStandardUnit($this->getItem()->product['price_unit'])) {
            $prefix = '<span class="approx">&asymp;</span>';
        }

        return '<h3 class="price">' . $prefix . $this->getPrice() . '</h3><div class="unit">' . $this->getUnit() . '</div>';
    }
}
