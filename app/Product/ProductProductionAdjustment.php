<?php

namespace App\Product;

class ProductProductionAdjustment extends \App\BaseModel
{
    public $timestamps = false;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'product_id' => 'required',
        'year' => 'required',
        'week' => 'required',
        'quantity' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'year',
        'week',
        'quantity',
    ];
}
