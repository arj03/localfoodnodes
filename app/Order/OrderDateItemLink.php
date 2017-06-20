<?php

namespace App\Order;

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

            // If OrderDate count is 1, this is the last OrderItemDateLink connected and is safe to remove.
            if ($orderDateItemLink->getDate()->orderDateItemLinks()->count() <= 1) {
                $orderDateItemLink->getDate()->delete();
            }
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
    * Get item price.
    *
    * @return int
    */
    public function getPrice()
    {
        $price = $this->getItem()->variant ?  $this->getItem()->variant['price'] : $this->getItem()->product['price'];

        if ($this->getItem()->product['price_unit'] === 'product') {
            // Sold by product
            return $price * $this->quantity;
        } else {
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
        if ($this->getItem()->product['price_unit'] !== 'product') {
            $prefix = '<span class="approx">&asymp;</span>';
        }

        return $prefix . ' ' . $this->getPrice() . ' ' . $this->getUnit();
    }

    public function isDeletable()
    {
        $product = $this->getItem()->getProduct();
        $productDeadline = $product->getDeadlineDate();
        $orderDeliveryDate = $this->getDate()->date;
        $interval = $productDeadline->diff($orderDeliveryDate);

        return (int) $interval->format('%r%a') < 0 ? false : true;
    }
}
