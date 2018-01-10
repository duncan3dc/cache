<?php

namespace duncan3dc\Cache;

use duncan3dc\Cache\Exceptions\CacheKeyException;

trait CacheKeyTrait
{
    /**
     * Check the passed key to ensure it's a valid cache key.
     *
     * @param string $key
     *
     * @return void
     */
    protected function validateKey($key)
    {
        if (!is_string($key)) {
            throw new CacheKeyException("Cache key must be a string, " . gettype($key) . " given");
        }

        if (preg_match("/[^A-Za-z0-9\._-]/", $key)) {
            throw new CacheKeyException("Cache key contains invalid characters: {$key}");
        }

        if (strlen($key) > 64) {
            throw new CacheKeyException("Cache key cannot be longer than 64 characters: {$key}");
        }
    }


    /**
     * Check the passed parameter to ensure it's a valid iterable.
     *
     * @param array|\Traversable $keys
     *
     * @return void
     */
    private function validateKeys($keys)
    {
        if (!is_array($keys) || $keys instanceof \Traversable) {
            throw new CacheKeyException("Invalid keys, must be iterable");
        }
    }
}
