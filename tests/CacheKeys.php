<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\CacheKeyTrait;

class CacheKeys
{
    use CacheKeyTrait;

    /**
     * @param array<mixed> $args
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        return $this->$method(...$args);
    }
}
