<?php

namespace duncan3dc\Cache;

use duncan3dc\Cache\Exceptions\CacheKeyException;

use function preg_match;
use function strlen;

trait CacheKeyTrait
{
    /**
     * Check the passed key to ensure it's a valid cache key.
     * @throws CacheKeyException
     */
    protected function validateKey(string $key): void
    {
        if (preg_match("/[^A-Za-z0-9\._-]/", $key)) {
            throw new CacheKeyException("Cache key contains invalid characters: {$key}");
        }

        if (strlen($key) > 64) {
            throw new CacheKeyException("Cache key cannot be longer than 64 characters: {$key}");
        }
    }
}
