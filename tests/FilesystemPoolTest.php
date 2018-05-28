<?php

namespace duncan3dc\CacheTests;

use duncan3dc\Cache\CacheInterface;
use duncan3dc\Cache\FilesystemPool;
use duncan3dc\ObjectIntruder\Intruder;

class FilesystemPoolTest extends AbstractPoolTest
{
    private $path;

    public function setUp()
    {
        $this->path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "duncan3dc-cache-pool-phpunit";

        parent::setUp();
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
}
