<?php

namespace duncan3dc\CacheTests;

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


    protected function getPool()
    {
        return new FilesystemPool($this->path);
    }


    public function pathProvider()
    {
        return [
            ["test", "test.cache"],
        ];
    }
    /**
     * @dataProvider pathProvider
     */
    public function testGetPath($key, $path)
    {
        $intruder = new Intruder($this->getPool());

        $result = $intruder->getPath($key);

        $this->assertSame("{$this->path}/{$path}", $result);
    }
}
