<?php

namespace App\Order;

use Illuminate\Database\Eloquent\Collection;

class OrderDateItemLink extends \App\BaseModel
{
    // protected $with = ['orderItemRelationship', 'orderDateRelationship'];

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
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($orderDateItemLink) {
            $orderDateItemLink->getItem()->delete();
        });
    }

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
        if ($this->item) {
            return $this->item;
        }

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
        if ($this->date) {
            return $this->date;
        }

        return $this->orderDateRelationship->first();
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
            return round($this->quantity * $csaPrice);
        } else if (\UnitsHelper::isStandardUnit($this->getItem()->product['price_unit'])) {
            // Sold by weight
            if ($this->getItem()->variant) {
                $variant = $this->getItem()->variant;
                $packageAmount = isset($variant['package_amount']) ? $variant['package_amount'] : 1;

                return $price * $this->quantity * $packageAmount;
            } else {
                $product = $this->getItem()->product;
                $packageAmount = isset($product['package_amount']) ? $product['package_amount'] : 1;

                return $price * $this->quantity * $packageAmount;
            }
        } else {
            // Sold by product
            return $price * $this->quantity;
        }
    }

    /**
     * Get unit.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->getItem()->producer['currency'];
    }

    /**
     * Get price unit.
     *
     * @return string
     */
    public function getPriceWithUnit()
    {
        $prefix = '';
        if (\UnitsHelper::isStandardUnit($this->getItem()->product['price_unit'])) {
            $prefix = '<span class="approx">&asymp;</span>';
        }

        return $prefix . ' ' . $this->getPrice() . ' ' . $this->getUnit();
    }

    /**
     * Check if order is deletable.
     *
     * @return boolean
     */
    public function isDeletable()
    {
        // If date is missing, fix for bad data
        if (!$this->getDate()) {
            return true;
        }

        $product = $this->getItem()->getProduct();
        $productDeadline = $product->getDeadlineDate();
        $orderDeliveryDate = $this->getDate()->date;
        $interval = $productDeadline->diff($orderDeliveryDate);

        return (int) $interval->format('%r%a') < 0 ? false : true;
    }
}
