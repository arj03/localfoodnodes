<?php

namespace App\Producer;

class ProducerNodeLink extends \App\BaseModel
{
    public $timestamps = false;

    protected $with = ['producerRelationship', 'nodeRelationship'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'producer_id',
        'node_id',
    ];

    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($producerNodeLink) {
            $producer = $producerNodeLink->getProducer();
            $node = $producerNodeLink->getNode();
            $products = $producer->products();

            $products->each(function($product) use ($node) {
                $product->deliveryLinks($node->id)->each->delete();
            });
        });
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
