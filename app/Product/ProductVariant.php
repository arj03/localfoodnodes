<?php

namespace App\Product;

use App\BaseModel;

class ProductVariant extends BaseModel
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'product_id' => 'required',
        'name' => 'required',
        'price' => '',
        'package_amount' => 'required',
        'main_variant' => 'boolean',
        'quantity' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'package_amount',
        'main_variant',
        'quantity',
    ];

    /**
     * Get product.
     *
     * @return App\Product\Product
     */
    public function getProduct()
    {
        return $this->hasOne('App\Product\Product', 'id', 'product_id')->first();
    }

    /**
     * Get production quantity.
     *
     * @return int
     */
    public function getProductionQuantity()
    {
        $product = $this->getProduct();

        // error_log(var_export($product->variants_individual_quantity, true));

        if ($product->variants_individual_quantity) {
            return $quantity;
        } else {
            $smallestCommonDenominator = $product->getProductionQuantity() / $this->package_amount;
            $quantity = $smallestCommonDenominator * $product->mainVariant()->package_amount;

            return floor($quantity);
        }
    }

    /**
     * Get info to be stored with order.
     *
     * @return array
     */
    public function getInfoForOrder()
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'price' => $this->price,
            'package_amount' => $this->package_amount,
            'main_variant' => $this->main_variant,
        ];
    }
}
