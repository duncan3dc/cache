<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\CacheCallsTrait;
use Psr\SimpleCache\CacheInterface;

class CacheCalls
{
    use CacheCallsTrait;

    private int $counter1 = 0;

    private int $counter2 = 0;

    private int $counter3 = 0;


    public function __construct(?CacheInterface $pool = null)
    {
        if ($pool !== null) {
            $this->setCacheCallsPool($pool);
        }
    }


    public function normalMethod(): string
    {
        return "ok";
    }


    public function _justOnce(): string
    {
        ++$this->counter1;
        return "called_{$this->counter1}_times";
    }


    public function _withParams(string $one, string $two, string $three): string
    {
        ++$this->counter2;
        return "{$one}_{$two}_{$three}_{$this->counter2}";
    }


    public function manualOnce(): string
    {
        return $this->cacheMethod("manualOnce");
    }


    public function _manualOnce(): string
    {
        ++$this->counter3;
        return "called_{$this->counter3}_times";
    }
}
