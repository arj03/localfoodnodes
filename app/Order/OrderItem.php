<?php

namespace App\Order;

use \App\User\User;
use \App\Producer\Producer;
use \App\Product\Product;
use \App\Variant\Variant;
use \App\Node\Node;

class OrderItem extends \App\BaseModel
{
    public $timestamps = false;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'user_id' => 'required',
        'user' => 'required',
        'node_id' => 'required',
        'node' => 'required',
        'producer_id' => 'required',
        'producer' => 'required',
        'product_id' => 'required',
        'product' => 'required',
        'variant_id' => '',
        'variant' => '',
        'quantity' => 'required',
        'ref' => 'required',
        'message' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user',
        'node_id',
        'node',
        'producer_id',
        'producer',
        'variant_id',
        'variant',
        'product_id',
        'product',
        'quantity',
        'ref',
        'message',
    ];

    /**
     * Attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'user' => 'array',
        'node' => 'array',
        'producer' => 'array',
        'product' => 'array',
        'variant' => 'array',
    ];

    /**
     * Get all links related to this date.
     *
     * @return Collection
     */
    public function orderDateItemLink()
    {
        return $this->hasOne('App\Order\OrderDateItemLink', 'order_item_id', 'id')->first();
    }

    /**
     * Get order item name.
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->product['name'];

        if ($this->variant) {
            $name .= ' - ' . $this->variant['name'];
        }

        return $name;
    }

    /**
    * Get cart item price.
    *
    * @return int
    */
    public function getPrice()
    {
        if ($this->variant) {
            return $this->variant['price'];
        } else {
            return $this->product['price'];
        }
    }

    /**
     * Get current user, and stored user data as fallback.
     *
     * @return array
     */
    public function getUser()
    {
        $user = User::find($this->user_id);
        $user = $user ? $user->toArray() : $this->user;

        return $user;
    }

    /**
     * Get current producer or stored producer data as fallback.
     *
     * @return array
     */
    public function getProducer()
    {
        $producer = Producer::find($this->producer_id);
        $producer = $producer ? $producer->toArray() : $this->producer;

        return $producer;
    }

    /**
     * Get current product or stored product data as fallback.
     *
     * @return array
     */
    public function getProduct()
    {
        $product = Product::find($this->product_id);
        $producer = $product ? $product->toArray() : $this->product;

        return $product;
    }

    /**
     * Get current variant or stored variant data as fallback.
     *
     * @return array
     */
    public function getVariant()
    {
        $variant = Variant::find($this->variant_id);
        $variant = $variant ? $variant->toArray() : $this->variant;

        return $variant;
    }

    /**
     * Get current producer or stored producer data as fallback.
     *
     * @return array
     */
    public function getNode()
    {
        $node = Node::find($this->node_id);
        $node = $node ? $node->toArray() : $this->node;

        return $producer;
    }
}
