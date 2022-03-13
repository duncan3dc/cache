<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\Exceptions\CacheKeyException;
use PHPUnit\Framework\TestCase;

use function str_repeat;

class CacheKeysTraitTest extends TestCase
{
    /** @var CacheKeys */
    private $cache;

    protected function setUp(): void
    {
        $this->cache = new CacheKeys();
    }


    /**
     * @return iterable<array<mixed>>
     */
    public function validateKeyProvider()
    {
        $keys = [
            "ok"        =>  null,
            "ok_1_2"    =>  null,
            "ABC-abc"   =>  null,
            "NOT_OK_@"  =>  "Cache key contains invalid characters",
            "No Spaces" =>  "Cache key contains invalid characters",
            "!NOPE"     =>  "Cache key contains invalid characters",
            "/root"     =>  "Cache key contains invalid characters",
            "\\escape"  =>  "Cache key contains invalid characters",
            "too" . str_repeat("o", 60) . "_long"  =>  "Cache key cannot be longer than 64 characters",
        ];
        foreach ($keys as $key => $expected) {
            yield [$key, $expected];
        }
        yield [false, "Cache key must be a string"];
        yield [null, "Cache key must be a string"];
        yield [[], "Cache key must be a string"];
        yield [new \DateTime(), "Cache key must be a string"];
    }
    /**
     * @dataProvider validateKeyProvider
     * @param mixed $key
     * @param mixed $expected
     */
    public function testValidateKey($key, $expected): void
    {
        if ($expected !== null) {
            $this->expectException(CacheKeyException::class);
            $this->expectExceptionMessage($expected);
        }

        $this->cache->validateKey($key);
        $this->assertTrue(true);
    }
}
