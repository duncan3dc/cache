<?php

namespace duncan3dc\CacheTests;

use ArrayIterator;
use duncan3dc\Cache\CacheInterface;
use duncan3dc\Cache\Exceptions\CacheException;
use duncan3dc\Cache\Exceptions\CacheKeyException;
use duncan3dc\Cache\Item;
use PHPUnit\Framework\TestCase;

abstract class AbstractPoolTest extends TestCase
{
    /** @var CacheInterface */
    private $pool;


    abstract protected function getPool(): CacheInterface;


    public function setUp(): void
    {
        $this->pool = $this->getPool();
    }


    public function tearDown(): void
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
        $this->pool->set("one", 1);
        $this->pool->set("two", 2);
        $this->assertTrue($this->pool->has("one"));
        $this->assertTrue($this->pool->has("two"));

        $this->assertTrue($this->pool->clear());

        $this->assertFalse($this->pool->has("one"));
        $this->assertFalse($this->pool->has("two"));
    }


    public function testDeleteItem()
    {
        $this->testSave();

        $this->assertTrue($this->pool->deleteItem("luke"));
        $this->assertNull($this->pool->getItem("luke")->get());
    }


    public function testDeleteItems()
    {
        $this->testSave();

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


    public function testGet1()
    {
        $this->assertTrue($this->pool->set("episode7", "the force awakens"));
        $this->assertSame("the force awakens", $this->pool->get("episode7"));
    }
    public function testGet2()
    {
        $this->assertNull($this->pool->get("episode8"));
    }
    public function testGet3()
    {
        $result = $this->pool->get("episode8", "the last jedi");
        $this->assertSame("the last jedi", $result);
    }
    public function testGet4()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Cache key must be a string, integer given");
        $this->pool->get(404);
    }


    public function testSet1()
    {
        $result = $this->pool->set("snarky_puppy", "culcha vulcha");
        $this->assertTrue($result);
        $this->assertSame("culcha vulcha", $this->pool->get("snarky_puppy", "shofukan"));
    }
    public function testSet2()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Cache key must be a string, integer given");
        $this->pool->set(404, "value");
    }


    public function testSetWithTtl1()
    {
        $result = $this->pool->set("novallo", "white phoenix", 7);
        $this->assertTrue($result);
        $this->assertSame("white phoenix", $this->pool->get("novallo", "betty phage"));
    }
    public function testSetWithTtl2()
    {
        $result = $this->pool->set("novallo", "white phoenix", -7);
        $this->assertTrue($result);
        $this->assertSame("betty phage", $this->pool->get("novallo", "betty phage"));
    }
    public function testSetWithTtl3()
    {
        $interval = new \DateInterval("PT7S");
        $result = $this->pool->set("novallo", "white phoenix", $interval);
        $this->assertTrue($result);
        $this->assertSame("white phoenix", $this->pool->get("novallo", "betty phage"));
    }
    public function testSetWithTtl4()
    {
        $interval = new \DateInterval("PT7S");
        $interval->invert = 1;
        $result = $this->pool->set("novallo", "white phoenix", $interval);
        $this->assertTrue($result);
        $this->assertSame("betty phage", $this->pool->get("novallo", "betty phage"));
    }


    public function testDelete1()
    {
        $this->pool->set("snarky_puppy", "culcha vulcha");
        $result = $this->pool->delete("snarky_puppy");
        $this->assertTrue($result);
        $this->assertFalse($this->pool->has("snarky_puppy"));
    }
    public function testDelete2()
    {
        $result = $this->pool->delete("does-not-exist");
        $this->assertTrue($result);
        $this->assertFalse($this->pool->has("does-not-exist"));
    }
    public function testDelete3()
    {
        $this->expectException("Psr\Cache\CacheException");
        $this->expectExceptionMessage("Cache key must be a string, integer given");
        $this->pool->delete(404);
    }


    public function testGetMultiple1()
    {
        $result = $this->pool->getMultiple(["episode7", "episode8", "episode9"]);

        $this->assertSame([
            "episode7"  =>  null,
            "episode8"  =>  null,
            "episode9"  =>  null,
        ], $result);
    }
    public function testGetMultiple2()
    {
        $this->assertTrue($this->pool->set("episode7", "the force awakens"));

        $result = $this->pool->getMultiple(["episode7", "episode8", "episode9"]);

        $this->assertSame([
            "episode7"  =>  "the force awakens",
            "episode8"  =>  null,
            "episode9"  =>  null,
        ], $result);
    }
    public function testGetMultiple3()
    {
        $this->assertTrue($this->pool->set("episode7", "the force awakens"));
        $this->assertTrue($this->pool->set("episode8", "the last jedi"));

        $result = $this->pool->getMultiple(["episode7", "episode8", "episode9"], "unknown");

        $this->assertSame([
            "episode7"  =>  "the force awakens",
            "episode8"  =>  "the last jedi",
            "episode9"  =>  "unknown",
        ], $result);
    }
    public function testGetMultiple4()
    {
        $this->assertTrue($this->pool->set("episode7", "the force awakens"));
        $this->assertTrue($this->pool->set("episode8", "the last jedi"));

        $result = $this->pool->getMultiple(new ArrayIterator(["episode7", "episode8", "episode9"]), "unknown");

        $this->assertSame([
            "episode7"  =>  "the force awakens",
            "episode8"  =>  "the last jedi",
            "episode9"  =>  "unknown",
        ], $result);
    }
    public function testGetMultiple5()
    {
        $this->expectException("Psr\SimpleCache\InvalidArgumentException");
        $this->expectExceptionMessage("Invalid keys, must be iterable");
        $this->pool->getMultiple(new \DateTime());
    }
    public function testGetMultiple6()
    {
        $this->expectException("Psr\Cache\InvalidArgumentException");
        $this->expectExceptionMessage("Cache key must be a string, integer given");
        $this->pool->getMultiple(["ok", 77]);
    }


    public function testSetMultiple1()
    {
        $this->assertTrue($this->pool->setMultiple([
            "episode4"  =>  "a new hope",
            "episode5"  =>  "the empire strikes back",
        ]));

        $this->assertSame("a new hope", $this->pool->get("episode4"));
        $this->assertSame("the empire strikes back", $this->pool->get("episode5"));
    }
    public function testSetMultiple2()
    {
        $this->expectException("Psr\Cache\CacheException");
        $this->expectExceptionMessage("Invalid keys, must be iterable");
        $this->pool->setMultiple(new \DateTime());
    }
    public function testSetMultiple3()
    {
        $this->expectException("Psr\SimpleCache\CacheException");
        $this->expectExceptionMessage("Cache key must be a string, integer given");
        $this->pool->setMultiple(["ok" => 1, 77 => 2]);
    }


    public function testDeleteMultiple1()
    {
        $this->assertTrue($this->pool->setMultiple([
            "episode4"  =>  "a new hope",
            "episode5"  =>  "the empire strikes back",
            "episode6"  =>  "return of the jedi",
        ]));

        $this->assertTrue($this->pool->deleteMultiple(["episode4", "episode6"]));

        $this->assertNull($this->pool->get("episode4"));
        $this->assertSame("the empire strikes back", $this->pool->get("episode5"));
        $this->assertNull($this->pool->get("episode6"));
    }
    public function testDeleteMultiple2()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Invalid keys, must be iterable");
        $this->pool->DeleteMultiple(new \DateTime());
    }
    public function testDeleteMultiple3()
    {
        $this->expectException(CacheException::class);
        $this->expectExceptionMessage("Cache key must be a string, integer given");
        $this->pool->DeleteMultiple(["ok", 77]);
    }


    public function testHas1()
    {
        $result = $this->pool->has("no-such-key");
        $this->assertFalse($result);
    }
    public function testHas2()
    {
        $this->pool->set("haken", "affinity");
        $result = $this->pool->has("haken");
        $this->assertTrue($result);
    }
    public function testHas3()
    {
        $this->pool->set("afi", "decemberunderground");
        $this->assertTrue($this->pool->has("afi"));
        $this->pool->delete("afi");
        $result = $this->pool->has("afi");
        $this->assertFalse($result);
    }
    public function testHas4()
    {
        $this->pool->set("periphery", "clear");
        $this->assertTrue($this->pool->has("periphery"));
        $this->pool->clear();
        $result = $this->pool->has("periphery");
        $this->assertFalse($result);
    }
    public function testHas5()
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage("Cache key must be a string, integer given");
        $this->pool->has(123);
    }


    public function testCustomItem1()
    {
        $item = new CustomItem("coheed", "cambria");
        $this->pool->save($item);

        $result = $this->pool->getItem("coheed");

        $this->assertInstanceOf(CustomItem::class, $result);
    }
    public function testCustomItem2()
    {
        $item = new CustomItem("coheed", "cambria");
        $this->pool->save($item);

        $result = $this->pool->getItem("coheed");

        $this->assertSame("cambria", $result->get());
    }
    public function testCustomItem3()
    {
        $item = new CustomItem("coheed", "cambria");
        $this->pool->save($item);

        $result = $this->pool->get("coheed");

        $this->assertSame("cambria", $result);
    }
}
