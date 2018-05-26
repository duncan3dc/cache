<?php

namespace duncan3dc\CacheTests;

use duncan3dc\PhpIni\State;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class CacheCallsTraitTest extends TestCase
{
    private $instance;

    public function setUp()
    {
        $this->instance = new CacheCalls;
    }


    public function testNormalMethod()
    {
        $this->assertSame("ok", $this->instance->normalMethod());
    }


    public function testCachedMethod()
    {
        $this->assertSame("called_1_times", $this->instance->justOnce());
        $this->assertSame("called_1_times", $this->instance->justOnce());
    }


    public function testWithParams()
    {
        $this->assertSame("one_two_three_1", $this->instance->withParams("one", "two", "three"));
        $this->assertSame("one_two_new_2", $this->instance->withParams("one", "two", "new"));
        $this->assertSame("one_two_three_1", $this->instance->withParams("one", "two", "three"));
    }


    public function testManualMethod()
    {
        $this->assertSame("called_1_times", $this->instance->manualOnce());
        $this->assertSame("called_1_times", $this->instance->manualOnce());
    }


    public function testMethodDoesntExist()
    {
        $ini = new State;
        $ini->set("max_execution_time", 1);
        $ini->set("xdebug.max_nesting_level", 30);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage("Call to undefined method duncan3dc\CacheTests\CacheCalls::doesNotExist()");

        $ini->call(function () {
            $this->instance->doesNotExist();
        });
    }


    public function testSetCacheCallsPool()
    {
        $pool = Mockery::mock(CacheInterface::class);
        $pool->shouldReceive("has")->once()->andReturn(false);
        $pool->shouldReceive("set")->once();

        $instance = new CacheCalls($pool);

        $this->assertSame("called_1_times", $instance->justOnce());
    }
}
