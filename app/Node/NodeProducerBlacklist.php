<?php

namespace App\Node;

use App\BaseModel;

class NodeProducerBlacklist extends BaseModel
{
    protected $table = 'node_producer_blacklist';

    public $timestamps = false;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'node_id' => 'required',
        'producer_id' => 'required'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'node_id',
        'producer_id'
    ];
}
