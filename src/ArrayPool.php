<?php

namespace duncan3dc\Cache;

use Psr\Cache\CacheItemInterface;

class ArrayPool implements CacheInterface
{
    use CacheKeyTrait;
    use SimpleCacheTrait;

    /**
     * @var array $data The array to store the cache data in.
     */
    private $data = [];


    /**
     * Returns a Cache Item representing the specified key.
     *
     * @param string $key The key for which to return the corresponding Cache Item.
     *
     * @return CacheItemInterface
     */
    public function getItem($key)
    {
        $this->validateKey($key);

        if ($this->hasItem($key)) {
            return $this->data[$key];
        }

        # If we didn't find a suitable item then create a new one now
        return new Item($key);
    }


    /**
     * Returns a traversable set of cache items.
     *
     * @param array $keys An indexed array of keys of items to retrieve.
     *
     * @return \Traversable
     */
    public function getItems(array $keys = [])
    {
        $this->validateKeys($keys);

        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->getItem($key);
        }

        return $result;
    }


    /**
     * Confirms if the cache contains specified cache item.
     *
     * @param string $key The key for which to check existence.
     *
     * @return bool
     */
    public function hasItem($key)
    {
        $this->validateKey($key);

        return array_key_exists($key, $this->data);
    }


    /**
     * Deletes all items in the pool.
     *
     * @return bool
     */
    public function clear()
    {
        $this->data = [];

        return true;
    }


    /**
     * Removes the item from the pool.
     *
     * @param string $key The key for which to delete
     *
     * @return bool
     */
    public function deleteItem($key)
    {
        $this->validateKey($key);

        if ($this->hasItem($key)) {
            unset($this->data[$key]);
        }

        return true;
    }


    /**
     * Removes multiple items from the pool.
     *
     * @param array $keys An array of keys that should be removed from the pool
     *
     * @return bool
     */
    public function deleteItems(array $keys)
    {
        $this->validateKeys($keys);

        $result = true;

        foreach ($keys as $key) {
            if (!$this->deleteItem($key)) {
                $result = false;
            }
        }

        return $result;
    }


    /**
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item The cache item to save
     *
     * @return bool
     */
    public function save(CacheItemInterface $item)
    {
        $key = $item->getKey();

        $this->validateKey($key);

        $this->data[$key] = $item;

        return true;
    }


    /**
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item The cache item to save.
     *
     * @return bool
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        return $this->save($item);
    }


    /**
     * Persists any deferred cache items.
     *
     * @return bool
     */
    public function commit()
    {
        return true;
    }
}
