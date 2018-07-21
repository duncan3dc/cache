<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\ArrayPool;
use duncan3dc\Cache\CacheInterface;

class ArrayPoolTest extends AbstractPoolTest
{
    protected function getPool(): CacheInterface
    {
        return new ArrayPool();
    }
}
