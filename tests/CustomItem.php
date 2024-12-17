<?php

namespace duncan3dc\CacheTests;

use Psr\Cache\CacheItemInterface;

use function is_numeric;
use function time;

class CustomItem implements CacheItemInterface
{
    private string $key;

    private mixed $value = null;

    private ?int $expiration = null;


    public function __construct(string $key, mixed $value = null)
    {
        $this->key = $key;
        if ($value !== null) {
            $this->set($value);
        }
    }


    public function getKey(): string
    {
        return $this->key;
    }


    public function get(): mixed
    {
        return $this->value;
    }


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


    public function set(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }


    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        if ($expiration instanceof \DateTimeInterface) {
            $this->expiration = $expiration->getTimestamp();
        } else {
            $this->expiration = $expiration;
        }

        return $this;
    }


    public function expiresAfter(\DateInterval|int|null $time): static
    {
        if ($time instanceof \DateInterval) {
            $time = (int) $time->format("%r%s");
        }

        if (is_numeric($time)) {
            $this->expiration = time() + $time;
        } else {
            $this->expiration = $time;
        }

        return $this;
    }
}
