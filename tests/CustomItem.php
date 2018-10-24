<?php

namespace duncan3dc\CacheTests;

use Psr\Cache\CacheItemInterface;
use function time;

class CustomItem implements CacheItemInterface
{
    /**
     * @var string The unique key of this item.
     */
    private $key;

    /**
     * @var mixed The current value of this item.
     */
    private $value;

    /**
     * @var int The expiration time of this item.
     */
    private $expiration;


    /**
     * @inheritDoc
     */
    public function __construct($key, $value = null)
    {
        $this->key = $key;
        if ($value !== null) {
            $this->set($value);
        }
    }


    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->value;
    }


    /**
     * @inheritDoc
     */
    public function isHit()
    {
        if ($this->value === null) {
            return false;
        }

        if ($this->expiration !== null) {
            if ($this->expiration < time()) {
                return false;
            }
        }

        return true;
    }


    /**
     * @inheritDoc
     */
    public function set($value)
    {
        $this->value = $value;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function expiresAt($expiration)
    {
        $this->expiration = $expiration->getTimestamp();

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function expiresAfter($time)
    {
        if ($time instanceof \DateInterval) {
            $time = (int) $time->format("%r%s");
        }

        $this->expiration = time() + $time;

        return $this;
    }
}
