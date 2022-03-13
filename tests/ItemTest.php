<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testGetKey(): void
    {
        $item = new Item("test");

        $this->assertSame("test", $item->getKey());
    }


    public function testGetNoValue(): void
    {
        $item = new Item("test");

        $this->assertNull($item->get());
    }


    public function testGetBool(): void
    {
        $item = new Item("test", false);

        $this->assertFalse($item->get());
    }


    public function testGetString(): void
    {
        $item = new Item("test", "true");

        $this->assertSame("true", $item->get());
    }


    public function testSet(): void
    {
        $item = new Item("test");

        $value = (object) [
            "field" =>  "value",
        ];

        $result = $item->set($value);

        $this->assertSame($item, $result);
        $this->assertSame($value, $item->get());
    }


    public function testIsHit1(): void
    {
        $item = new Item("test");
        $item->set("ok");

        $this->assertTrue($item->isHit());
    }
    public function testIsHit2(): void
    {
        $item = new Item("test");

        $this->assertFalse($item->isHit());
    }


    public function testExpiresAt1(): void
    {
        $item = new Item("test", "value");
        $this->assertSame(true, $item->isHit());

        $item->expiresAt(new \DateTime("2018-07-04 13:27:01"));
        $this->assertSame(false, $item->isHit());
    }
    public function testExpiresAt2(): void
    {
        $item = new Item("test", "value");
        $item->expiresAt(new \DateTime("2018-07-04 13:27:01"));
        $this->assertSame(false, $item->isHit());

        $item->expiresAt(null);
        $this->assertSame(true, $item->isHit());
    }
    public function testExpiresAt3(): void
    {
        $item = new Item("test", "value");

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unexpected argument type passed to expiresAt()");
        $item->expiresAt("balloon");
    }


    public function testExpiresAfter1(): void
    {
        $item = new Item("test", "value");
        $this->assertSame(true, $item->isHit());

        $item->expiresAfter(-7);
        $this->assertSame(false, $item->isHit());
    }
    public function testExpiresAfter2(): void
    {
        $item = new Item("test", "value");
        $this->assertSame(true, $item->isHit());

        $interval = new \DateInterval("PT7S");
        $interval->invert = 1;
        $item->expiresAfter($interval);
        $this->assertSame(false, $item->isHit());
    }
    public function testExpiresAfter3(): void
    {
        $item = new Item("test", "value");
        $item->expiresAfter(-7);
        $this->assertSame(false, $item->isHit());

        $item->expiresAfter(null);
        $this->assertSame(true, $item->isHit());
    }
    public function testExpiresAfter4(): void
    {
        $item = new Item("test", "value");

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unexpected argument type passed to expiresAfter()");
        $item->expiresAfter("balloon");
    }
}
