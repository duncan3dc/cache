<?php

namespace duncan3dc\Cache;

use Psr\Cache\CacheItemInterface;

use function is_numeric;
use function time;

final class Item implements CacheItemInterface
{
    private string $key;

    private mixed $value = null;

    private ?int $expiration = null;


    /**
     * Create a new instance.
     *
     * @param string $key The unique key of this item
     * @param mixed $value The current value of this item
     */
    public function __construct(string $key, mixed $value = null)
    {
        $this->key = $key;
        if ($value !== null) {
            $this->set($value);
        }
    }


    /**
     * Returns the key for the current cache item.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }


    /**
     * Retrieves the value of the item from the cache associated with this object's key.
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->value;
    }


    /**
     * Confirms if the cache item lookup resulted in a cache hit.
     *
     * @return bool
     */
    public function isHit(): bool
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
     * Sets the value represented by this cache item.
     *
     * @param mixed $value The serializable value to be stored
     *
     * @return $this
     */
    public function set(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }


    /**
     * Sets the expiration time for this cache item.
     *
     * @param \DateTimeInterface|null $expiration The point in time after which the item MUST be considered expired
     *
     * @return $this
     */
    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        if ($expiration instanceof \DateTimeInterface) {
            $this->expiration = $expiration->getTimestamp();
        } elseif ($expiration === null) {
            $this->expiration = null;
        } else {
            throw new \InvalidArgumentException("Unexpected argument type passed to expiresAt()");
        }

        return $this;
    }


    /**
     * Sets the expiration time for this cache item.
     *
     * @param int|\DateInterval|null $time The period of time from now after which the item MUST be considered expired
     *
     * @return $this
     */
    public function expiresAfter(\DateInterval|int|null $time): static
    {
        if ($time instanceof \DateInterval) {
            $time = (int) $time->format("%r%s");
        }

        if ($time === null) {
            $this->expiration = null;
        } elseif (is_numeric($time)) {
            $this->expiration = time() + $time;
        } else {
            throw new \InvalidArgumentException("Unexpected argument type passed to expiresAfter()");
        }

        return $this;
    }
}
