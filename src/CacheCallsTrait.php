<?php

namespace duncan3dc\Cache;

use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

use function get_class;
use function method_exists;
use function print_r;
use function sha1;

trait CacheCallsTrait
{
    private ?SimpleCacheInterface $_cache = null;


    protected function setCacheCallsPool(SimpleCacheInterface $pool): void
    {
        $this->_cache = $pool;
    }


    private function getCacheCallsPool(): SimpleCacheInterface
    {
        if ($this->_cache === null) {
            $this->_cache = new ArrayPool();
        }

        return $this->_cache;
    }


    public function cacheMethod(string $method, ...$args): mixed
    {
        # Generates a key for this method call and its arguments
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

        if (!method_exists($this, $call)) {
            throw new \BadMethodCallException("Call to undefined method " . get_class($this) . "::{$method}()");
        }

        $result = $this->$call(...$args);

        # Store the result for future calls
        $this->getCacheCallsPool()->set($key, $result);

        return $result;
    }


    public function __call(string $method, array $args)
    {
        return $this->cacheMethod($method, ...$args);
    }
}
