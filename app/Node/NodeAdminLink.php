<?php

namespace App\Node;

class NodeAdminLink extends \App\BaseModel
{
    public $timestamps = false;

    protected $with = ['userRelationship', 'nodeRelationship'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'user_id' => 'required',
        'node_id' => 'required',
        'active' => ''
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'node_id',
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
     * Define node relationship.
     *
     * @return Relation
     */
    public function nodeRelationship()
    {
        return $this->hasOne('App\Node\Node', 'id', 'node_id');
    }

    /**
     * Get node.
     *
     * @return Node
     */
    public function getNode()
    {
        return $this->nodeRelationship;
    }
}
