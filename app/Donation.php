<?php

namespace App;

class Donation extends BaseModel
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'name' => 'required',
        'email' => 'required',
        'address' => '',
        'city' => '',
        'zip' => '',
        'size' => '',
        'amount' => 'integer|min:3',
        'stripe_token' => '',
        'uuid' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'city',
        'zip',
        'size',
        'amount',
        'stripe_token',
        'uuid',
    ];
}
