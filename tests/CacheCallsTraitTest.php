<?php

namespace duncan3dc\CacheTests;

use Mockery;
use Psr\SimpleCache\CacheInterface;

class CacheCallsTraitTest extends \PHPUnit_Framework_TestCase
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


    public function testSetCacheCallsPool()
    {
        $pool = Mockery::mock(CacheInterface::class);
        $pool->shouldReceive("has")->once()->andReturn(false);
        $pool->shouldReceive("set")->once();

        $instance = new CacheCalls($pool);

        $this->assertSame("called_1_times", $instance->justOnce());
    }
}
