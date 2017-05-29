<?php

namespace App\Product\Production;

class WeekAdjustment extends \App\BaseModel
{
    public $timestamps = false;

    protected $table = 'product_production_week_adjustments';

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
