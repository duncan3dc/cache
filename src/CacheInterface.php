<?php

namespace duncan3dc\Cache;

use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

interface CacheInterface extends CacheItemPoolInterface, SimpleCacheInterface
{
}
