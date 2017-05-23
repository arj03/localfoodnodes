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
        'node_id',
        'node',
        'producer_id',
        'producer',
        'product_id',
        'product',
        'variant_id',
        'variant',
        'quantity',
        'ref' => 'required',
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
