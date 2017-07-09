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
        $name = $this->getProductName();

        if ($this->variant) {
            $name .= ' - ' . $this->getVariantName();
        }

        return $name;
    }

    /**
     * Get product name.
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->product['name'];
    }

    /**
     * Get variant name.
     *
     * @return string
     */
    public function getVariantName()
    {
        $variantName = '';

        if ($this->variant) {
            $variantName = $this->variant['name'];

            if ($this->product['package_unit']) {
                $unit = trans_choice('units.' . $this->product['package_unit'], $this->variant['package_amount']);
                $variantName .= ' (' . $this->variant['package_amount'] .  $unit . ')';
            }
        }

        return $variantName;
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
     * Get item unit.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->producer['currency'];
    }

    /**
     * Get price and unit.
     *
     * @return string
     */
    public function getPriceWithUnit()
    {
        $prefix = '';
        if (\UnitsHelper::isStandardUnit($this->product['price_unit'])) {
            $prefix = '<span class="approx">&asymp;</span>';
        }

        return $prefix . $this->getPrice() . ' ' . $this->getUnit();
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        if ($this->product['production_type'] === 'csa') {
            return $this->cartDateItemLinks()->first()->quantity;
        } else {
            return $this->cartDateItemLinks()->sum->quantity;
        }
    }
}
