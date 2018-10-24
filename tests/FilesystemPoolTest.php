<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\CacheInterface;
use duncan3dc\Cache\Exceptions\CacheException;
use duncan3dc\Cache\FilesystemPool;
use duncan3dc\ObjectIntruder\Intruder;
use function rmdir;
use function sys_get_temp_dir;

class FilesystemPoolTest extends AbstractPoolTest
{
    /** @var string */
    private $parent;

    /** @var string */
    private $path;


    public function setUp()
    {
        $this->parent = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . "duncan3dc-cache-phpunit";
        $this->path = $this->parent . \DIRECTORY_SEPARATOR . "sub-directory";

        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();

        rmdir($this->path);
        rmdir($this->parent);
    }


    protected function getPool(): CacheInterface
    {
        return new FilesystemPool($this->path);
    }


    public function pathProvider(): array
    {
        return [
            ["test", "test.cache"],
        ];
    }
    /**
     * @dataProvider pathProvider
     */
    public function testGetPath(string $key, string $path)
    {
        $intruder = new Intruder($this->getPool());

        $result = $intruder->getPath($key);

        $this->assertSame("{$this->path}/{$path}", $result);
    }


    public function testInvalidObject()
    {
        $pool = $this->getPool();

        $intruder = new Intruder($pool);
        $path = $intruder->getPath("trivium");

        file_put_contents($path, serialize(new \DateTime()));

        $this->expectException(CacheException::class);
        $this->expectExceptionMessage("Unexpected object during deserialization: DateTime");
        $pool->getItem("trivium");
    }
}
