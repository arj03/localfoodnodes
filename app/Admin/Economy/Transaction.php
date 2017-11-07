<?php

namespace App\Admin\Economy;

class Transaction extends \App\BaseModel
{
    protected $table = 'economy_transactions';

    public $timestamps = false;

    /**
     * Validation rules.
     *
     * @var array
     */
    public $validationRules = [
        'hash' => 'required|unique:economy_transactions',
        'date' => 'required|date',
        'ref' => 'required',
        'description' => 'required',
        'amount' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash',
        'date',
        'ref',
        'description',
        'amount',
        'category',
    ];
}
