<?php

namespace duncan3dc\Cache;

use Psr\Cache\CacheItemInterface;

trait SimpleCacheTrait
{
    /**
     * Fetches a value from the cache.
     *
     * @param string $key The unique key of this item in the cache
     * @param mixed $default Default value to return if the key does not exist
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->has($key)) {
            /** @var CacheItemInterface $item */
            $item = $this->getItem($key);
            if ($item->isHit()) {
                return $item->get();
            }
        }

        return $default;
    }


    /**
     * Persists data in the cache, uniquely referenced by a key.
     *
     * @param string $key The key of the item to store
     * @param mixed $value The value of the item to store, must be serializable
     * @param \DateInterval|int|null $ttl The TTL value of this item
     *
     * @return bool
     */
    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $item = new Item($key, $value);
        if ($ttl !== null) {
            $item->expiresAfter($ttl);
        }
        return $this->save($item);
    }


    /**
     * Delete an item from the cache by its unique key.
     */
    public function delete(string $key): bool
    {
        return $this->deleteItem($key);
    }


    /**
     * Obtains multiple cache items by their unique keys
     *
     * @param iterable<string> $keys A list of keys that can obtained in a single operation
     * @param mixed $default Default value to return for keys that do not exist
     *
     * @return iterable<string, mixed> A list of key => value pairs
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }


    /**
     * Persists a set of key => value pairs in the cache.
     *
     * @param iterable<string, mixed> $values A list of key => value pairs for a multiple-set operation
     */
    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        $result = true;
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $result = false;
            }
        }

        return $result;
    }


    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable<string> $keys A list of string-based keys to be deleted
     */
    public function deleteMultiple(iterable $keys): bool
    {
        $result = true;
        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                $result = false;
            }
        }

        return $result;
    }


    /**
     * Determines whether an item is present in the cache.
     */
    public function has(string $key): bool
    {
        return $this->hasItem($key);
    }
}
