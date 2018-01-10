<?php

namespace duncan3dc\Cache\Exceptions;

use Psr\Cache\InvalidArgumentException;
use Psr\SimpleCache\InvalidArgumentException as SimpleInvalidArgumentException;

class CacheKeyException extends CacheException implements InvalidArgumentException, SimpleInvalidArgumentException
{
}
