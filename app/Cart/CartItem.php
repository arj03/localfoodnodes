<?php

namespace App\Cart;

class CartItem extends \App\BaseModel
{
    public $timestamps = false;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'user_id' => 'required',
        'node_id' => 'required',
        'node' => 'required',
        'producer_id' => 'required',
        'producer' => 'required',
        'product_id' => 'required',
        'product' => 'required',
        'variant_id' => '',
        'variant' => '',
        'message' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'node_id',
        'node',
        'producer_id',
        'producer',
        'product_id',
        'product',
        'variant_id',
        'variant',
        'message',
    ];

    /**
     * Attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'node' => 'array',
        'product' => 'array',
        'variant' => 'array',
        'producer' => 'array',
    ];

    /**
     * Get all links related to this item.
     *
     * @return Collection
     */
    public function cartDateItemLinks()
    {
        return $this->hasMany('App\Cart\CartDateItemLink')->get();
    }

    /**
     * Get product.
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->hasMany('App\Product\Product', 'id', 'product_id')->first();
    }

    /**
     * Get producer.
     *
     * @return Producer
     */
    public function getProducer()
    {
        return $this->hasMany('App\Producer\Producer', 'id', 'producer_id')->first();
    }

    /**
     * Get node.
     *
     * @return Node
     */
    public function getNode()
    {
        return $this->hasMany('App\Node\Node', 'id', 'node_id')->first();
    }

    /**
     * Get cart item name.
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
}
