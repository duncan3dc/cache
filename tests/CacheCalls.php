<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\CacheCallsTrait;
use Psr\SimpleCache\CacheInterface;

class CacheCalls
{
    use CacheCallsTrait;

    private $counter1 = 0;
    private $counter2 = 0;
    private $counter3 = 0;


    public function __construct(CacheInterface $pool = null)
    {
        if ($pool !== null) {
            $this->setCacheCallsPool($pool);
        }
    }


    public function normalMethod()
    {
        return "ok";
    }


    public function _justOnce()
    {
        ++$this->counter1;
        return "called_{$this->counter1}_times";
    }


    public function _withParams($one, $two, $three)
    {
        ++$this->counter2;
        return "{$one}_{$two}_{$three}_{$this->counter2}";
    }


    public function manualOnce()
    {
        return $this->cacheMethod("manualOnce");
    }


    public function _manualOnce()
    {
        ++$this->counter3;
        return "called_{$this->counter3}_times";
    }
}
