<?php

namespace App\Product;

use Illuminate\Support\Facades\DB;

use App\BaseModel;

class ProductTag extends BaseModel
{
    public $timestamps = false;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'product_id' => 'required',
        'tag' => 'required'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'tag'
    ];
}
