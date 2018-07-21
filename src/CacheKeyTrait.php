<?php

namespace duncan3dc\Cache;

use duncan3dc\Cache\Exceptions\CacheKeyException;
use function is_array;
use function is_string;
use function preg_match;
use function strlen;

trait CacheKeyTrait
{
    /**
     * Check the passed key to ensure it's a valid cache key.
     *
     * @param string $key
     *
     * @return void
     * @throws CacheKeyException
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
     * @throws CacheKeyException
     */
    private function validateKeys($keys)
    {
        if (is_array($keys)) {
            return;
        }

        if ($keys instanceof \Traversable) {
            return;
        }

        throw new CacheKeyException("Invalid keys, must be iterable");
    }
}
