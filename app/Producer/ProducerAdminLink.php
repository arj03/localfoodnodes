<?php

namespace App\Producer;

class ProducerAdminLink extends \App\BaseModel
{
    public $timestamps = false;

    protected $with = ['userRelationship', 'producerRelationship'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'user_id' => 'required',
        'producer_id' => 'required',
        'active' => ''
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'producer_id',
        'active'
    ];

    /**
     * Define user relationship.
     *
     * @return Relation
     */
    public function userRelationship()
    {
        return $this->hasOne('App\User\User', 'id', 'user_id');
    }

    /**
     * Get user..
     *
     * @return User
     */
    public function getUser()
    {
        return $this->userRelationship;
    }

    /**
     * Define producer relationship.
     *
     * @return Relation
     */
    public function producerRelationship()
    {
        return $this->hasOne('App\Producer\Producer', 'id', 'producer_id');
    }

    /**
     * Get producer.
     *
     * @return Producer
     */
    public function getProducer()
    {
        return $this->producerRelationship;
    }
}
