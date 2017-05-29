<?php

namespace App\Product\Production;

class DeliveryAdjustment extends \App\BaseModel
{
    public $timestamps = false;

    protected $table = 'product_production_delivery_adjustments';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'product_id' => 'required',
        'date' => 'required',
        'quantity' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'date',
        'quantity',
    ];
}
