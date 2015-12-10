<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\Item;

abstract class AbstractPoolTest extends \PHPUnit_Framework_TestCase
{
    private $pool;


    abstract protected function getPool();


    public function setUp()
    {
        $this->pool = $this->getPool();
    }


    public function tearDown()
    {
        $this->pool->clear();
    }


    public function testGetItem1()
    {
        $episode7 = new Item("episode7", "the force awakens");
        $this->assertTrue($this->pool->save($episode7));
        $this->assertSame("the force awakens", $this->pool->getItem("episode7")->get());
    }
    public function testGetItem2()
    {
        $this->assertNull($this->pool->getItem("episode8")->get());
    }


    public function testGetItems()
    {
        $this->pool->save(new Item("episode4", "a new hope"));
        $this->pool->save(new Item("episode5", "the empire strikes back"));

        $items = [];
        foreach ($this->pool->getItems(["episode4", "episode5", "episode6"]) as $item) {
            $items[$item->getKey()] = $item->get();
        }

        $this->assertSame([
            "episode4"  =>  "a new hope",
            "episode5"  =>  "the empire strikes back",
            "episode6"  =>  null,
        ], $items);
    }


    public function testHasItem1()
    {
        $this->pool->save(new Item("episode7", "the force awakens"));
        $this->assertTrue($this->pool->hasItem("episode7"));
    }
    public function testHasItem2()
    {
        $this->assertFalse($this->pool->hasItem("episode8"));
    }


    public function testClear()
    {
        $this->pool->save(new Item("episode7", "the force awakens"));
        $this->assertTrue($this->pool->hasItem("episode7"));

        $this->assertTrue($this->pool->clear());

        $this->assertFalse($this->pool->hasItem("episode7"));
    }


    public function testDeleteItem()
    {
        $item = $this->testSave();

        $this->assertTrue($this->pool->deleteItem("luke"));
        $this->assertNull($this->pool->getItem("luke")->get());
    }


    public function testDeleteItems()
    {
        $luke = $this->testSave();

        $han = new Item("han", "solo");
        $this->assertTrue($this->pool->save($han));
        $this->assertSame("solo", $this->pool->getItem("han")->get());

        $sheev = new Item("sheev", "palpatine");
        $this->assertTrue($this->pool->save($sheev));
        $this->assertSame("palpatine", $this->pool->getItem("sheev")->get());

        $this->assertTrue($this->pool->deleteItems(["luke", "sheev"]));
        $this->assertNull($this->pool->getItem("luke")->get());
        $this->assertNull($this->pool->getItem("sheev")->get());
        $this->assertSame("solo", $this->pool->getItem("han")->get());
    }


    public function testSave()
    {
        $item = new Item("luke", "skywalker");

        $this->assertTrue($this->pool->save($item));
        $this->assertSame("skywalker", $this->pool->getItem("luke")->get());

        return $item;
    }


    public function testSaveDeferred()
    {
        $item = new Item("luke", "skywalker");

        $this->assertTrue($this->pool->saveDeferred($item));
        $this->assertSame("skywalker", $this->pool->getItem("luke")->get());
    }


    public function testCommit()
    {
        $this->assertTrue($this->pool->commit());
    }
}
