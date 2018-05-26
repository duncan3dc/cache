<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{

    public function testGetKey()
    {
        $item = new Item("test");

        $this->assertSame("test", $item->getKey());
    }


    public function testGetNoValue()
    {
        $item = new Item("test");

        $this->assertNull($item->get());
    }


    public function testGetBool()
    {
        $item = new Item("test", false);

        $this->assertFalse($item->get());
    }


    public function testGetString()
    {
        $item = new Item("test", "true");

        $this->assertSame("true", $item->get());
    }


    public function testSet()
    {
        $item = new Item("test");

        $value = (object) [
            "field" =>  "value",
        ];

        $result = $item->set($value);

        $this->assertSame($item, $result);
        $this->assertSame($value, $item->get());
    }


    public function testIsHit1()
    {
        $item = new Item("test");
        $item->set("ok");

        $this->assertTrue($item->isHit());
    }
    public function testIsHit2()
    {
        $item = new Item("test");

        $this->assertFalse($item->isHit());
    }
}
