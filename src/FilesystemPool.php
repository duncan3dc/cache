<?php

namespace duncan3dc\Cache;

use duncan3dc\Cache\Exceptions\CacheKeyException;
use Psr\Cache\CacheItemInterface;
use function chmod;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function glob;
use function is_dir;
use function mkdir;
use function serialize;
use function unlink;
use function unserialize;

class FilesystemPool implements CacheInterface
{
    use CacheKeyTrait;
    use SimpleCacheTrait;

    /**
     * @var string The local path to store the cache files in.
     */
    private $path;

    /**
     * Create a new instance.
     *
     * @param string $path The local path to store the cache files in
     */
    public function __construct(string $path)
    {
        $this->path = $path;

        if (!is_dir($this->path)) {
            $result = mkdir($this->path, 0777, true);
            if ($result === true) {
                chmod($this->path, 0777);
            }
        }
    }


    /**
     * Get the path for a specified key.
     *
     * @param string $key The key to get the path for
     *
     * @return string
     */
    private function getPath(string $key): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $key . ".cache";
    }


    /**
     * Returns a Cache Item representing the specified key.
     *
     * @param string $key The key for which to return the corresponding Cache Item.
     *
     * @return CacheItemInterface
     * @throws CacheKeyException
     */
    public function getItem($key)
    {
        $this->validateKey($key);

        if ($this->hasItem($key)) {
            $data = file_get_contents($this->getPath($key));
            if ($data !== false) {
                $item = unserialize($data, [
                    "allowed_classes"   =>  [Item::class],
                ]);

                if ($item instanceof Item) {
                    return $item;
                }
            }
        }

        # If we didn't find a suitable item then create a new one now
        return new Item($key);
    }


    /**
     * Returns a traversable set of cache items.
     *
     * @param array $keys An indexed array of keys of items to retrieve.
     *
     * @return iterable
     * @throws CacheKeyException
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
     * @throws CacheKeyException
     */
    public function hasItem($key)
    {
        $this->validateKey($key);

        return file_exists($this->getPath($key));
    }


    /**
     * Deletes all items in the pool.
     *
     * @return bool
     */
    public function clear()
    {
        $result = true;

        foreach (glob("{$this->path}/*.cache") as $filename) {
            if (!unlink($filename)) {
                $result = false;
            }
        }

        return $result;
    }


    /**
     * Removes the item from the pool.
     *
     * @param string $key The key for which to delete
     *
     * @return bool
     * @throws CacheKeyException
     */
    public function deleteItem($key)
    {
        $this->validateKey($key);

        if (!$this->has($key)) {
            return true;
        }

        return unlink($this->getPath($key));
    }


    /**
     * Removes multiple items from the pool.
     *
     * @param array $keys An array of keys that should be removed from the pool
     *
     * @return bool
     * @throws CacheKeyException
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
     * @throws CacheKeyException
     */
    public function save(CacheItemInterface $item)
    {
        $key = $item->getKey();

        $this->validateKey($key);

        $data = serialize($item);

        $result = file_put_contents($this->getPath($key), $data);

        if ($result === false) {
            return false;
        }

        return true;
    }


    /**
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     * @throws CacheKeyException
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
