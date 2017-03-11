<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\ArrayPool;

class ArrayPoolTest extends AbstractPoolTest
{
    protected function getPool()
    {
        return new ArrayPool;
    }
}
