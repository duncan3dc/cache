<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\CacheKeyTrait;

class CacheKeys
{
    use CacheKeyTrait;

    public function __call($method, $args)
    {
        return $this->$method(...$args);
    }
}
