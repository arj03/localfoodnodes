<?php

namespace App\Product\Production;

use App\BaseModel;

class ProductProduction extends BaseModel
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'product_id' => 'required',
        'date' => 'date',
        'quantity' => 'required',
        'type' => 'required',
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
        'type'
    ];

    /**
     * Return date object.
     *
     * @param string $value
     * @return Date
     */
    public function getDateAttribute($value)
    {
        return new \DateTime($value);
    }
}
