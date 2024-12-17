<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\Exceptions\CacheKeyException;
use PHPUnit\Framework\TestCase;

use function str_repeat;

class CacheKeysTraitTest extends TestCase
{
    private CacheKeys $cache;

    protected function setUp(): void
    {
        $this->cache = new CacheKeys();
    }


    /**
     * @return iterable<array<mixed>>
     */
    public function goodKeyProvider()
    {
        $keys = [
            "ok",
            "ok_1_2",
            "ABC-abc",
        ];
        foreach ($keys as $key) {
            yield [$key];
        }
    }


    /**
     * @dataProvider goodKeyProvider
     * @doesNotPerformAssertions
     */
    public function testValidateGoodKey(string $key): void
    {
        $this->cache->validateKey($key);
    }


    /**
     * @return iterable<array<mixed>>
     */
    public function badKeyProvider()
    {
        $keys = [
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
    }


    /**
     * @dataProvider badKeyProvider
     */
    public function testValidateBadKey(string $key, string $expected): void
    {
        $this->expectException(CacheKeyException::class);
        $this->expectExceptionMessage($expected);
        $this->cache->validateKey($key);
    }
}
