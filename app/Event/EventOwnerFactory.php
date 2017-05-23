<?php

namespace App\Event;

class EventOwnerFactory
{
    /**
     * Return owner object.
     *
     * @param string $type
     * @param int $id
     * @return Node|Producer
     */
    public static function create($type, $id)
    {
        if ($type === 'node') {
            return \App\Node\Node::find($id);
        } else if ($type === 'producer') {
            return \App\Producer\Producer::find($id);
        }
    }
}
