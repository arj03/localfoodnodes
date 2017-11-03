<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;

class DateTime extends \DateTime implements Arrayable
{
    public function __construct(string $time = "now" , \DateTimeZone $timezone = NULL ) {
        parent::__construct($time, new \DateTimeZone("UTC"));
    }

    public function toArray() {
        return (array) $this;
    }
}
