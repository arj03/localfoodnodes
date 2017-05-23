<?php

namespace App\Event;

interface EventOwnerInterface
{
    /**
     * Return event owner entity name.
     *
     * @return string
     */
    public function eventOwnerType();

    /**
     * Url part.
     *
     * @return string
     */
    public function eventOwnerUrl();
}
