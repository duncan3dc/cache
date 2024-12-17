<?php

namespace duncan3dc\Cache;

use duncan3dc\Cache\Exceptions\CacheKeyException;
use Psr\Cache\CacheItemInterface;

use function array_key_exists;

final class ArrayPool implements CacheInterface
{
    use CacheKeyTrait;
    use SimpleCacheTrait;

    /**
     * @var array<string, CacheItemInterface> The array to store the cache data in.
     */
    private array $data = [];


    /**
     * Returns a Cache Item representing the specified key.
     *
     * @param string $key The key for which to return the corresponding Cache Item.
     *
     * @return CacheItemInterface
     * @throws CacheKeyException
     */
    public function getItem(string $key): CacheItemInterface
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
     * @param array<string> $keys An indexed array of keys of items to retrieve.
     *
     * @return iterable<string, CacheItemInterface>
     * @throws CacheKeyException
     */
    public function getItems(array $keys = []): iterable
    {
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
     * @throws CacheKeyException
     */
    public function hasItem(string $key): bool
    {
        $this->validateKey($key);

        return array_key_exists($key, $this->data);
    }


    /**
     * Deletes all items in the pool.
     *
     * @return bool
     */
    public function clear(): bool
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
     * @throws CacheKeyException
     */
    public function deleteItem(string $key): bool
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
     * @param array<string> $keys An array of keys that should be removed from the pool
     *
     * @return bool
     * @throws CacheKeyException
     */
    public function deleteItems(array $keys): bool
    {
        foreach ($keys as $key) {
            $this->deleteItem($key);
        }

        return true;
    }


    /**
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item The cache item to save
     *
     * @return bool
     * @throws CacheKeyException
     */
    public function save(CacheItemInterface $item): bool
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
     * @throws CacheKeyException
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->save($item);
    }


    /**
     * Persists any deferred cache items.
     *
     * @return bool
     */
    public function commit(): bool
    {
        return true;
    }
}
