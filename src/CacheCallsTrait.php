<?php

namespace duncan3dc\Cache;

use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

trait CacheCallsTrait
{
    private $_cache;


    protected function setCacheCallsPool(SimpleCacheInterface $pool)
    {
        $this->_cache = $pool;
    }


    private function getCacheCallsPool()
    {
        if ($this->_cache === null) {
            $this->_cache = new ArrayPool;
        }

        return $this->_cache;
    }


    public function cacheMethod($method, ...$args)
    {
        # Generatea a key for this method call and it's arguments
        $key = sha1($method . print_r($args, true));

        /**
         * If we've already run this method with these arguments
         * before then return the previously cached result.
         */
        if ($this->getCacheCallsPool()->has($key)) {
            return $this->getCacheCallsPool()->get($key);
        }

        /**
         * Call the underlying method that provides the result.
         * Eg, when calling code does $instance->getData(), a single call
         * to $instance->_getData() will be issued and then future calls
         * to $instance->getData() will return the previously cached value.
         */
        $call = "_{$method}";
        $result = $this->$call(...$args);

        # Store the result for future calls
        $this->getCacheCallsPool()->set($key, $result);

        return $result;
    }


    public function __call($method, $args)
    {
        return $this->cacheMethod($method, ...$args);
    }
}
