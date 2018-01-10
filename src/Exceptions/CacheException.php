<?php

namespace duncan3dc\Cache\Exceptions;

use Psr\Cache\CacheException as Psr6Exception;
use Psr\SimpleCache\CacheException as Psr16Exception;

class CacheException extends \Exception implements Psr6Exception, Psr16Exception
{
}
