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
    public function get($key, $default = null)
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
    public function set($key, $value, $ttl = null)
    {
        $item = new Item($key, $value);
        if ($ttl !== null) {
            $item->expiresAfter($ttl);
        }
        return $this->save($item);
    }


    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete
     *
     * @return bool
     */
    public function delete($key)
    {
        return $this->deleteItem($key);
    }


    /**
     * Obtains multiple cache items by their unique keys
     *
     * @param iterable $keys A list of keys that can obtained in a single operation
     * @param mixed $default Default value to return for keys that do not exist
     *
     * @return iterable A list of key => value pairs
     */
    public function getMultiple($keys, $default = null)
    {
        $this->validateKeys($keys);

        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }


    /**
     * Persists a set of key => value pairs in the cache.
     *
     * @param iterable $values A list of key => value pairs for a multiple-set operation
     * @param \DateInterval|int|null $ttl The TTL value of this item
     *
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        $this->validateKeys($values);

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
     * @param iterable $keys A list of string-based keys to be deleted
     *
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        $this->validateKeys($keys);

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
     *
     * @param string $key The cache item key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->hasItem($key);
    }
}
